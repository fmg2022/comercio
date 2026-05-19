<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderState;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.order.index', [
            'orders' => Order::orderByDesc('date')->paginate(10),
            'orderStates' => OrderState::all(['code', 'id']),
        ]);
    }

    public function show(String $id): View
    {
        $order = Order::findOrFail($id);
        return view('pages.dashboard.order.show', compact('order'));
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

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
}
