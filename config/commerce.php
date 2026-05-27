<?php

return [
  'tax_rate' => env('COMMERCE_TAX_RATE', 21.0),
  'start_date' => env('COMMERCE_START_DATE', '2024-10-01'),

  'mercadopago' => [
    'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
    'public_key' => env('MERCADO_PAGO_PUBLIC_KEY'),
    'currency_id' => env('MERCADO_PAGO_CURRENCY_ID'),
    'webhook_secret' => env('MERCADO_PAGO_WEBHOOK_SECRET'),
  ],
];
