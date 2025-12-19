<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
	public function index()
	{
		$orders = Order::select('id', 'date', 'total', 'user_id', 'order_status_id')
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
				'total' => Category::count()
			]
		];

		$bestSellers = Product::select(['id', 'name', 'image', 'price'])
			->withCount('orders')->orderBy('orders_count', 'desc')
			->limit(5)->get();

		$totalYear =  OrderPayment::onlyCompleted()
			->dateRange(now()->startOfYear())
			->sum('amount');
		$totalMonth = OrderPayment::onlyCompleted()
			->dateRange(now()->subMonth())
			->sum('amount');
		$totalWeek = OrderPayment::onlyCompleted()
			->dateRange(now()->subWeek())
			->sum('amount');
		$total2Week = OrderPayment::onlyCompleted()
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
		$sellers = OrderPayment::selectRaw('COUNT(*) as total, date')
			->onlyCompleted()
			->whereMonth('date', now()->month)
			->groupBy('date')
			->orderBy('date', 'asc')
			->get();

		[$data, $labels] = $sellers->reduce(function ($carry, $item) {
			$carry[0][] = $item->total;
			$carry[1][] = $item->date;
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
}
