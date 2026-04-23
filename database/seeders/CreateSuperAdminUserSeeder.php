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
            'name' => 'Admin Super',
            'surname' => 'Unico',
            'phone' => '+54 1234567890',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'active' => true,
        ]);

        $role = Role::create(['name' => 'Super Admin']);

        $superAdmin->assignRole($role);
    }
}
