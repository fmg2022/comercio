<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Nette\Utils\Strings;

use function Pest\Laravel\json;

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
        return view('pages.dashboard.product.index', [
            'products' => Product::paginate(10),
            'productsDeleted' => Product::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
            'categories' => Category::where('parent_id', null)->get()
        ]);
    }

    public function create(): View
    {
        return view('pages.dashboard.product.create', [
            'categories' => Category::where('parent_id', null)->get()
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
            'categories' => Category::where('parent_id', null)->get()
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
        $product = Product::withTrashed()->find($id, ['id', 'name', 'mark', 'image', 'sku', 'price', 'quantity', 'description']);
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
