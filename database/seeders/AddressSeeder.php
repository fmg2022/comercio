<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'user_id' => 1,
            'name' => 'Salud',
            'street' => 'Jaime Durand',
            'intersection' => 'Lazarovich',
            'locality' => 'Salta',
            'province' => 'Salta',
            'postal_code' => 'A4400',
            'latitude' => -24.737465,
            'longitude' => -65.404197,
            'is_default' => true,
        ]);

        Address::create([
            'user_id' => 2,
            'name' => 'Colegio',
            'street' => 'Calle M',
            'intersection' => 'Calle 11, mz 471 f',
            'locality' => 'Salta',
            'province' => 'Salta',
            'postal_code' => 'A4400',
            'latitude' => -24.721783,
            'longitude' => -65.415891,
            'is_default' => true,
        ]);

        Address::create([
            'user_id' => 3,
            'name' => 'Local 2',
            'street' => 'Los Arces 700',
            'intersection' => 'Av. Los Molles',
            'locality' => 'Salta',
            'province' => 'Salta',
            'postal_code' => 'A4400',
            'latitude' => -24.762476,
            'longitude' => -65.393661,
            'is_default' => true,
        ]);

        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $count = rand(1, 3);
            $addresses = Address::factory()
                ->count($count)
                ->for($user)  // Asigna el user_id automáticamente
                ->create();

            // Marcamos la PRIMERA de ellas como predeterminada
            $addresses->first()->setAsDefault();
        }
    }
}
