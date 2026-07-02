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
        $selectedCategories = Category::where('parent_id', '!=', null)->limit(5)->get()->values('name', 'id');
        $products = Product::with('brand:id,name')
            ->whereColumn('stock', '>', 'min_stock')
            ->select(['id', 'name', 'brand_id', 'image', 'price'])->inRandomOrder()->limit(6)->get();
        $offers = OfferTemplate::limit(5)->get(['id', 'name']);
        $brands = Brand::inRandomOrder()->limit(7)->get(['name', 'id']);

        if (Auth::check() && CartFacade::getContent()->isEmpty()) {
            $this->cartService->loadCartFromDatabase(Auth::user());
        }

        return view('pages.index', compact('products', 'offers', 'selectedCategories', 'brands'));
    }

    public function showProduct(Product $product): View
    {
        $products = Product::where('category_id', $product->category_id)->whereColumn('stock', '>', 'min_stock')->limit(5)->get();
        $categoriesNav = $product->category->breadcrumbs();

        $categoriesNav[] = $product->name;
        return view('pages.home.product.show', compact('product', 'products', 'categoriesNav'));
    }

    public function search(Request $request): View
    {
        $validated = $request->validate([
            'query' => 'nullable|string|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|exists:categories,id',
            'brands' => 'nullable|array',
            'brands.*' => 'nullable|exists:brands,id',
        ]);

        $filters = [
            'brands' => $validated['brands'] ?? [],
            'categories' => $validated['categories'] ?? [],
        ];
        $query = $validated['query'] ?? '';

        $products = Product::query()
            ->with(['brand:id,name', 'category:id,name,parent_id'])
            ->when(!empty($filters['categories']), function ($query) use ($filters) {
                $query->whereIn('category_id', $filters['categories']);
            })
            ->when(!empty($filters['brands']), function ($query) use ($filters) {
                $query->whereIn('brand_id', $filters['brands']);
            })
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q) use ($query) {
                    $q->whereFullText(['name', 'weight', 'container'], $query . '*', ['mode' => 'boolean'])
                        ->orWhereHas('brand', fn($qry) => $qry->whereLike('name', "%{$query}%"))
                        ->orWhereHas('category', fn($qry) => $qry->whereLike('name', "%{$query}%"));
                })
                    ->orderByRaw("
                CASE
                    WHEN name = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN name LIKE ? THEN 3
                    ELSE 4
                END
            ", [$query, "{$query}%", "%{$query}%"]);
            })
            ->whereColumn('stock', '>', 'min_stock')
            ->get();
        $categoriesNav = [];
        $brandsProducts = Brand::whereIn('id', $products->pluck('brand_id'))->select('name', 'id')->get();
        $categoriesProduct = Category::whereIn('id', $products->pluck('category_id'))->select('name', 'id')->get();

        return view('pages.home.product.list', compact('products', 'categoriesNav', 'brandsProducts', 'categoriesProduct', 'query', 'filters'));
    }

    public function getProductsCategory(Category $category): View
    {
        $categoriesProduct = $category->childrenTree
            ->flatMap(fn($child) => $child->childrenTree->prepend($child))
            ->prepend($category)
            ->filter(fn($category) => $category->childrenTree->isEmpty());
        $products = Product::whereIn('category_id', $categoriesProduct->pluck('id'))->whereColumn('stock', '>', 'min_stock')->get();
        $categoriesProduct = $categoriesProduct->whereIn('id', $products->pluck('category_id'));
        $brandsProducts = Brand::whereIn('id', $products->pluck('brand_id'))->select('name', 'id')->get();
        $categoriesNav = $category->breadcrumbs();
        return view('pages.home.product.list', compact('products', 'brandsProducts', 'categoriesProduct', 'categoriesNav'));
    }

    public function getProductsOffer(Offer $offer): View
    {
        $products = $offer->products()
            ->with('category')
            ->whereColumn('stock', '>', 'min_stock')
            ->get();
        $categoriesNav[] = $offer->offerTemplate->name;
        $brandsProducts = Brand::whereIn('id', $products->pluck('brand_id'))->select('name', 'id')->get();
        $categoriesProduct = Category::whereIn('id', $products->pluck('category_id'))->select('name', 'id')->get();

        return view('pages.home.product.list', compact('products', 'categoriesNav', 'brandsProducts', 'categoriesProduct'));
    }
}
