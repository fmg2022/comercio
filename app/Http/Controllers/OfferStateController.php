<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateTypeRequest;
use App\Models\OfferState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OfferStateController extends Controller
{
    public function store(StateTypeRequest $request): RedirectResponse
    {
        OfferState::create($request->validated());

        return redirect()->route('states-types.index');
    }

    public function update(StateTypeRequest $request, OfferState $offerState): RedirectResponse
    {
        $offerState->update($request->validated());

        return redirect()->route('states-types.index');
    }

    public function destroy(OfferState $offerState): RedirectResponse
    {
        $type = 'warning';
        $message = 'No se ha podido eliminar el estado: ' . $offerState->code . ' porque tiene offertas asociadas';

        if (!$offerState->offers()->exists()) {
            $type = 'success';
            $message = 'El estado ' . $offerState->code . ' se ha eliminado correctamente';
            $offerState->delete();
        }

        return redirect()
            ->route('states-types.index')
            ->with($type, $message);
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(OfferState::findOrFail($id, ['id', 'code', 'description']));
    }
}
