<?php

namespace App\Http\Controllers;

use App\Models\OfferType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfferTypeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:offer_type,code',
            'description' => 'required|string|max:255'
        ]);
        OfferType::create($validated);

        return redirect()->route('states-types.index');
    }

    public function update(Request $request, OfferType $offerType): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offer_type', 'code')->ignore($offerType->id),
            ],
            'description' => 'required|string|max:255',
        ]);
        $offerType->update($validated);

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
