<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OfferTemplate;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::where('parent_id', null)->get()->values('name', 'id');
        $selectedCategories = Category::where('parent_id', '!=', null)->limit(5)->get()->values('name', 'id');
        $products = Product::with('brand:id,name')->select('id', 'name', 'brand_id', 'image', 'price')->inRandomOrder()->limit(6)->get();
        $offers = OfferTemplate::all('id', 'name');
        $brands = Brand::inRandomOrder()->limit(7)->get()->values('name', 'id');

        return view('pages.index', compact('categories', 'products', 'offers', 'selectedCategories', 'brands'));
    }
}
