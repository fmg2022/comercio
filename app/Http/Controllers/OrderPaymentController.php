<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderPaymentRequest;
use App\Models\OrderPayment;
use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderPaymentController extends Controller
{
	public function index(): View
	{
		return view('pages.dashboard.payment.index', [
			'payments' => OrderPayment::paginate(10),
			'paymentsDeleted' => OrderPayment::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
			'statuses' => PaymentStatus::all(['id', 'name']),
			'methods' => Payment::all(['id', 'name']),
		]);
	}

	public function update(OrderPaymentRequest $request, OrderPayment $payment): RedirectResponse
	{
		$payment->update($request->validated());
		return redirect()->back();
	}

	public function destroy(OrderPayment $payment): RedirectResponse
	{
		$payment->delete();
		return redirect()->route('payments.index');
	}

	public function restore(string $id): RedirectResponse
	{
		$payment = OrderPayment::onlyTrashed()->findOrFail($id);
		$payment->restore();
		return redirect()->route('payments.index');
	}

	public function fetch(string $id): JsonResponse
	{
		$dataJson = OrderPayment::select(['id', 'date', 'amount', 'payment_id', 'payment_status_id'])->findOrFail($id);

		$dataJson->amount = '$' . number_format($dataJson->amount, 2, ',', '.');
		return response()->json($dataJson);
	}
}
