<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Juan Carlos',
                'surname' => 'Perez',
                'phone' => '+54 098 765-4321',
                'email' => 'admin@gmail.com',
                'dni' => '12345678',
                'address' => 'Barrio Celeste, Calle G N° 456 Cielo, Sub 123',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
            [
                'name' => 'Fernando',
                'surname' => 'Suarez',
                'phone' => '+54 098 765-4321',
                'email' => 'vendedor@gmail.com',
                'dni' => '82343678',
                'address' => 'Barrio Tierra, Calle Ground N° 456 Cielo',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
            [
                'name' => 'Carlos',
                'surname' => 'Perez',
                'phone' => '+54 098 765-4321',
                'email' => 'cliente@gmail.com',
                'dni' => '92345678',
                'address' => 'Barrio Escuela, Calle 1562 N° 456 Poso',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
        ];

        User::create($users[0])->assignRole(['Cliente', 'Admin']);
        User::create($users[1])->assignRole(['Cliente', 'Vendedor']);
        User::create($users[2])->assignRole('Cliente');

        User::factory(6)->create()->each(function (User $user) {
            $user->assignRole(['Cliente', 'Vendedor']);
        });

        User::factory(50)->create()->each(function (User $user) {
            $user->assignRole('Cliente');
        });

        User::factory(3)->create()->each(function (User $user) {
            $user->assignRole(['Cliente', 'Admin']);
        });
    }
}
