<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['name' => 'Almacén', 'parent_id' => null], // 1
            ['name' => 'Lacteos', 'parent_id' => null], // 2
            ['name' => 'Bebidas', 'parent_id' => null], // 3
            ['name' => 'Limpieza', 'parent_id' => null], // 4
            ['name' => 'Golosinas', 'parent_id' => null], // 5
            ['name' => 'Perfumería', 'parent_id' => null], // 6
            ['name' => 'Electrónica', 'parent_id' => null], // 7
            ['name' => 'Bebés y Niños', 'parent_id' => null], // 8
            ['name' => 'Fideo', 'parent_id' => 1],
            ['name' => 'Arroz', 'parent_id' => 1],
            ['name' => 'Legumbres', 'parent_id' => 1],
            ['name' => 'Harina', 'parent_id' => 1],
            ['name' => 'Cereal', 'parent_id' => 1],
            ['name' => 'Aceite', 'parent_id' => 1],
            ['name' => 'Vinagre', 'parent_id' => 1],
            ['name' => 'Leche', 'parent_id' => 2],
            ['name' => 'Manteca', 'parent_id' => 2],
            ['name' => 'Yogur', 'parent_id' => 2],
            ['name' => 'Queso', 'parent_id' => 2],
            ['name' => 'Gaseosa', 'parent_id' => 3],
            ['name' => 'Vino', 'parent_id' => 3],
            ['name' => 'Cerveza', 'parent_id' => 3],
            ['name' => 'Agua', 'parent_id' => 3],
            ['name' => 'Jugos', 'parent_id' => 3],
            ['name' => 'Lavandina', 'parent_id' => 4],
            ['name' => 'Cera', 'parent_id' => 4],
            ['name' => 'Javón', 'parent_id' => 4],
            ['name' => 'Esponja', 'parent_id' => 4],
            ['name' => 'Perfume para pisos', 'parent_id' => 4],
            ['name' => 'Alfajor', 'parent_id' => 5],
            ['name' => 'Galletas', 'parent_id' => 5],
            ['name' => 'Caramelos', 'parent_id' => 5],
            ['name' => 'Colonias', 'parent_id' => 6],
            ['name' => 'Desodorantes', 'parent_id' => 6],
            ['name' => 'Shampoo', 'parent_id' => 6],
            ['name' => 'Celular', 'parent_id' => 7],
            ['name' => 'Parlantes', 'parent_id' => 7],
            ['name' => 'Juguete', 'parent_id' => 8],
            ['name' => 'Pañal', 'parent_id' => 8],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categories')->insert($categoria);
        }
    }
}
