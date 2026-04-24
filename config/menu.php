<?php

return [
  'sidebar' => [
    [
      'name' => 'Dashboard',
      'icon' => 'dashboard',
      'route' => 'dashboard',
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
      'route' => 'addresses.myIndex',
      'permission' => 'list my_section',
    ],
    [
      'name' => 'Mis Ordenes',
      'icon' => 'order',
      'route' => 'orders.myIndex',
      'permission' => 'list my_section',
    ],
    [
      'name' => 'Sección Administrativa',
      'route' => '',
      'permission' => 'list orders',
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
      'permission' => 'list product',
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
      'name' => 'Estados y Tipos',
      'icon' => 'states-types',
      'route' => 'states-types.index',
      'permission' => 'list state-type-tables',
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
