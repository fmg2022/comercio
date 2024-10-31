<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        $products = Product::limit(10)->get()->values('id', 'name', 'mark', 'image', 'price');
        $offers = Offer::limit(10)->get()->values('name');
        return view('index', compact('categories', 'products', 'offers'));
    }
}
