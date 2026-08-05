<?php

namespace App\Http\Controllers;

use App\Models\OrderState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderStateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:order_states,slug',
            'name' => 'required|string|max:255'
        ]);
        OrderState::create($validated);

        return redirect()->route('states-types.index');
    }

    public function update(Request $request, OrderState $orderState): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('order_states', 'slug')->ignore($orderState->id),
            ],
            'name' => 'required|string|max:255',
        ]);
        $orderState->update($validated);

        return redirect()->route('states-types.index');
    }

    public function destroy(OrderState $orderState): RedirectResponse
    {
        $type = 'warning';
        $message = 'No se ha podido eliminar el estado: ' . $orderState->name . ' porque tiene ordenes asociadas';

        if (!$orderState->orders()->exists()) {
            $type = 'success';
            $message = 'El estado ' . $orderState->name . ' se ha eliminado correctamente';
            $orderState->delete();
        }

        return redirect()
            ->route('states-types.index')
            ->with($type, $message);
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(OrderState::findOrFail($id, ['id', 'slug', 'name']));
    }
}
