<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OfferTemplate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function index()
    {
        $selectedCategories = Category::where('parent_id', '!=', null)->limit(5)->get()->values('name', 'id');
        $products = Product::with('brand:id,name')->select('id', 'name', 'brand_id', 'image', 'price')->inRandomOrder()->limit(6)->get();
        $offers = OfferTemplate::all('id', 'name');
        $brands = Brand::inRandomOrder()->limit(7)->get()->values('name', 'id');

        return view('pages.index', compact('products', 'offers', 'selectedCategories', 'brands'));
    }

    public function showProduct($id): View
    {
        $product = Product::findOrFail($id);
        $products = Product::where('category_id', $product->category_id)->get();
        $categoriesNav = $product->getParentCategories();
        return view('pages.home.product.show', compact('product', 'products', 'categoriesNav'));
    }

    /* Buscar productos
        -> [Producto, ...], [Categoría, ...], String
    */
    // public function findProducts(Request $request, $q): View
    // {
    //     $products = Product::where('name', 'like', '%' . $q . '%')->get();
    //     return view('pages.home.product.list', compact('products', 'q'));
    // }

    public function getProductsCategory(string $id): View
    {
        $categories = Category::findOrFail($id)->childrenTree()->pluck('id');
        $products = Product::whereIn('category_id', $categories)->get();
        return view('pages.home.product.list', compact('products'));
    }
}
