<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Http\Requests\OrderExportRequest;
use App\Models\{Cart, Order, OrderState, Payment, PaymentProvider, PaymentState, Product, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\{Request, JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
	public function index(): View
	{
		return view('pages.dashboard.order.index', [
			'orders' => Order::orderByDesc('date')->paginate(10),
			'orderStates' => OrderState::all(['code', 'id']),
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
			'orderStates' => OrderState::all(['code', 'id']),
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
		if (!$user->address) {
			return back()->with('error', 'Debes crear una dirección antes de poder realizar el pago.');
		}

		$validated = $request->validate([
			'cart_id' => 'required|exists:carts,id',
			'notes' => 'nullable|string',
			'payment_method' => 'required|string|in:mercadopago,paypal',
		]);

		$cart = Cart::where('id', $validated['cart_id'])
			->where('user_id', auth()->id())
			->firstOrFail();

		if ($cart->products->isEmpty()) {
			return back()->with('error', 'El carrito está vacío.');
		}

		$order = DB::transaction(function () use ($cart, $validated, $user) {
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
				'notes' => $validated['notes'],
				'order_state_id' => OrderState::where('code', 'PENDIENTE')->value('id'),
				'user_id' => $user->id,
				'address' => $user->address,
			]);

			$total = 0;

			foreach ($cart->products as $productItem) {
				$templateOffer = $productItem->getCurrentOffer();
				$discount = $templateOffer ?
					$productItem->getDiscountTotal(
						$productItem->pivot->quantity,
						$templateOffer->buy_qty,
						$templateOffer->pay_qty,
						$templateOffer->offerType->code
					)
					: 0;

				$order->products()->attach($productItem->id, [
					'quantity' => $productItem->pivot->quantity,
					'price' => $productItem->price,
					'discount' => $discount,
					'offer_template_id' => $templateOffer?->id ?? '',
					'offer_type_code' => $templateOffer?->offerType->code ?? '',
				]);

				$total += (($productItem->price * $productItem->pivot->quantity) - $productItem->pivot->discount);
			}

			$iva = $total * floatval(config('commerce.tax_rate', 21)) / 100;
			$order->update(['total' => $total, 'iva' => round($iva, 2)]);

			foreach ($cartProducts as $cartItem) {
				$product = $lockedProducts->get($cartItem->id);
				$product->decrement('stock', $cartItem->pivot->quantity);
			}

			return $order;
		});

		$code = $validated['payment_method'] === 'mercadopago' ? 'MERCADO_PAGO' : 'PAYPAL';
		$payment = Payment::create([
			'transaction_id' => 'pending_' . uniqid('', true),
			'paymentId' => 'pay_' . uniqid('', true),
			'provider_state' => 'pending',
			'checkout_url' => '#',
			'method' => $validated['payment_method'],
			'amount' => $order->total + $order->iva,
			'paid_at' => null,
			'order_id' => $order->id,
			'payment_state_id' => PaymentState::where('code', 'EN_PROCESO')->value('id'),
			'payment_provider_id' => PaymentProvider::where('code', $code)->value('id'),
		]);

		if ($validated['payment_method'] === 'mercadopago')
			return redirect()->route('checkout.process', ['order' => $order->id, 'payment' => $payment->id]);
		else
			return redirect()->route('paypal.checkout', ['order' => $order->id, 'payment' => $payment->id]);
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
			'orderStates' => OrderState::all(['code', 'id']),
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
			->with(['user:id,name,surname', 'orderState:id,code', 'products']);

		$this->applyFilters($query, $filters);

		if ($query->count() === 0) {
			return redirect()->back()->with('error', 'No se encontraron resultados.');
		}

		return Excel::download(new OrdersExport($query->orderBy('date', 'desc')->get()), 'orders.xlsx');
	}
}
