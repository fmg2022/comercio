<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showOne(Request $request, $id)
    {
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        $product = Product::findOrFail($id);
        $products = Product::where('category_id', $product->category_id)->get();
        return view('product.show', compact('product', 'categories', 'products'));
    }

    public function findProducts(Request $request, $q)
    {
        $products = Product::where('name', 'like', '%' . $q . '%')->get();
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        return view('product.list', compact('products', 'categories', 'q'));
    }

    public function getAllProducts(Request $request)
    {
        $products = Product::all();
        return view('product.list', compact('products'));
    }
}
