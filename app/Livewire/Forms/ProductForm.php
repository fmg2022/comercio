<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    public ?int $productId = null;

    #[Validate('required|string|max:255')]
    public ?string $name = '';
    #[Validate('required|string|max:255')]
    public ?string $sku = null;
    #[Validate('required|numeric|min:0')]
    public ?float $price = 0;
    #[Validate('required|integer|min:0')]
    public ?int $stock = 0;
    #[Validate('required|integer|min:0')]
    public ?int $minStock = 0;
    #[Validate('required|string|max:12')]
    public ?string $weight = '';
    #[Validate('required|string|max:50')]
    public ?string $container = '';
    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    #[Validate('required|exists:categories,id')]
    public ?int $categoryId = null;
    #[Validate('required|exists:brands,id')]
    public ?int $brandId = null;

    protected function validationAttributes(): array
    {
        return [
            'name' => 'nombre',
            'sku' => 'SKU',
            'price' => 'precio',
            'stock' => 'stock',
            'minStock' => 'stock mínimo',
            'weight' => 'peso',
            'container' => 'contenedor',
            'description' => 'descripción',
            'categoryId' => 'categoría',
            'brandId' => 'marca',
        ];
    }

    public function setProduct(Product $product): void
    {
        $this->productId = $product->id;

        $this->fill([
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'stock' => $product->stock,
            'minStock' => $product->min_stock,
            'weight' => $product->weight,
            'container' => $product->container,
            'description' => $product->description,
            'categoryId' => $product->category_id,
            'brandId' => $product->brand_id,
        ]);
    }

    public function store(): int
    {
        $data = $this->validate();
        $product = Product::create($this->toProductAttributes($data));

        return $product->id;
    }

    public function update(): void
    {
        $data = $this->validate();
        $product = Product::findOrFail($this->productId);
        $product->update($this->toProductAttributes($data));
    }

    private function toProductAttributes(array $data): array
    {
        return [
            'name' => $data['name'],
            'sku' => $data['sku'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'min_stock' => $data['minStock'],
            'weight' => $data['weight'],
            'container' => $data['container'],
            'description' => $data['description'],
            'category_id' => $data['categoryId'],
            'brand_id' => $data['brandId'],
        ];
    }
}
