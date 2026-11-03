<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function ordersbyproduct(String $id): View
    {
        $product = Product::withTrashed()->findOrFail($id);
        $orders = $product->orders()->orderBy('date', 'desc')->paginate(10);
        $currentYear = now()->year;

        $aggregates = DB::table('order_product')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->where('order_product.product_id', $id)
            ->whereYear('orders.date', $currentYear)
            ->select(DB::raw('MONTH(orders.date) as month'), DB::raw('SUM(order_product.quantity) as total_quantity'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $quantity = [];

        foreach ($aggregates as $item) {
            $months[] = Carbon::create()->month($item->month)->translatedFormat('F');
            $quantity[] = (int) $item->total_quantity;
        }

        return view('pages.dashboard.product.orders', compact('product', 'orders', 'months', 'quantity'));
    }
}
