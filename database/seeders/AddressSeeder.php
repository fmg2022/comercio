<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $count = rand(1, 3);
            $addresses = \App\Models\Address::factory()
                ->count($count)
                ->for($user)  // Asigna el user_id automáticamente
                ->create();

            // Marcamos la PRIMERA de ellas como predeterminada
            $addresses->first()->setAsDefault();
        }
    }
}
