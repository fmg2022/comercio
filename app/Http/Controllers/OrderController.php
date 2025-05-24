<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.order.index', [
            'orders' => Order::paginate(10),
            'ordersDeleted' => Order::onlyTrashed()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('pages.dashboard.order.create');
    }

    public function store(OrderRequest $request)
    {
        // $order = Order::create($request->all());
        // return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        return 1;
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('pages.dashboard.order.edit', compact('order'));
    }

    public function update(OrderRequest $request, $id)
    {
        // $order = Order::findOrFail($id);
        // $order->update($request->all());
        // return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        return 1;
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('pages.dashboard.order.show', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('orders.index')->with('success', 'Order restored successfully.');
    }
}
