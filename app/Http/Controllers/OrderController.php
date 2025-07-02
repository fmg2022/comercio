<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
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

    public function show(String $id): View
    {
        $order = Order::withTrashed()->findOrFail($id);
        return view('pages.dashboard.order.show', compact('order'));
    }

    public function destroy(String $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->delete();
        $order->deleted_at = now();
        $order->save();
        return redirect()->route('orders.index');
    }

    public function restore(String $id): RedirectResponse
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('orders.index');
    }

    public function editLine(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
        ]);

        $order->products()->updateExistingPivot($validated['product_id'], [
            'quantity' => $validated['quantity']
        ]);
        return redirect()->route('orders.show', $order->id);
    }
}
