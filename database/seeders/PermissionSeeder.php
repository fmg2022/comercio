<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* 
            * MANAGE: show items, create, edit, delete. Según corresponda [Tiene por lo menos 2 permisos englobados]
            * LIST: show, list, filter
        */
        $permissions = [
            'list my_section', // Address, Order [ver y crear], Payment, Cart (ver los items)
            'add my_cart', // Solo para home
            'list roles',
            'manage roles',
            'list users',
            'manage users',
            'list products',
            'list product-attributes',
            'manage products-and-attributes', // + Category + Brand + Product
            'list addresses',
            'manage addresses',
            'list state-type-tables', // States & Types
            'manage state-type-tables', // States & Types
            'list orders',
            'show orders',
            'list payments',
            'list offers', // + Offer Template
            'manage offers', // + Offer Template
            'list carts', // + Carts Details
            'manage carts-details',
            'manage providers', // + Providers Details
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
