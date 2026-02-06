<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavouriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $qty = random_int(0, 8);
            $user->products()->sync(
                DB::table('products')->inRandomOrder()->limit($qty)->pluck('id')
            );
        }
    }
}
