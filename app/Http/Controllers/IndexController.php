<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Category, Product};
use Illuminate\View\View;

class IndexController extends Controller
{
    public function index()
    {
        $selectedCategories = Category::where('parent_id', '!=', null)->limit(5)->get()->values('name', 'id');
        $products = Product::with('brand:id,name')
            ->whereColumn('stock', '>', 'min_stock')
            ->select(['id', 'name', 'brand_id', 'image', 'price'])->inRandomOrder()->limit(6)->get();
        $brands = Brand::inRandomOrder()->limit(7)->get(['name', 'id']);

        return view('pages.home.index', compact('products', 'selectedCategories', 'brands'));
    }

    public function showProduct(Product $product): View
    {
        $products = Product::where('category_id', $product->category_id)->whereColumn('stock', '>', 'min_stock')->limit(5)->get();
        $categoriesNav = $product->category->breadcrumbs();

        $categoriesNav[] = $product->shortDescription;
        return view('pages.home.product.show', compact('product', 'products', 'categoriesNav'));
    }
}
