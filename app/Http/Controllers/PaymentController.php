<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\PaymentState;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
	public function index(): View
	{
		return view('pages.dashboard.payment.index', [
			'payments' => Payment::orderByDesc('paid_at')->paginate(10),
			'paymentsDeleted' => Payment::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
			'statuses' => PaymentState::all(['id', 'code']),
		]);
	}

	public function update(PaymentRequest $request, Payment $payment): RedirectResponse
	{
		$payment->update($request->validated());
		return redirect()->back();
	}

	public function destroy(Payment $payment): RedirectResponse
	{
		$payment->delete();
		return redirect()->route('payments.index');
	}

	public function restore(string $id): RedirectResponse
	{
		$payment = Payment::onlyTrashed()->findOrFail($id);
		$payment->restore();
		return redirect()->route('payments.index');
	}

	public function fetch(string $id): JsonResponse
	{
		$dataJson = Payment::select(['id', 'paid_at', 'amount', 'payment_id', 'payment_state_id'])->findOrFail($id);

		$dataJson->amount = '$' . number_format($dataJson->amount, 2, ',', '.');
		return response()->json($dataJson);
	}
}
