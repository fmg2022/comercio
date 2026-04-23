<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateTypeRequest;
use App\Models\ShipmentState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShipmentStateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:shipment_states,code',
            'description' => 'required|string|max:255'
        ]);
        ShipmentState::create($validated);

        return redirect()->route('states-types.index');
    }

    public function update(Request $request, ShipmentState $shipmentState): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shipment_states', 'code')->ignore($shipmentState->id),
            ],
            'description' => 'required|string|max:255',
        ]);
        $shipmentState->update($validated);

        return redirect()->route('states-types.index');
    }

    public function destroy(ShipmentState $shipmentState): RedirectResponse
    {
        $type = 'warning';
        $message = 'No se ha podido eliminar el estado: ' . $shipmentState->code . ' porque tiene envios asociadas';

        if (!$shipmentState->shipments()->exists()) {
            $type = 'success';
            $message = 'El estado ' . $shipmentState->code . ' se ha eliminado correctamente';
            $shipmentState->delete();
        }

        return redirect()
            ->route('states-types.index')
            ->with($type, $message);
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(ShipmentState::findOrFail($id, ['id', 'code', 'description']));
    }
}
