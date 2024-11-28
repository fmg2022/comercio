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
}
