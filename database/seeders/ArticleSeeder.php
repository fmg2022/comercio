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
        // arroz
        DB::table('articles')->insert([
            'table' => 'products',
            'table_id' => 10,
        ]);
        // gaseosas
        DB::table('articles')->insert([
            'table' => 'categories',
            'table_id' => 20,
        ]);
        // leches
        DB::table('articles')->insert([
            'table' => 'categories',
            'table_id' => 16,
        ]);
        // galletas
        DB::table('articles')->insert([
            'table' => 'categories',
            'table_id' => 31,
        ]);
    }
}
