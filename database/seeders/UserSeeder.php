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
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
            [
                'name' => 'Fernando',
                'surname' => 'Suarez',
                'phone' => '+54 098 765-4321',
                'email' => 'vendedor@gmail.com',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
            [
                'name' => 'Carlos',
                'surname' => 'Perez',
                'phone' => '+54 098 765-4321',
                'email' => 'cliente@gmail.com',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
        ];

        User::create($users[0])->assignRole(['Cliente', 'Admin']);
        User::create($users[1])->assignRole(['Cliente', 'Vendedor']);
        User::create($users[2])->assignRole('Cliente');

        User::factory(6)->create(['active' => fake()->boolean(80)])->each(function (User $user) {
            $user->assignRole(['Cliente', 'Vendedor']);
        });

        User::factory(50)->create(['active' => fake()->boolean(40)])->each(function (User $user) {
            $user->assignRole('Cliente');
        });

        User::factory(3)->create(['active' => fake()->boolean(20)])->each(function (User $user) {
            $user->assignRole(['Cliente', 'Admin']);
        });
    }
}
