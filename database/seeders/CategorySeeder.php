<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents('database/json/categorias.json');
        $data = json_decode($json, true);

        foreach ($data as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'parent_id' => $category['parent_id'],
            ]);
        }
    }
}
