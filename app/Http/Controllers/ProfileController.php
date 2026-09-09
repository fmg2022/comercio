<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\GeocodingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private GeocodingService $geocodingService) {}

    public function index(Request $request): View
    {
        return view('pages.home.profile.index', [
            'user' => $request->user(),
            'address' => $request->user()->defaultAddress,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('pages.home.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();
        $userData = [
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'dni' => $data['dni'],
            'phone' => $data['phone'],
        ];

        $addressData = [
            'name' => $data['name'],
            'street' => $data['street'],
            'intersection' => $data['intersection'] ?? null,
            'locality' => $data['locality'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
        ];


        try {
            $address = implode(', ', array_filter([
                $addressData['street'],
                $addressData['intersection'] ?? null,
                $addressData['locality'],
                $addressData['province'],
                $addressData['postal_code'],
                'Argentina',
            ]));
            $geoData = $this->geocodingService->geocode($address);

            $emailChanged = false;

            DB::transaction(function () use ($user, $userData, $addressData, $geoData, &$emailChanged) {
                // Address
                if (!$user->defaultAddress) {
                    $user->addresses()->create([...$addressData, 'latitude' => $geoData['latitude'], 'longitude' => $geoData['longitude']]);
                    return;
                } else {
                    $user->defaultAddress->update([...$addressData, 'latitude' => $geoData['latitude'], 'longitude' => $geoData['longitude']]);
                }

                // User
                $user->fill($userData);

                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                $user->save();
                $emailChanged = $user->wasChanged('email');
            });

            if ($emailChanged) {
                $user->sendEmailVerificationNotification();
            }

            return redirect()->back()->with('success', 'Perfil actualizado');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el perfil.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
