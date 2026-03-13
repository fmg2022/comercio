<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents('database/json/marcas.json');
        $data = json_decode($json, true);

        foreach ($data as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
            ]);
        }
    }
}
