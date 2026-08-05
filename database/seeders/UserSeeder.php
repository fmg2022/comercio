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
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
            [
                'name' => 'Fernando',
                'surname' => 'Suarez',
                'phone' => '+54 098 765-4321',
                'email' => 'logistica@gmail.com',
                'dni' => '82343678',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
            [
                'name' => 'Juan',
                'surname' => 'Liendro',
                'phone' => '+54 098 765-4321',
                'email' => 'soporte@gmail.com',
                'dni' => '12335678',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
            [
                'name' => 'Carlos',
                'surname' => 'Torres',
                'phone' => '+54 098 765-4321',
                'email' => 'cliente@gmail.com',
                'dni' => '92345678',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'active' => true,
            ],
        ];

        User::create($users[0])->assignRole('admin');
        User::create($users[1])->assignRole('logistics');
        User::create($users[2])->assignRole('support');
        User::create($users[3])->assignRole('client');

        User::factory(8)->create()->each(function (User $user) {
            $user->assignRole('logistics');
        });

        User::factory(50)->create()->each(function (User $user) {
            $user->assignRole('client');
        });

        User::factory(3)->create()->each(function (User $user) {
            $user->assignRole('admin');
        });
    }
}
