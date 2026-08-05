<?php

return [
  'sidebar' => [
    [
      'name' => 'Dashboard',
      'icon' => 'dashboard',
      'route' => 'client.dashboard',
      'permission' => null,
    ],
    [
      'name' => 'Dashboard Admin',
      'icon' => 'dashboard2',
      'route' => 'admin.dashboard',
      'permission' => 'view_any_roles',
    ],
    [
      'name' => 'Sección de Usuario',
      'route' => '',
      'permission' => null,
    ],
    [
      'name' => 'Mi Carrito',
      'icon' => 'cart',
      'route' => 'my.cart.index',
      'permission' => 'view_own_cart',
    ],
    [
      'name' => 'Mis Ordenes',
      'icon' => 'order',
      'route' => 'my.orders.index',
      'permission' => 'view_any_own_orders',
    ],
    [
      'name' => 'Mis Pagos',
      'icon' => 'payment',
      'route' => 'my.payments.index',
      'permission' => 'view_any_own_payments',
    ],
    [
      'name' => 'Sección Administrativa',
      'route' => '',
      'permission' => 'view_any_products',
    ],
    [
      'name' => 'Ofertas',
      'icon' => 'offer',
      'route' => 'offers.index',
      'permission' => 'view_any_offers',
    ],
    [
      'name' => 'Ordenes',
      'icon' => 'order',
      'route' => 'orders.index',
      'permission' => 'view_any_orders',
    ],
    [
      'name' => 'Pagos',
      'icon' => 'payment',
      'route' => 'payments.index',
      'permission' => 'view_any_payments',
    ],
    [
      'name' => 'Productos',
      'icon' => 'product',
      'route' => 'products.index',
      'permission' => 'view_any_products',
    ],
    [
      'name' => 'Categorías',
      'icon' => 'category',
      'route' => 'categories.index',
      'permission' => 'view_any_product_attributes',
    ],
    [
      'name' => 'Marcas',
      'icon' => 'brand',
      'route' => 'brands.index',
      'permission' => 'view_any_product_attributes',
    ],
    [
      'name' => 'Estados y Tipos',
      'icon' => 'states-types',
      'route' => 'states-types.index',
      'permission' => 'view_any_state_type',
    ],
    [
      'name' => 'Carritos',
      'icon' => 'cart',
      'route' => 'carts.index',
      'permission' => 'view_any_carts',
    ],
    [
      'name' => 'Proveedores',
      'icon' => 'provider',
      'route' => 'providers.index',
      'permission' => 'view_any_providers',
    ],
    [
      'name' => 'Usuarios',
      'icon' => 'users',
      'route' => 'users.index',
      'permission' => 'view_any_users',
    ],
    [
      'name' => 'Roles y Permisos',
      'icon' => 'role',
      'route' => 'roles.index',
      'permission' => 'view_any_roles',
    ],
    [
      'name' => 'Sección de Soporte',
      'route' => '',
      'permission' => null,
    ],
    [
      'name' => 'Ayuda',
      'icon' => 'support',
      'route' => '#',
      'permission' => null,
    ],
    [
      'name' => 'Configuración',
      'icon' => 'config',
      'route' => 'site.settings',
      'permission' => 'manage_settings',
    ],
  ],
];
