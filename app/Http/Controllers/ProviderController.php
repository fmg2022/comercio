<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ProviderController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.provider.index', [
            'providers' => Provider::withTrashed()->orderByDesc('active')->orderBy('contact_name')->paginate(10),
            'products' => Product::get(['name', 'id']),
        ]);
    }

    public function store(ProviderRequest $request): RedirectResponse
    {
        $provider = Provider::create($request->validated());
        $provider->products()->attach($request->products_ids);

        return redirect()->back();
    }

    public function update(ProviderRequest $request, Provider $provider): RedirectResponse
    {
        $provider->update($request->validated());
        $provider->products()->sync($request->products_ids);

        return redirect()->back();
    }

    public function destroy(Provider $provider): RedirectResponse
    {
        $provider->update(['active' => false]);
        $provider->delete();
        return redirect()->back();
    }

    public function restore(String $id): RedirectResponse
    {
        $provider = Provider::onlyTrashed()->findOrFail($id);
        $provider->restore();
        return redirect()->back();
    }

    public function fetch(String $id): JsonResponse
    {
        $provider = Provider::withTrashed()->with('products:id')->findOrFail($id);
        $provider->products_ids = $provider->products()->pluck('products.id');
        $provider->unsetRelation('products');

        return response()->json($provider);
    }
}
