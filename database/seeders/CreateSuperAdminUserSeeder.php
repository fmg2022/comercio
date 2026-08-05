<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Admin Gerardo',
            'surname' => 'Fernández',
            'phone' => '+54 123 456-7890',
            'email' => 'test1@gmail.com',
            'dni' => '98765432',
            'password' => bcrypt('123456'),
            'email_verified_at' => now(),
            'active' => true,
        ]);

        $superAdmin->assignRole('super_admin');
    }
}
