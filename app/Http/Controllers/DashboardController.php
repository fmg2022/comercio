<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderProduct, OrderState, Payment};
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
	public function redirectToDashboard(): RedirectResponse
	{
		if (auth()->user()->hasRole(['Admin', 'Super Admin'])) {
			return redirect()->route('admin.dashboard');
		}
		return redirect()->route('client.dashboard');
	}

	public function index()
	{
		$user = auth()->user();

		$recentOrders = $user->orders()->orderByDesc('date')->limit(5)->get();
		$favoriteProducts = OrderProduct::join('orders', 'order_product.order_id', '=', 'orders.id')
			->join('products', 'order_product.product_id', '=', 'products.id')
			->where('orders.user_id', $user->id)
			->whereIn('orders.order_state_id', $this->completedStateIds())
			->select('products.id', 'products.name', 'products.image', 'products.price', 'products.stock', DB::raw('SUM(order_product.quantity) as total_qty'), DB::raw('products.stock > products.min_stock as in_stock'))
			->groupBy('products.id', 'products.name', 'products.image', 'products.price', 'products.stock', 'products.min_stock')
			->orderByDesc('total_qty')
			->limit(5)
			->get();

		$startDate = Carbon::now()->subMonths(6)->startOfMonth();
		$monthlySpending = $user->orders()
			->whereIn('order_state_id', $this->completedStateIds())
			->where('date', '>=', $startDate)
			->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('SUM(total) as total'))
			->groupBy('month')
			->orderBy('month')
			->get();

		$currentCart = $user->cart;

		$totalSaved = $user->orders
			->whereIn('order_state_id', $this->completedStateIds())
			->sum('discount');

		return view('pages.dashboard.dashboard', compact(
			'recentOrders',
			'favoriteProducts',
			'monthlySpending',
			'currentCart',
			'totalSaved'
		));
	}

	public function cantSellers(): JsonResponse
	{
		$sellers = Payment::selectRaw('COUNT(*) as total, DATE(paid_at) as paid_date')
			->OnlyAprobed()
			->whereMonth('paid_at', now()->month)
			->groupBy('paid_date')
			->orderBy('paid_date', 'asc')
			->get();

		[$data, $labels] = $sellers->reduce(function ($carry, $item) {
			$carry[0][] = $item->total;
			$carry[1][] = $item->paid_date;
			return $carry;
		}, [[], []]);

		return response()->json(['values' => $data, 'labels' => $labels]);
	}

	public function cantOrders(): JsonResponse
	{
		$orders = Order::selectRaw('COUNT(*) as total, date')
			->whereMonth('date', now()->month)
			->groupBy('date')->get();

		[$data, $labels] = $orders->reduce(function ($carry, $item) {
			$carry[0][] = $item->total;
			$carry[1][] = $item->date;
			return $carry;
		}, [[], []]);

		return response()->json(['values' => $data, 'labels' => $labels]);
	}

	public function indexStatesTypes(): View
	{
		return view('pages.dashboard.statetype.index', [
			'orderStates' => \App\Models\OrderState::get(['id', 'slug', 'name']),
			'offerStates' => \App\Models\OfferState::get(['id', 'slug', 'name']),
			'offerTypes' => \App\Models\OfferType::get(['id', 'slug', 'name']),
			'paymentStates' => \App\Models\PaymentState::get(['id', 'slug', 'name']),
		]);
	}

	private function completedStateIds()
	{
		return OrderState::whereIn('slug', ['completed', 'paid'])->pluck('id')->toArray();
	}
}
