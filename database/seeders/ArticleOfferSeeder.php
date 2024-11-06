<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer::find(1)->articles()->attach([
            1 => ['initial_date' => '2024-10-29', 'expiration_date' => '2024-11-01'],
            4 => ['initial_date' => '2024-10-29', 'expiration_date' => '2024-11-01']
        ]);
        Offer::find(2)->articles()->attach(6, ['initial_date' => '2024-10-25', 'expiration_date' => '2024-11-02']);
        Offer::find(3)->articles()->attach(7, ['initial_date' => '2024-11-25', 'expiration_date' => '2024-12-02']);
        Offer::find(4)->articles()->attach(5, ['initial_date' => '2024-11-25', 'expiration_date' => '2024-12-02']);
    }
}
