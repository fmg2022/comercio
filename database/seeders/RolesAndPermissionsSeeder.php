<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // =============================================
        // 1. DEFINICIÓN DE TODOS LOS PERMISOS
        // =============================================
        $permissions = [
            // ---------- Mi Sección (Cliente) ----------
            ['name' => 'view_any_own_orders', 'display_name' => 'Listar mis pedidos'],
            ['name' => 'view_own_order', 'display_name' => 'Ver mi pedido'],
            ['name' => 'view_own_cart', 'display_name' => 'Ver mi carrito'],
            ['name' => 'view_any_own_payments', 'display_name' => 'Listar mis pagos'],

            // ---------- Roles ----------
            ['name' => 'view_any_roles', 'display_name' => 'Listar roles'],
            ['name' => 'create_roles', 'display_name' => 'Crear roles'],
            ['name' => 'update_roles', 'display_name' => 'Editar roles'],
            ['name' => 'delete_roles', 'display_name' => 'Eliminar roles'],

            // ---------- Usuarios ----------
            ['name' => 'view_any_users', 'display_name' => 'Listar usuarios'],
            ['name' => 'create_users', 'display_name' => 'Crear usuarios'],
            ['name' => 'update_users', 'display_name' => 'Editar usuarios'],
            ['name' => 'delete_users', 'display_name' => 'Eliminar usuarios'],

            // ---------- Direcciones ----------
            ['name' => 'view_any_addresses', 'display_name' => 'Listar direcciones'],
            ['name' => 'view_address', 'display_name' => 'Ver direccion'],
            ['name' => 'create_addresses', 'display_name' => 'Crear direcciones'],
            ['name' => 'update_addresses', 'display_name' => 'Editar direcciones'],
            ['name' => 'delete_addresses', 'display_name' => 'Eliminar direcciones'],

            // ---------- Productos ----------
            ['name' => 'view_any_products', 'display_name' => 'Listar productos'],
            ['name' => 'view_product', 'display_name' => 'Ver producto'],
            ['name' => 'create_products', 'display_name' => 'Crear productos'],
            ['name' => 'update_products', 'display_name' => 'Editar productos'],
            ['name' => 'delete_products', 'display_name' => 'Eliminar productos'],

            // ---------- Atributos de Productos ----------
            ['name' => 'view_any_product_attributes', 'display_name' => 'Listar atributos'],
            ['name' => 'create_product_attributes', 'display_name' => 'Crear atributos'],
            ['name' => 'update_product_attributes', 'display_name' => 'Editar atributos'],
            ['name' => 'delete_product_attributes', 'display_name' => 'Eliminar atributos'],

            // ---------- Estados y Tipos ----------
            ['name' => 'view_any_state_types', 'display_name' => 'Listar estados y tipos'],
            ['name' => 'manage_state_types', 'display_name' => 'Gestionar estados y tipos'],

            // ---------- Pedidos (Backoffice) ----------
            ['name' => 'view_any_orders', 'display_name' => 'Listar todos los pedidos'],
            ['name' => 'view_orders', 'display_name' => 'Ver detalle de pedido'],
            ['name' => 'update_orders', 'display_name' => 'Editar pedidos'],
            ['name' => 'delete_orders', 'display_name' => 'Eliminar pedidos'],

            // ---------- Envíos ----------
            ['name' => 'view_any_shipments', 'display_name' => 'Listar todos los envíos'],
            ['name' => 'view_shipments', 'display_name' => 'Ver detalle de envío'],
            ['name' => 'update_shipments', 'display_name' => 'Editar envíos'],
            ['name' => 'delete_shipments', 'display_name' => 'Eliminar envíos'],

            // ---------- Pagos ----------
            ['name' => 'view_any_payments', 'display_name' => 'Listar todos los pagos'],
            ['name' => 'view_payments', 'display_name' => 'Ver detalle de pago'],

            // ---------- Ofertas ----------
            ['name' => 'view_any_offers', 'display_name' => 'Listar ofertas'],
            ['name' => 'manage_offers', 'display_name' => 'Gestionar ofertas'],

            // ---------- Carritos ----------
            ['name' => 'view_any_carts', 'display_name' => 'Listar carritos'],
            ['name' => 'manage_cart_details', 'display_name' => 'Gestionar detalles de carrito'],

            // ---------- Proveedores ----------
            ['name' => 'view_any_providers', 'display_name' => 'Listar proveedores'],
            ['name' => 'view_providers', 'display_name' => 'Ver proveedores'],
            ['name' => 'create_providers', 'display_name' => 'Crear proveedores'],
            ['name' => 'update_providers', 'display_name' => 'Editar proveedores'],
            ['name' => 'delete_providers', 'display_name' => 'Eliminar proveedores'],

            // ---------- Configuración ----------
            ['name' => 'manage_settings', 'display_name' => 'Gestionar configuración'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                ['display_name' => $perm['display_name']]
            );
        }

        // =============================================
        // 2. DEFINICIÓN DE ROLES
        // =============================================
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrador'],
            ['name' => 'admin', 'display_name' => 'Administrador'],
            ['name' => 'logistics', 'display_name' => 'Logística / Almacén'],
            ['name' => 'support', 'display_name' => 'Atención al Cliente'],
            ['name' => 'client', 'display_name' => 'Cliente Registrado'],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                ['display_name' => $roleData['display_name']]
            );
        }

        // =============================================
        // 3. ASIGNACIÓN DE PERMISOS POR ROL
        // =============================================

        /** @var Role $superAdmin */
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->syncPermissions(Permission::all()); // Todos los permisos

        /** @var Role $admin */
        $admin = Role::where('name', 'admin')->first();
        $admin->syncPermissions([
            // TODO excepto eliminar roles (y quizás eliminar configuraciones críticas)
            'view_any_own_orders',
            'view_own_order',
            'view_own_cart',
            'view_any_own_payments',
            'view_any_roles',
            'create_roles',
            'update_roles',
            'view_any_users',
            'create_users',
            'update_users',
            'delete_users',
            'view_any_products',
            'view_product',
            'create_products',
            'update_products',
            'delete_products',
            'view_any_product_attributes',
            'create_product_attributes',
            'update_product_attributes',
            'delete_product_attributes',
            'view_any_state_types',
            'manage_state_types',
            'view_any_orders',
            'view_orders',
            'update_orders',
            'delete_orders',
            'view_any_shipments',
            'view_shipments',
            'update_shipments',
            'delete_shipments',
            'view_any_payments',
            'view_payments',
            'view_any_offers',
            'manage_offers',
            'view_any_carts',
            'manage_cart_details',
            'view_any_providers',
            'view_providers',
            'create_providers',
            'update_providers',
            'delete_providers',
        ]);

        /** @var Role $logistics */
        $logistics = Role::where('name', 'logistics')->first();
        $logistics->syncPermissions([
            // Cliente
            'view_any_own_orders',
            'view_own_order',
            'view_own_cart',
            'view_any_own_payments',
            // Catálogo (solo lectura)
            'view_any_products',
            'view_product',
            'view_any_state_types',
            // Pedidos (lectura + actualización de estado)
            'view_any_orders',
            'view_orders',
            'update_orders',
            // Envíos (gestión completa)
            'view_any_shipments',
            'view_shipments',
            'update_shipments',
            // Pagos (lectura)
            'view_any_payments',
            'view_payments',
            // Ofertas (lectura)
            'view_any_offers',
        ]);

        /** @var Role $support */
        $support = Role::where('name', 'support')->first();
        $support->syncPermissions([
            // Cliente
            'view_any_own_orders',
            'view_own_order',
            'view_own_cart',
            'view_any_own_payments',
            // Usuarios (solo lectura)
            'view_any_users',
            // Catálogo (solo lectura)
            'view_any_products',
            'view_product',
            'view_any_state_types',
            // Pedidos (lectura + poder cancelar/actualizar estado)
            'view_any_orders',
            'view_orders',
            'update_orders',
            // Envíos (solo lectura)
            'view_any_shipments',
            'view_shipments',
            // Pagos (solo lectura)
            'view_any_payments',
            'view_payments',
            // Ofertas (solo lectura)
            'view_any_offers',
            // Carritos (solo lectura)
            'view_any_carts',
        ]);

        /** @var Role $client */
        $client = Role::where('name', 'client')->first();
        $client->syncPermissions([
            'view_any_own_orders',
            'view_own_order',
            'view_own_cart',
            'view_any_own_payments',
        ]);
    }
}
