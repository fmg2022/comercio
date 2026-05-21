<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\View\View;

class DashboardController extends Controller
{
	public function index()
	{
		$orders = Order::select('id', 'date', 'total', 'user_id', 'order_state_id')
			->dateRange(now()->subWeek())
			->orderBy('date', 'desc')
			->limit(5)->get();

		$statistics = [
			[
				'icon' => 'order',
				'color' => 'cyan',
				'name' => 'Ordenes',
				'total' => Order::count()
			],
			[
				'icon' => 'product',
				'color' => 'indigo',
				'name' => 'Productos',
				'total' => Product::count()
			],
			[
				'icon' => 'users',
				'color' => 'fuchsia',
				'name' => 'Usuarios',
				'total' => User::count()
			],
			[
				'icon' => 'category',
				'color' => 'orange',
				'name' => 'Categorias',
				'total' => \App\Models\Category::count()
			]
		];

		$bestSellers = DB::table('products')
			->join('order_product', 'products.id', '=', 'order_product.product_id')
			->join('orders', 'order_product.order_id', '=', 'orders.id')
			->join('order_states', function (JoinClause $join) {
				$join->on('orders.order_state_id', '=', 'order_states.id')
					->where('order_states.code', '=', 'COMPLETO');
			})
			->select(
				[
					'products.id',
					'products.name',
					'products.image',
					'products.price',
					DB::raw('SUM(order_product.quantity) as total_sold')
				]
			)
			->groupBy('products.id', 'products.name', 'products.image', 'products.price')
			->orderByDesc('total_sold')
			->limit(5)
			->get();

		$totalYear =  Payment::OnlyAprobed()
			->dateRange(now()->startOfYear())
			->sum('amount');
		$totalMonth = Payment::OnlyAprobed()
			->dateRange(now()->subMonth())
			->sum('amount');
		$totalWeek = Payment::OnlyAprobed()
			->dateRange(now()->subWeek())
			->sum('amount');
		$total2Week = Payment::OnlyAprobed()
			->dateRange(now()->subWeeks(2), now()->subWeek())
			->sum('amount');
		$totalSellers = [
			'lastYear' => Number::currency($totalYear, 'ARS', 'es_AR'),
			'lastMonth' => Number::currency($totalMonth, 'ARS', 'es_AR'),
			'lastWeek' => Number::currency($totalWeek, 'ARS', 'es_AR'),
			'diffWeeks' => ["value" => $total2Week > 0 ? number_format((($totalWeek - $total2Week) / $total2Week) * 100, 2) : '100.00', "sign" => $totalWeek - $total2Week >= 0 ? '+' : '-'],
		];

		$totalWeek = Order::notCanceled()
			->dateRange(now()->subWeek())
			->count();
		$total2Week = Order::notCanceled()
			->dateRange(now()->subWeeks(2), now()->subWeek())
			->count();
		$orderStatistics = [
			'thisMonth' => Order::whereMonth('date', now()->month)->count(),
			'month' => now()->locale('es')->monthName,
			'diffWeeks' => ["value" => $total2Week > 0 ? number_format((($totalWeek - $total2Week) / $total2Week) * 100, 2) : '100.00', "sign" => $totalWeek - $total2Week >= 0 ? '+' : '-']
		];

		$user = Auth::user();

		return view('pages.dashboard', compact('user', 'orders', 'statistics', 'bestSellers', 'totalSellers', 'orderStatistics'));
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
			'orderStates' => \App\Models\OrderState::get(['id', 'code', 'description']),
			'offerStates' => \App\Models\OfferState::get(['id', 'code', 'description']),
			'offerTypes' => \App\Models\OfferType::get(['id', 'code', 'description']),
			'paymentStates' => \App\Models\PaymentState::get(['id', 'code', 'description']),
		]);
	}
}
