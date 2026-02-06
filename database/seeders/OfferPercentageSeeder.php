<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferPercentageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offer_percentages')->insert([
            [
                'offer_template_id' => 1,
                'percentage' => 0.1
            ],
            [
                'offer_template_id' => 4,
                'percentage' => 0.25
            ],
        ]);
    }
}
