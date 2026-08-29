<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{

    public function __construct(public GeocodingService $geocodingService) {}

    public function store(AddressRequest $request)
    {
        try {
            $user = $request->user();

            $address = implode(', ', [
                $request->street_1,
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
                    'street_1' => ucwords($request->street_1),
                    'street_2' => $request->street_2 ? ucwords($request->street_2) : null,
                    'locality' => ucwords($request->locality),
                    'province' => ucwords($request->province),
                    'postal_code' => $request->postal_code,
                    'latitude' => $geoData['latitude'],
                    'longitude' => $geoData['longitude'],
                    'is_default' => true,
                    'user_id' => $user->id,
                ]);

                if ($request->route('profile.index')) {
                    return redirect()->route('profile.index')->with('success', 'Dirección creada');
                }

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
}
