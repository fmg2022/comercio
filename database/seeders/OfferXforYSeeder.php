<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferXforYSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offer_xforys')->insert([
            [
                'offer_template_id' => 2,
                'buy_qty' => 2,
                'pay_qty' => 1
            ],
            [
                'offer_template_id' => 3,
                'buy_qty' => 4,
                'pay_qty' => 2
            ],
        ]);
    }
}
