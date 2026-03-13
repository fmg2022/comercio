<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 29/10/2024
        $json = file_get_contents('database/json/productos.json');
        $data = json_decode($json, true);

        foreach ($data as $product) {
            $category_id = DB::table('categories')->where('name', $product['category'])->value('id');
            $brand_id = DB::table('brands')->where('name', $product['brand'])->value('id');
            DB::table('products')->insert([
                'name' => $product['name'],
                'price' => $product['price'],
                'sku' => $product['SKU'],
                'description' => $product['description'],
                'stock' => $product['stock'],
                'image' => $product['image'],
                'category_id' => $category_id,
                'brand_id' => $brand_id
            ]);
        }
    }
}
