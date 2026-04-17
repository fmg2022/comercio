<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateTypeRequest;
use App\Models\OfferType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OfferTypeController extends Controller
{
    public function store(StateTypeRequest $request): RedirectResponse
    {
        OfferType::create($request->validated());

        return redirect()->route('states-types.index');
    }

    public function update(StateTypeRequest $request, OfferType $offerType): RedirectResponse
    {
        $offerType->update($request->validated());

        return redirect()->route('states-types.index');
    }

    public function destroy(OfferType $offerType): RedirectResponse
    {
        $type = 'warning';
        $message = 'No se ha podido eliminar el tipo: ' . $offerType->code . ' porque tiene ofertas asociadas';

        if (!$offerType->offerTemplates()->exists()) {
            $type = 'success';
            $message = 'El tipo ' . $offerType->code . ' se ha eliminado correctamente';
            $offerType->delete();
        }

        return redirect()
            ->route('states-types.index')
            ->with($type, $message);
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(OfferType::findOrFail($id, ['id', 'code', 'description']));
    }
}
