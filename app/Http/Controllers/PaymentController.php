<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use App\Http\Requests\PaymentExportRequest;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\PaymentState;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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
			'statuses' => PaymentState::all(['id', 'slug', 'name']),
			'users' => User::whereHas('orders.payment')->select('id', 'name', 'surname')->get(),
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

		$v = $payment->update([
			'payment_state_id' => $validated['states']
		]);

		return redirect()->back();
	}

	public function myIndex(): View
	{
		$user = auth()->user();
		return view('pages.dashboard.payment.index', [
			'payments' => $user->payments()->orderByDesc('paid_at')->paginate(10),
			'statuses' => PaymentState::all(['id', 'slug', 'name']),
		]);
	}

	private function applyFilters(Builder $query, array $filters): Builder
	{
		if (isset($filters['users']) && is_array($filters['users'])) {
			$users = array_filter($filters['users'], fn($user) => !is_null($user) && $user !== '');
			if (!empty($users)) {
				$query->whereIn('user_id', $users);
			}
		}

		if (!empty($filters['date_from'])) {
			$query->where('paid_at', '>=', $filters['date_from']);
		}

		if (!empty($filters['date_to'])) {
			$query->where('paid_at', '<=', $filters['date_to']);
		}

		if (isset($filters['states']) && is_array($filters['states'])) {
			$states = array_filter($filters['states'], fn($state) => !is_null($state) && $state !== '');
			if (!empty($states)) {
				$query->whereIn('payment_state_id', $states);
			}
		}

		if (!empty($filters['total_from'])) {
			$query->where('amount', '>=', $filters['total_from']);
		}

		if (!empty($filters['total_to'])) {
			$query->where('amount', '<=', $filters['total_to']);
		}

		return $query;
	}

	public function count(PaymentExportRequest $request): JsonResponse
	{
		$filters = $request->validated();

		$query = Payment::query();
		$this->applyFilters($query, $filters);

		return response()->json(['count' => $query->count()]);
	}

	public function export(PaymentExportRequest $request)
	{
		$filters = $request->validated();
		$query = Payment::query()
			->with(['order:id,user_id,address,date', 'order.user:id,name,surname', 'paymentState:id,name']);

		$this->applyFilters($query, $filters);

		if ($query->count() === 0) {
			return redirect()->back()->with('error', 'No se encontraron resultados.');
		}

		return Excel::download(new PaymentsExport($query->orderBy('paid_at', 'desc')->get()), 'payments.xlsx');
	}
}
