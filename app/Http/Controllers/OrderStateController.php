<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateTypeRequest;
use App\Models\OrderState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OrderStateController extends Controller
{
    public function store(StateTypeRequest $request): RedirectResponse
    {
        OrderState::create($request->validated());

        return redirect()->route('states-types.index');
    }

    public function update(StateTypeRequest $request, OrderState $orderState): RedirectResponse
    {
        $orderState->update($request->validated());

        return redirect()->route('states-types.index');
    }

    public function destroy(OrderState $orderState): RedirectResponse
    {
        $type = 'warning';
        $message = 'No se ha podido eliminar el estado: ' . $orderState->code . ' porque tiene ordenes asociadas';

        if (!$orderState->orders()->exists()) {
            $type = 'success';
            $message = 'El estado ' . $orderState->code . ' se ha eliminado correctamente';
            $orderState->delete();
        }

        return redirect()
            ->route('states-types.index')
            ->with($type, $message);
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(OrderState::findOrFail($id, ['id', 'code', 'description']));
    }
}
