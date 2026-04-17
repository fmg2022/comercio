<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateTypeRequest;
use App\Models\ShipmentState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ShipmentStateController extends Controller
{
    public function store(StateTypeRequest $request): RedirectResponse
    {
        ShipmentState::create($request->validated());

        return redirect()->route('states-types.index');
    }

    public function update(StateTypeRequest $request, ShipmentState $shipmentState): RedirectResponse
    {
        $shipmentState->update($request->validated());

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
