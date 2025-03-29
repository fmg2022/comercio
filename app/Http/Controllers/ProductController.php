<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function showOne(Request $request, $id)
    {
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        $product = Product::findOrFail($id);
        $products = Product::where('category_id', $product->category_id)->get();
        return view('pages.product.show', compact('product', 'categories', 'products'));
    }

    /* Buscar productos
        -> [Producto, ...], [Categoría, ...], String
    */
    public function findProducts(Request $request, $q)
    {
        $products = Product::where('name', 'like', '%' . $q . '%')->get();
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        return view('pages.product.list', compact('products', 'categories', 'q'));
    }

    public function getAllProducts(Request $request)
    {
        $products = Product::all();
        return view('pages.product.list', compact('products'));
    }

    // Para el Dashboard

    public function index()
    {
        return view('pages.dashboard.product', [
            'products' => Product::paginate(15),
            'user' => Auth::user(),
        ]);
    }
}
