<?php

namespace App\Services;

use App\Models\{Brand, Category, Offer, Product};
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchService
{
  /**
   * Busca productos en base a una cadena de texto
   */
  public function search(string $query): Collection
  {
    $brand = Brand::where('name', $query)->first();
    $category = Category::where('name', $query)->first();

    if ($brand || $category) {
      return $this->searchByBrandOrCategory($brand, $category);
    }

    return $this->searchByText($query);
  }

  /**
   * Obtiene productos de una oferta específica
   */
  public function getProductsByOffer(int $offerId): Collection
  {
    $offer = Offer::active()->findOrFail($offerId);
    return $offer->products()
      ->with('category')
      ->whereColumn('stock', '>', 'min_stock')
      ->get();
  }

  /**
   * Obtiene marcas y categorías asociadas a una colección de productos
   */
  public function getRelatedBrands(Collection $products): Collection
  {
    return Brand::whereIn('id', $products->pluck('brand_id'))
      ->select(['name', 'id'])
      ->get();
  }

  public function getRelatedCategories(Collection $products): Collection
  {
    return Category::whereIn('id', $products->pluck('category_id'))
      ->select(['name', 'id'])
      ->get();
  }

  // --- Métodos privados auxiliares ---

  private function searchByBrandOrCategory(?Brand $brand, ?Category $category): Collection
  {
    $query = Product::query()
      ->select(['id', 'name', 'brand_id', 'category_id', 'image', 'price', 'stock', 'weight']);

    if ($brand) {
      $query->orWhere('brand_id', $brand->id);
    }

    if ($category) {
      $query->orWhereIn('category_id', $category->leafDescendantIds());
    }

    return $query->get();
  }

  private function searchByText(string $query): Collection
  {
    return Product::query()
      ->select(['id', 'name', 'brand_id', 'category_id', 'image', 'price', 'stock', 'weight'])
      ->where(function (Builder $q) use ($query) {
        $q->where('name', 'LIKE', "%{$query}%")
          ->orWhere('container', 'LIKE', "%{$query}%")
          ->orWhere('weight', 'LIKE', "%{$query}%");
      })
      ->whereColumn('stock', '>', 'min_stock')
      ->get();
  }
}
