<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Offer;
use App\Models\OfferTemplate;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Joelwmale\Cart\Facades\CartFacade;

class IndexController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        CartFacade::setSessionKey('cart_' . auth()->id());
        $selectedCategories = Category::where('parent_id', '!=', null)->limit(5)->get()->values('name', 'id');
        $products = Product::with('brand:id,name')
            ->where('stock', '>', 'min_stock')
            ->select(['id', 'name', 'brand_id', 'image', 'price'])->inRandomOrder()->limit(6)->get();
        $offers = OfferTemplate::all(['id', 'name']);
        $brands = Brand::inRandomOrder()->limit(7)->get()->values('name', 'id');

        if (Auth::check() && CartFacade::getContent()->isEmpty()) {
            $this->cartService->loadCartFromDatabase(Auth::user());
        }

        return view('pages.index', compact('products', 'offers', 'selectedCategories', 'brands'));
    }

    public function showProduct(Product $product): View
    {
        $products = Product::where('category_id', $product->category_id)->where('stock', '>', 'min_stock')->limit(5)->get();
        $categoriesNav = $product->category->breadcrumbs();

        $categoriesNav[] = $product->name;
        return view('pages.home.product.show', compact('product', 'products', 'categoriesNav'));
    }

    public function search(Request $request): View
    {
        $validated = $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $products = Product::query()
            ->with(['brand:id,name', 'category:id,name,parent_id'])
            ->when($validated['query'], function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereFullText(['name', 'weight', 'container'], $search . '*', ['mode' => 'boolean'])
                        ->orWhereHas('brand', fn($query) => $query->whereLike('name', "%{$search}%"))
                        ->orWhereHas('category', fn($query) => $query->whereLike('name', "%{$search}%"));
                })
                    ->orderByRaw("
                CASE
                    WHEN name = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN name LIKE ? THEN 3
                    ELSE 4
                END
            ", [$search, "{$search}%", "%{$search}%"]);
            })
            ->where('stock', '>', 'min_stock')
            ->paginate(12);
        $categoriesNav[] = $validated['query'];
        $brandsProducts = Brand::whereIn('id', $products->pluck('brand_id'))->select('name', 'id')->get();
        $categoriesProduct = [];
        foreach ($products as $product) {
            $categoriesProduct = $product->category->breadcrumbs() + $categoriesProduct;
        }

        return view('pages.home.product.list', compact('products', 'categoriesNav', 'brandsProducts', 'categoriesProduct'));
    }

    public function getProductsCategory(Category $category): View
    {
        $categoriesProduct = $category
            ->childrenTree
            ->mapWithKeys(
                fn($child) =>
                $child->childrenTree
                    ->prepend($child)
                    ->pluck('name', 'id')
            )
            ->prepend($category->name, $category->id)
            ->toArray();

        $products = Product::whereIn('category_id', array_keys($categoriesProduct))->where('stock', '>', 'min_stock')->paginate(12);
        $brandsProducts = Brand::whereIn('id', $products->pluck('brand_id'))->select('name', 'id')->get();
        $categoriesNav = $category->breadcrumbs();
        return view('pages.home.product.list', compact('products', 'brandsProducts', 'categoriesProduct', 'categoriesNav'));
    }

    public function getProductsOffer(Offer $offer): View
    {
        $products = $offer->products()
            ->with('category')
            ->where('stock', '>', 'min_stock')
            ->paginate(12);
        $categoriesNav[] = $offer->offerTemplate->name;
        $brandsProducts = Brand::whereIn('id', $products->pluck('brand_id'))->select('name', 'id')->get();
        $categoriesProduct = [];

        foreach ($products as $product) {
            $categoriesProduct = $product->category->breadcrumbs() + $categoriesProduct;
        }

        return view('pages.home.product.list', compact('products', 'categoriesNav', 'brandsProducts', 'categoriesProduct'));
    }
}
