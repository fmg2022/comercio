<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.address.index', [
            'addresses' => Address::paginate(10),
            'addressesDeleted' => Address::onlyTrashed()->paginate(10, pageName: 'pageDeleted')
        ]);
    }

    public function edit(Address $address): View
    {
        return view('pages.dashboard.address.edit', [
            'address' => $address
        ]);
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        $address->update($request->validated());

        return redirect()->route('addresses.index');
    }

    public function create(): View
    {
        return view('pages.dashboard.address.create');
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        Address::create($request->validated());

        return redirect()->route('addresses.index');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $address->delete();
        return redirect()->back();
    }

    public function restore(String $id): RedirectResponse
    {
        $order = Address::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('addresses.index');
    }

    public function fetch($id): JsonResponse
    {
        $address = Address::withTrashed()->findOrFail($id, ['street', 'city', 'province', 'is_default']);

        if (!$address) {
            return response()->json(['error' => 'Dirección no encontrada'], 404);
        }

        return response()->json($address);
    }
}
