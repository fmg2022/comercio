<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Http\Requests\OrderExportRequest;
use App\Mail\InvoiceMail;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderState;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Joelwmale\Cart\Facades\CartFacade;
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

	public function show(String $id): View
	{
		$order = Order::findOrFail($id);
		return view('pages.dashboard.order.show', compact('order'));
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'cart_id' => 'required|exists:carts,id',
			'notes' => 'nullable|string',
			'payment_method' => 'required|string|in:mercadopago,store,cash',
		]);

		$cart = Cart::find($validated['cart_id']);
		$products = $cart->products()->get();
		CartFacade::setSessionKey('cart_' . auth()->user()->id);

		$order = DB::transaction(function () use ($products, $validated) {
			$order = Order::create([
				'date' => now(),
				'total' => 0,
				'iva' => 0,
				'notes' => $validated['notes'],
				'order_state_id' => OrderState::where('code', 'CREADO')->value('id'),
				'user_id' => auth()->user()->id,
				'address_id' => auth()->user()->getCurrentAddress()->id,
			]);

			foreach ($products as $product) {
				$offerTemplate = $product->getCurrentOffer();
				$order->products()->attach($product->id, [
					'quantity' => $product->pivot->quantity,
					'price' => $product->price,
					'discount' => $offerTemplate ? $product->getDiscountTotal($product->pivot->quantity, $offerTemplate->buy_qty, $offerTemplate->pay_qty, $offerTemplate->offerType->code) : 0,
					'offer_template_id' => $offerTemplate ? $offerTemplate->id : '',
					'offer_type_code' => $offerTemplate ? $offerTemplate->offerType->code : '',
				]);
			}

			$iva = (float) config('commerce.tax_rate');

			$order->update([
				'total' => $order->products->sum(fn($product) => $product->pivot->getSubtotal),
				'iva' => $order->total * ($iva / 100),
			]);
			return $order;
		});

		CartFacade::clear();
		$cart->products()->detach();

		// Tiene que ir a Payment Model (store) y una ruta que lo llame (create) desde un controller
		// Solo para comprobar la creación de la orden, págos y envíos (TABLAS)
		DB::transaction(function () use ($order, $validated) {
			$payment = Payment::factory()->create([
				'checkout_url' => null,
				'method' => $validated['payment_method'],
				'amount' => $order->total,
				'paid_at' => null,
				'order_id' => $order->id,
				'payment_state_id' => DB::table('payment_states')->where('code', 'EN_PROCESO')->value('id'),
				'payment_provider_id' => DB::table('payment_providers')->where('code', 'MERCADO_PAGO')->value('id'),
			]);
			// Tiene que ser redirecionado a una página de pagos (Paypal, MercadoPago,...), de ahí que accedan a la ruta payments y cree uno nuevo.

			$order->update([
				'payment_id' => $payment->id,
			]);

			// Cuando el pago ha sido confirmado (APROBADO) se procede a crear el envío
			$payment->update([
				'payment_state_id' => DB::table('payment_states')->where('code', 'APROBADO')->value('id'),
				'checkout_url' => 'https://www.mercadopago.com.ar/checkout/payments/result/?payment_id=123456789',
				'paid_at' => now(),
			]);
		});
		// $order->user->email
		Mail::to('maximo4735@gmail.com')->send(new InvoiceMail($order));

		return redirect()->route('home')->with('success', 'La orden ha sido creada exitosamente');
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
			->with(['user:id,name,surname', 'address:id,street,city', 'orderState:id,code', 'products']);

		$this->applyFilters($query, $filters);

		if ($query->count() === 0) {
			return redirect()->back()->with('error', 'No se encontraron resultados.');
		}

		return Excel::download(new OrdersExport($query->orderBy('date', 'desc')->get()), 'orders.xlsx');
	}
}
