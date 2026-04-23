<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateTypeRequest;
use App\Models\PaymentState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentStateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:payment_states,code',
            'description' => 'required|string|max:255'
        ]);
        PaymentState::create($validated);

        return redirect()->route('states-types.index');
    }

    public function update(Request $request, PaymentState $paymentState): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_states', 'code')->ignore($paymentState->id),
            ],
            'description' => 'required|string|max:255',
        ]);
        $paymentState->update($validated);

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
