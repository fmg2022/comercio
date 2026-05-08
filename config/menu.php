<?php

return [
  'sidebar' => [
    [
      'name' => 'Dashboard',
      'icon' => 'dashboard',
      'route' => 'dashboard.index',
      'permission' => null,
    ],
    [
      'name' => 'Sección de Usuario',
      'route' => '',
      'permission' => null,
    ],
    [
      'name' => 'Mis Direcciones',
      'icon' => 'address',
      'route' => 'my.addresses.index',
      'permission' => 'list my_section',
    ],
    [
      'name' => 'Mis Ordenes',
      'icon' => 'order',
      'route' => 'my.orders.index',
      'permission' => 'list my_section',
    ],
    [
      'name' => 'Mis Pagos',
      'icon' => 'payment',
      'route' => 'my.payments.index',
      'permission' => 'list my_section',
    ],
    [
      'name' => 'Mi Carrito',
      'icon' => 'cart',
      'route' => 'my.cart.index',
      'permission' => 'list my_section',
    ],
    [
      'name' => 'Sección Administrativa',
      'route' => '',
      'permission' => 'list products',
    ],
    [
      'name' => 'Ordenes',
      'icon' => 'order',
      'route' => 'orders.index',
      'permission' => 'list orders',
    ],
    [
      'name' => 'Pagos',
      'icon' => 'payment',
      'route' => 'payments.index',
      'permission' => 'list payments',
    ],
    [
      'name' => 'Productos',
      'icon' => 'product',
      'route' => 'products.index',
      'permission' => 'list products',
    ],
    [
      'name' => 'Categorías',
      'icon' => 'category',
      'route' => 'categories.index',
      'permission' => 'list product-attributes',
    ],
    [
      'name' => 'Marcas',
      'icon' => 'brand',
      'route' => 'brands.index',
      'permission' => 'list product-attributes',
    ],
    [
      'name' => 'Estados y Tipos',
      'icon' => 'states-types',
      'route' => 'states-types.index',
      'permission' => 'list state-type-tables',
    ],
    [
      'name' => 'Carritos',
      'icon' => 'cart',
      'route' => 'carts.index',
      'permission' => 'manage carts-details',
    ],
    [
      'name' => 'Proveedores',
      'icon' => 'provider',
      'route' => 'providers.index',
      'permission' => 'manage providers',
    ],
    [
      'name' => 'Usuarios',
      'icon' => 'users',
      'route' => 'users.index',
      'permission' => 'list users',
    ],
    [
      'name' => 'Direcciones',
      'icon' => 'address',
      'route' => 'addresses.index',
      'permission' => 'list addresses',
    ],
    [
      'name' => 'Roles y Permisos',
      'icon' => 'role',
      'route' => 'roles.index',
      'permission' => 'list roles',
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
  ],
];
