<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function showOne(Request $request, $id): View
    {
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        $product = Product::findOrFail($id);
        $products = Product::where('category_id', $product->category_id)->get();
        return view('pages.product.show', compact('product', 'categories', 'products'));
    }

    /* Buscar productos
        -> [Producto, ...], [Categoría, ...], String
    */
    public function findProducts(Request $request, $q): View
    {
        $products = Product::where('name', 'like', '%' . $q . '%')->get();
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        return view('pages.product.list', compact('products', 'categories', 'q'));
    }

    // Quitar
    public function getAllProducts(Request $request): View
    {
        $products = Product::all();
        return view('pages.product.list', compact('products'));
    }

    // Para el Dashboard

    public function index(): View
    {
        return view('pages.dashboard.product', [
            'products' => Product::paginate(15),
            'user' => Auth::user(),
        ]);
    }

    public function create(): View
    {
        return view('pages.dashboard.create', [
            'user' => Auth::user(),
            'categories' => Category::all()
        ]);
        // where('parent_id', null)->get()->values('name', 'id')
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Producto creado correctamente');
    }

    public function show(string $id)
    {
        return true;
    }

    public function edit(Product $product)
    {
        return true;
    }

    public function update(Request $request, Product $product)
    {
        return true;
    }

    public function destroy(Product $product)
    {
        return true;
    }
}
