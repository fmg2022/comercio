<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Role::create(['name' => 'Cliente']);
        $customerPermission = [
            'list my_section',
            'add my_cart',
            'manage addresses',
            'manage carts-details',
            'show orders',
        ];
        $customer->givePermissionTo(Permission::whereIn('name', $customerPermission)->get());

        $vendor = Role::create(['name' => 'Vendedor']);
        $vendorPermission = [
            ...$customerPermission,
            'list users',
            'list addresses',
            'list products',
            'list orders',
            'list offers', // + Offer Template
        ];
        $vendor->givePermissionTo(Permission::whereIn('name', $vendorPermission)->get());

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::whereNot('name', 'manage roles')->get());
    }
}
