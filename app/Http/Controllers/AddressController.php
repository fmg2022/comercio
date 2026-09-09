<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Services\GeocodingService;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressController extends Controller
{

    public function __construct(public GeocodingService $geocodingService) {}

    public function index(): View
    {
        return view('pages.dashboard.address.index', [
            'addresses' => Address::orderBy('is_default', 'desc')->orderBy('id', 'desc')->paginate(10),
            'addressesDeleted' => Address::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
            'users' => \App\Models\User::get(['id', 'name', 'surname']),
        ]);
    }

    public function store(AddressRequest $request)
    {
        try {
            $user = $request->user();

            $address = implode(', ', [
                $request->street,
                $request->locality,
                $request->province,
                $request->postal_code,
                'Argentina',
            ]);

            $geoData = $this->geocodingService->geocode($address);

            DB::transaction(function () use ($user, $request, $geoData) {
                $user->addresses()
                    ->where('is_default', true)
                    ->update([
                        'is_default' => false,
                    ]);

                Address::create([
                    'name' => ucwords($request->name),
                    'street' => ucwords($request->street),
                    'intersection' => $request->intersection ? ucwords($request->intersection) : null,
                    'locality' => ucwords($request->locality),
                    'province' => ucwords($request->province),
                    'postal_code' => $request->postal_code,
                    'latitude' => $geoData['latitude'],
                    'longitude' => $geoData['longitude'],
                    'is_default' => true,
                    'user_id' => $user->id,
                ]);

                return redirect()->back()->with('success', 'Dirección creada');
            });
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ocurrió un error al guardar la dirección.'
                );
        }
    }

    public function update(Request $request) {}

    public function updateDefault(Request $request)
    {
        $request->validate([
            'address' => 'required|array',
            'address.*' => 'required|exists:addresses,id',
        ]);

        $user = $request->user();
        $address = $user->defaultAddress;

        if ($address->id !== $request->address[0]) {
            $address->update(['is_default' => false]);
            Address::where('id', $request->address[0])->update(['is_default' => true]);
        }

        return redirect()->back()->with('success', 'Dirección actualizada');
    }

    public function fetch(String $id): JsonResponse
    {
        $address = Address::withTrashed()->findOrFail($id, ['name', 'street', 'locality', 'province', 'postal_code', 'is_default', 'user_id']);

        return response()->json($address);
    }
}
