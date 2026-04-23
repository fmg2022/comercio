<?php

namespace App\Http\Controllers;

use App\Models\OfferState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfferStateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:offer_states,code',
            'description' => 'required|string|max:255'
        ]);
        OfferState::create($validated);

        return redirect()->route('states-types.index');
    }

    public function update(Request $request, OfferState $offerState): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offer_states', 'code')->ignore($offerState->id),
            ],
            'description' => 'required|string|max:255',
        ]);
        $offerState->update($validated);

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
