<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use App\Http\Requests\PaymentRequest;
use App\Models\OrderState;
use App\Models\Payment;
use App\Models\PaymentState;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
	public function index(): View
	{
		return view('pages.dashboard.payment.index', [
			'payments' => Payment::orderByDesc('paid_at')->paginate(10),
			'statuses' => PaymentState::all(['id', 'code']),
		]);
	}

	public function update(PaymentRequest $request, Payment $payment): RedirectResponse
	{
		$payment->update($request->validated());
		return back();
	}

	public function fetch(string $id): JsonResponse
	{
		$dataJson = Payment::select(['id', 'paid_at', 'amount', 'payment_id', 'payment_state_id'])->findOrFail($id);

		$dataJson->amount = '$' . number_format($dataJson->amount, 2, ',', '.');
		return response()->json($dataJson);
	}

	public function updateStates(Request $request, Payment $payment): RedirectResponse
	{
		$validated = $request->validate([
			'states' => 'required|exists:payment_states,id',
		]);

		$payment->update([
			'payment_state_id' => $validated['states']
		]);

		return redirect()->route('payments.index');
	}

	public function myIndex(): View
	{
		$user = auth()->user();
		return view('pages.dashboard.payment.index', [
			'payments' => $user->paymentsBuilder()->paginate(10),
			'statuses' => PaymentState::all(['id', 'code']),
		]);
	}

	public function export()
	{
		return Excel::download(new PaymentsExport, 'payments.xlsx');
	}

	// Rutas para redirecciones de Mercado Pago
	public function success(Request $request): RedirectResponse
	{
		$paymentId = $request->query('payment_id') ?? $request->query('collection_id');
		// Busca por el external_reference
		if ($paymentId) {
			$payment = Payment::where('provider_transaction_id', $paymentId)
				->orWhere('id', $paymentId)
				->first();

			if ($payment && $payment->provider_state !== 'approved') {
				$payment->update([
					'provider_transaction_id' => $paymentId,
					'provider_state' => 'approved',
					'paid_at' => now(),
				]);

				if ($payment->order) {
					$payment->order->update(['order_state_id' => OrderState::where('code', 'PAGADO')->value('id')]);
				}
			}
		}

		return redirect('/')->with('success', 'El pago fue aprobado.');
	}

	public function failure(Request $request): RedirectResponse
	{
		$paymentId = $request->query('payment_id') ?? $request->query('collection_id');

		if ($paymentId) {
			$pago = Payment::where('provider_transaction_id', $paymentId)
				->orWhere('id', $paymentId)
				->first();
			if ($pago && $pago->provider_state !== 'rejected') {
				$pago->update(['provider_state' => 'rejected']);
			}
		}

		return redirect('/')->with('error', 'El pago fue rechazado.');
	}

	public function pending(Request $request): RedirectResponse
	{
		$paymentId = $request->query('payment_id') ?? $request->query('collection_id');

		if ($paymentId) {
			$pago = Payment::where('provider_transaction_id', $paymentId)
				->orWhere('id', $paymentId)
				->first();
			if ($pago && $pago->provider_state !== 'pending') {
				$pago->update(['provider_state' => 'pending']);
			}
		}

		return redirect('/')->with('warning', 'El pago está pendiente de acreditación.');
	}
}
