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
        $selectedCategories = Category::where('parent_id', '!=', null)->limit(5)->get()->values('name', 'id');
        $products = Product::limit(4)->get()->values('id', 'name', 'mark', 'image', 'price');
        $offers = Offer::limit(10)->get()->values('name');
        $marks = Product::select('mark as name')->distinct()->limit(5)->get();

        return view('pages.index', compact('categories', 'products', 'offers', 'selectedCategories', 'marks'));
    }
}
