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
                'phone' => '+54 0987654321',
                'email' => 'juan@gmail.com',
                'password' => bcrypt('123456'),
                'active' => true,
            ],
            [
                'name' => 'Fernando',
                'surname' => 'Suarez',
                'phone' => '+54 0987654321',
                'email' => 'vendedor@gmail.com',
                'password' => bcrypt('123456'),
                'active' => true,
            ],
            [
                'name' => 'Carlos',
                'surname' => 'Perez',
                'phone' => '+54 0987654321',
                'email' => 'cliente@gmail.com',
                'password' => bcrypt('123456'),
                'active' => true,
            ],
        ];
        $roleAsign = ['Admin', 'Vendedor', 'Cliente'];
        for ($i = 0; $i < 3; $i++) {
            (User::create($users[$i]))->assignRole($roleAsign[$i]);
        }

        User::factory(4)->create(['active' => fake()->boolean(80)])->each(function (User $user) {
            $user->assignRole('Vendedor');
        });

        User::factory(10)->create(['active' => fake()->boolean(40)])->each(function (User $user) {
            $user->assignRole('Cliente');
        });
    }
}
