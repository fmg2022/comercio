<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateTypeRequest;
use App\Models\PaymentState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PaymentStateController extends Controller
{
    public function store(StateTypeRequest $request): RedirectResponse
    {
        PaymentState::create($request->validated());

        return redirect()->route('states-types.index');
    }

    public function update(StateTypeRequest $request, PaymentState $paymentState): RedirectResponse
    {
        $paymentState->update($request->validated());

        return redirect()->route('states-types.index');
    }

    public function destroy(PaymentState $paymentState): RedirectResponse
    {
        $type = 'warning';
        $message = 'No se ha podido eliminar el estado: ' . $paymentState->code . ' porque tiene pagos asociados';

        if (!$paymentState->payments()->exists()) {
            $type = 'success';
            $message = 'El estado ' . $paymentState->code . ' se ha eliminado correctamente';
            $paymentState->delete();
        }

        return redirect()
            ->route('states-types.index')
            ->with($type, $message);
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(PaymentState::findOrFail($id, ['id', 'code', 'description']));
    }
}
