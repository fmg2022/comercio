<?php

namespace App\Http\Controllers;

use App\Models\OfferState;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Validation\Rule;

class OfferStateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:offer_states,slug',
            'name' => 'required|string|max:255'
        ]);
        OfferState::create($validated);

        return redirect()->route('states-types.index');
    }

    public function update(Request $request, OfferState $offerState): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offer_states', 'slug')->ignore($offerState->id),
            ],
            'name' => 'required|string|max:255',
        ]);
        $offerState->update($validated);

        return redirect()->route('states-types.index');
    }

    public function destroy(OfferState $offerState): RedirectResponse
    {
        $type = 'warning';
        $message = 'No se ha podido eliminar el estado: ' . $offerState->name . ' porque tiene offertas asociadas';

        if (!$offerState->offers()->exists()) {
            $type = 'success';
            $message = 'El estado ' . $offerState->name . ' se ha eliminado correctamente';
            $offerState->delete();
        }

        return redirect()
            ->route('states-types.index')
            ->with($type, $message);
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(OfferState::findOrFail($id, ['id', 'slug', 'name']));
    }
}
