<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.brand.index', [
            'brands' => Brand::paginate(10),
            'brandsDeleted' => Brand::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
        ]);
        Brand::create($validated);
        return redirect()->back();
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->ignore($brand->id),
            ],
        ]);
        $brand->update($validated);
        return redirect()->back();
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();
        return redirect()->back();
    }

    public function restore(String $id): RedirectResponse
    {
        Brand::withTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

    public function fetch(String $id): JsonResponse
    {
        return response()->json(
            Brand::findOrFail($id, ['id', 'name']),
            200
        );
    }
}
