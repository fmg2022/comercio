<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Http\Requests\OrderExportRequest;
use App\Models\Order;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{

	public function show(String $id): View
	{
		$order = Order::findOrFail($id);
		return view('pages.dashboard.order.show', compact('order'));
	}

	public function export(OrderExportRequest $request)
	{
		$filters = $request->validated();

		return Excel::download(new OrdersExport($filters), 'orders.xlsx');
	}
}
