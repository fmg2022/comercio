<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProductController extends Controller
{

    // Para el Dashboard

    public function index(): View
    {
        return view('pages.dashboard.product.index', [
            'products' => Product::paginate(10),
            'productsDeleted' => Product::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
            'categories' => $this->formatFlat(Category::getFullTree()),
            'brands' => Brand::all()
        ]);
    }

    public function create(): View
    {
        return view('pages.dashboard.product.create', [
            'categories' => $this->formatFlat(Category::getFullTree()),
            'brands' => Brand::all()
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index');
    }

    public function show(Product $product): View
    {
        return view('pages.dashboard.product.show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product): View
    {
        return view('pages.dashboard.product.edit', [
            'product' => $product,
            'categories' => $this->formatFlat(Category::getFullTree()),
            'brands' => Brand::all()
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return Redirect::back();
    }

    public function restore(String $id): RedirectResponse
    {
        Product::onlyTrashed()->findOrFail($id)->restore();
        return Redirect::back();
    }

    public function fetch(String $id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id, ['id', 'name', 'brand_id', 'category_id', 'image', 'sku', 'price', 'stock', 'weight', 'container', 'description']);

        return response()->json($product);
    }

    public function ordersbyproduct(String $id): View
    {
        $product = Product::withTrashed()->findOrFail($id);
        $orders = $product->orders()->orderBy('date', 'desc')->paginate(10);
        $currentYear = now()->year;

        $aggregates = DB::table('order_product')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->where('order_product.product_id', $id)
            ->whereYear('orders.date', $currentYear)
            ->select(DB::raw('MONTH(orders.date) as month'), DB::raw('SUM(order_product.quantity) as total_quantity'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $quantity = [];

        foreach ($aggregates as $item) {
            $months[] = Carbon::create()->month($item->month)->translatedFormat('F');
            $quantity[] = (int) $item->total_quantity;
        }

        return view('pages.dashboard.product.orders', compact('product', 'orders', 'months', 'quantity'));
    }

    private function formatFlat(Collection $categories, array &$result = []): array
    {
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'nivel' => $category->nivel
            ];

            if ($category->childrenTree->isNotEmpty()) {
                $this->formatFlat($category->childrenTree, $result);
            }
        }

        return $result;
    }
}
