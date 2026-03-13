<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProductController extends Controller
{

    // Para el Dashboard

    public function index(): View
    {
        return view('pages.dashboard.product.index', [
            'products' => Product::paginate(10),
            'productsDeleted' => Product::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
            'categories' => Category::where('parent_id', null)->get(),
            'brands' => Brand::all()
        ]);
    }

    public function create(): View
    {
        return view('pages.dashboard.product.create', [
            'categories' => Category::where('parent_id', null)->get(),
            'brands' => Brand::all()
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index');
    }

    public function show(string $id): View
    {
        return view('pages.dashboard.product.show', [
            'product' => Product::findOrFail($id),
        ]);
    }

    public function edit(String $id): View
    {
        return view('pages.dashboard.product.edit', [
            'product' => Product::findOrFail($id),
            'categories' => Category::where('parent_id', null)->get(),
            'brands' => Brand::all()
        ]);
    }

    public function update(ProductRequest $request, String $id)
    {
        Product::findOrFail($id)->update($request->validated());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return Redirect::back();
    }

    public function restore(String $id): RedirectResponse
    {
        Product::onlyTrashed()->findOrFail($id)->restore();
        return Redirect::back();
    }

    public function fetch(String $id): JsonResponse
    {
        $product = Product::withTrashed()->with('brand:id,name')->find($id, ['id', 'name', 'brand_id', 'image', 'sku', 'price', 'stock', 'description']);
        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        return response()->json($product);
    }

    public function ordersbyproduct(String $id): View
    {
        $product = Product::withTrashed()->findOrFail($id);
        $orders = $product->orders()->withTrashed()->paginate(10);
        return view('pages.dashboard.product.orders', compact('product', 'orders'));
    }
}
