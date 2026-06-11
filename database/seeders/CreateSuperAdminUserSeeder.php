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
            'address' => 'Calle de la casa, N° 1234',
            'password' => bcrypt('123456'),
            'email_verified_at' => now(),
            'active' => true,
        ]);

        $role = Role::create(['name' => 'Super Admin']);

        $superAdmin->assignRole($role);
    }
}
