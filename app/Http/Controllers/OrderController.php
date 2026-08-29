<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Http\Requests\OrderExportRequest;
use App\Models\{Cart, Order, OrderState, Payment, PaymentProvider, PaymentState, Product, Setting, Shipping, User};
use App\Services\{RoutingService, ShippingCostService};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\{Request, JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{

	public function __construct(
		public ShippingCostService $shippingService,
		public RoutingService $routingService
	) {}

	public function index(): View
	{
		return view('pages.dashboard.order.index', [
			'orders' => Order::orderByDesc('date')->paginate(10),
			'orderStates' => OrderState::all(['id', 'slug', 'name']),
			'users' => User::has('orders')->select('id', 'name', 'surname')->get(),
		]);
	}

	public function filter(OrderExportRequest $request): View
	{
		$orders = Order::query();
		$this->applyFilters($orders, $request->validated());
		$users = User::whereIn('id', $orders->pluck('user_id')->unique()->values())->get();

		return view('pages.dashboard.order.index', [
			'orders' => $orders->orderByDesc('date')->paginate(10),
			'orderStates' => OrderState::all(['id', 'slug', 'name']),
			'users' => $users,
		]);
	}

	public function show(String $id): View
	{
		$order = Order::findOrFail($id);
		return view('pages.dashboard.order.show', compact('order'));
	}

	public function store(Request $request): RedirectResponse
	{
		$user = auth()->user();

		$validated = $request->validate([
			'cart_id' => 'required|exists:carts,id',
			'address_id' => 'nullable|exists:addresses,id',
			'notes' => 'nullable|string',
			'delivery_method' => 'required|string|in:shipping,pickup',
			'payment_method' => 'required|string|in:mercadopago,paypal',
		]);

		if ($validated['delivery_method'] === 'shipping' && empty($validated['address_id'])) {
			return back()->withErrors([
				'address_id' => 'Debes seleccionar una dirección para el envío.',
			]);
		}

		$cart = Cart::where('id', $validated['cart_id'])
			->where('user_id', $user->id)
			->with('products')
			->firstOrFail();

		if ($cart->products->isEmpty()) {
			return back()->with('error', 'El carrito está vacío.');
		}

		$shippingRate = null;
		$shippingCost = 0.0;

		if ($validated['delivery_method'] === 'shipping') {
			$address = $user->addresses()->findOrFail($validated['address_id']);

			$localLocation = Setting::query()
				->whereIn('key', ['longitude', 'latitude',])
				->pluck('value', 'key');

			if (!isset($localLocation['latitude']) || !isset($localLocation['longitude'])) {
				return back()->with('error', 'No fue posible obtener la ubicación del local.');
			}

			$route = $this->routingService->distance(
				fromLatitude: (float) $localLocation['latitude'],
				fromLongitude: (float) $localLocation['longitude'],
				toLatitude: (float) $address->latitude,
				toLongitude: (float) $address->longitude,
			);

			if (!empty($route['error'])) {
				return back()->with('error', $route['message'] ?? 'No fue posible calcular la distancia de la ruta del envío.');
			}

			$shippingRate = $this->shippingService->calculateShippingCost($route['distance_km']);

			if (!$shippingRate['is_feasible']) {
				return back()->with('error', 'El envío no está disponible para la dirección seleccionada. ' . 'Podés seleccionar retiro en local.');
			}

			$shippingCost = (float) $shippingRate['rate']->cost;
		}

		$result = DB::transaction(function () use ($cart, $validated, $user, $shippingRate, $shippingCost) {
			$cartProducts = $cart->products;
			$productsIds = $cartProducts->pluck('id')->unique()->sort()->values();

			$lockedProducts = Product::whereIn('id', $productsIds)
				->orderBy('id')
				->lockForUpdate()
				->get()
				->keyBy('id');

			foreach ($cartProducts as $cartItem) {
				$product = $lockedProducts->get($cartItem->id);
				if (!$product) {
					throw new \Exception("Producto no encontrado: ID {$cartItem->id}");
				}

				$requestedQty = $cartItem->pivot->quantity;
				if ($product->stock < $requestedQty) {
					throw new \Exception(
						"Stock insuficiente para \"{$product->name}\". " .
							"Disponible: {$product->stock}, solicitado: {$requestedQty}"
					);
				}
			}

			$order = Order::create([
				'date' => now(),
				'total' => 0,
				'iva' => 0,
				'shipping_cost' => $shippingCost,
				'notes' => $validated['notes'],
				'order_state_id' => OrderState::where('slug', 'pending')->value('id'),
				'user_id' => $user->id,
				'address_id' => $validated['address_id'],
			]);

			$total = 0;

			foreach ($cartProducts as $productItem) {
				$offer = $productItem->getCurrentOffer();
				$templateOffer = $offer?->offerTemplate;
				$discount = $templateOffer ?
					$productItem->getDiscountTotal(
						$productItem->pivot->quantity,
						$templateOffer->buy_qty,
						$templateOffer->pay_qty,
						$templateOffer->offerType->slug
					)
					: 0;

				$order->products()->attach($productItem->id, [
					'quantity' => $productItem->pivot->quantity,
					'price' => $productItem->price,
					'discount' => $discount,
					'offer_id' => $offer?->id ?? '',
					'offer_template_name' => $templateOffer?->name ?? '',
					'offer_type_slug' => $templateOffer?->offerType->slug ?? '',
				]);

				$total += (($productItem->price * $productItem->pivot->quantity) - $discount);
			}

			$iva = $total * floatval(config('commerce.tax_rate', 21)) / 100;
			$total = $total + $iva + $shippingCost;
			$order->update(['total' => $total, 'iva' => round($iva, 2)]);

			foreach ($cartProducts as $cartItem) {
				$product = $lockedProducts->get($cartItem->id);
				$product->decrement('stock', $cartItem->pivot->quantity);
			}


			$trackingNumber = null;
			$deliveryDate = null;
			if ($validated['delivery_method'] === 'shipping') {
				$random = bin2hex(random_bytes(5));
				$date = date('YmdHis');
				$trackingNumber = "TRK-{$random}-{$date}";
				$deliveryDate = now()->addhours(random_int(1, 6));
			}

			$shippingStateSlug = $validated['delivery_method'] === 'pickup'
				? 'ready_for_pickup'
				: 'pending';

			Shipping::create([
				'order_id' => $order->id,
				'transport_user_id' => null,
				'shipping_states_id' => \App\Models\ShippingState::where('slug', $shippingStateSlug)->value('id'),
				'shipping_rate_id' => $shippingRate['is_feasible'] ? $shippingRate['rate']->id : null,
				'tracking_number' => $trackingNumber,
				'shipping_cost' => $shippingRate['is_feasible'] ? $shippingRate['rate']->cost : 0,
				'delivery_method' => $validated['delivery_method'],
				'estimated_delivery_date' => $deliveryDate,
				'delivered_at' => null,
				'notes' => null,
				'is_feasible' => $shippingRate['is_feasible'],
			]);

			$slug = $validated['payment_method'] === 'mercadopago' ? 'MERCADO_PAGO' : 'PAYPAL';
			$payment = Payment::create([
				'transaction_id' => 'pending_' . uniqid('', true),
				'paymentId' => 'pay_' . uniqid('', true),
				'provider_state' => 'pending',
				'checkout_url' => '#',
				'method' => $validated['payment_method'],
				'amount' => $order->total,
				'paid_at' => null,
				'order_id' => $order->id,
				'payment_state_id' => PaymentState::where('slug', 'pending')->value('id'),
				'payment_provider_id' => PaymentProvider::where('slug', $slug)->value('id'),
			]);

			return [
				'order' => $order,
				'payment' => $payment,
			];
		});

		$order = $result['order'];
		$payment = $result['payment'];

		return match ($validated['payment_method']) {
			'mercadopago' => redirect()->route(
				'checkout.process',
				[
					'order' => $order->id,
					'payment' => $payment->id,
				]
			),

			'paypal' => redirect()->route(
				'paypal.checkout',
				[
					'order' => $order->id,
					'payment' => $payment->id,
				]
			),
		};
	}

	public function updateStates(Request $request, Order $order): RedirectResponse
	{
		$validated = $request->validate([
			'states' => 'required|exists:order_states,id',
		]);

		$order->update([
			'order_state_id' => $validated['states']
		]);

		return redirect()->back();
	}

	public function myIndex(): View
	{
		$user = auth()->user();
		return view('pages.dashboard.order.index', [
			'orders' => $user->orders()->orderByDesc('date')->paginate(10),
			'orderStates' => OrderState::all(['slug', 'id']),
		]);
	}

	private function applyFilters(Builder $query, array $filters): Builder
	{
		if (isset($filters['users']) && is_array($filters['users'])) {
			$users = array_filter($filters['users'], fn($user) => !is_null($user) && $user !== '');
			if (!empty($users)) {
				$query->whereIn('user_id', $users);
			}
		}

		if (!empty($filters['date_from'])) {
			$query->where('date', '>=', $filters['date_from']);
		}

		if (!empty($filters['date_to'])) {
			$query->where('date', '<=', $filters['date_to']);
		}

		if (isset($filters['states']) && is_array($filters['states'])) {
			$states = array_filter($filters['states'], fn($state) => !is_null($state) && $state !== '');
			if (!empty($states)) {
				$query->whereIn('order_state_id', $states);
			}
		}

		if (!empty($filters['total_from'])) {
			$query->where('total', '>=', $filters['total_from']);
		}

		if (!empty($filters['total_to'])) {
			$query->where('total', '<=', $filters['total_to']);
		}

		return $query;
	}

	public function count(OrderExportRequest $request): JsonResponse
	{
		$filters = $request->validated();

		$query = Order::query();
		$this->applyFilters($query, $filters);

		return response()->json(['count' => $query->count()]);
	}

	public function export(OrderExportRequest $request)
	{
		$filters = $request->validated();
		$query = Order::query()
			->with(['user:id,name,surname', 'orderState:id,slug', 'products']);

		$this->applyFilters($query, $filters);

		if ($query->count() === 0) {
			return redirect()->back()->with('error', 'No se encontraron resultados.');
		}

		return Excel::download(new OrdersExport($query->orderBy('date', 'desc')->get()), 'orders.xlsx');
	}
}
