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
            'list products',
            'show orders',
        ];
        $customer->givePermissionTo($customerPermission);

        $vendor = Role::create(['name' => 'Vendedor']);
        $vendorPermission = [
            ...$customerPermission,
            'list users',
            'list addresses',
            'list orders',
            'list offers', // + Offer Template
        ];
        $vendor->givePermissionTo($vendorPermission);

        $admin = Role::create(['name' => 'Admin']);
        $adminPermission = [
            ...$vendorPermission,
            'list roles',
            'manage users',
            'list product-attributes',
            'manage products-and-attributes',
            'list state-type-tables',
            'manage state-type-tables',
            'list payments',
            'manage offers',
            'list carts',
            'list shipments',
            'manage shipments',
            'manage providers',
        ];
        $admin->givePermissionTo($adminPermission);
    }
}
