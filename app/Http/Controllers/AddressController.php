<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AddressController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.address.index', [
            'addresses' => Address::orderBy('is_default', 'desc')->orderBy('id', 'desc')->paginate(10),
            'addressesDeleted' => Address::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
            'users' => User::where('active', true)->selectRaw("id, CONCAT(surname, ', ', name) AS full_name")->get(),
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

        return redirect()->back();
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
        $address = Address::withTrashed()->findOrFail($id, ['name', 'street', 'city', 'province', 'postal_code', 'is_default', 'user_id']);

        if (!$address) {
            return response()->json(['error' => 'Dirección no encontrada'], 404);
        }

        return response()->json($address);
    }

    public function myIndex(): View
    {
        $user = auth()->user();
        return view('pages.dashboard.address.index', [
            'addresses' => $user->addresses()->orderBy('is_default', 'desc')->orderBy('id', 'desc')->paginate(10),
            'addressesDeleted' => $user->addresses()->onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
        ]);
    }
}
