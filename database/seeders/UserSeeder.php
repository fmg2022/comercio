<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Frame',
                'surname' => 'Prime',
                'phone' => '+54 1234567890',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
                'active' => true,
            ],
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
                'email' => 'fernando@gmail.com',
                'password' => bcrypt('123456'),
                'active' => false,
            ]
        ]);

        User::factory()->count(10)->create();
    }
}
