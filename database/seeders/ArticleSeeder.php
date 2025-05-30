<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            DB::table('articles')->insert([
                'table' => 'products',
                'table_id' => $i,
            ]);
        }
        DB::table('articles')->insert([
            // arroz
            ['table' => 'products', 'table_id' => 10],
            // gaseosas
            ['table' => 'categories', 'table_id' => 20],
            // leches
            ['table' => 'categories', 'table_id' => 16,],
            // galletas
            ['table' => 'categories', 'table_id' => 31,],
        ]);
    }
}
