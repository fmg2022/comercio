<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayPalService
{
  private function getAccessToken()
  {
    $response = Http::withBasicAuth(
      config('paypal.sandbox.client_id'),
      config('paypal.sandbox.client_secret')
    )->asForm()->post(
      config('paypal.sandbox.base_url') . '/v1/oauth2/token',
      [
        'grant_type' => 'client_credentials'
      ]
    );

    return $response->json()['access_token'];
  }

  // public function createOrder(array $products, float $taxTotal = 0)
  // {
  //   $token = $this->getAccessToken();
  //   $total = 0;
  //   $items = collect($products)->map(function ($product) use (&$total) {
  //     $subTotal = number_format($product['price'] * $product['quantity'], 2, '.', '');
  //     $total += $subTotal;
  //     return [
  //       'name' => $product['name'],
  //       'description' => $product['description'],
  //       'quantity' => (string) $product['quantity'],
  //       'unit_amount' => [
  //         'currency_code' => 'USD',
  //         'value' => $subTotal,
  //       ],
  //     ];
  //   })->toArray();
  //   dd(number_format($total + $taxTotal, 2, '.', ''), number_format($taxTotal, 2, '.', '') + $total, number_format($total, 2, '.', ''));
  //   return Http::withToken($token)
  //     ->post(config('paypal.sandbox.base_url') . "/v2/checkout/orders", [
  //       'intent' => 'CAPTURE',
  //       'purchase_units' => [
  //         [
  //           'amount' => [
  //             'currency_code' => 'USD',
  //             'value' => number_format($total + $taxTotal, 2, '.', ''),
  //             'breakdown' => [
  //               'item_total' => [
  //                 'currency_code' => 'USD',
  //                 'value' => number_format($total, 2, '.', ''),
  //               ],
  //               'tax_total' => [
  //                 'currency_code' => 'USD',
  //                 'value' => number_format($taxTotal, 2, '.', '')
  //               ]
  //             ]
  //           ],
  //           'items' => $items
  //         ]
  //       ],
  //       'application_context' => [
  //         'return_url' => route('paypal.success'),
  //         'cancel_url' => route('paypal.cancel'),
  //       ]
  //     ])
  //     ->json();
  // }

  public function createOrder(array $products, float $taxPercent = 21)
  {
    $token = $this->getAccessToken();

    $subtotal = 0;

    $items = collect($products)->map(function ($product) use (&$subtotal) {
      $finalPrice = $product['price'] - $product['discount'];

      $lineTotal = $finalPrice * $product['quantity'];
      $subtotal += $lineTotal;

      return [
        'name' => $product['name'],
        'description' => $product['description'],
        'quantity' => (string) $product['quantity'],
        'unit_amount' => [
          'currency_code' => 'USD',
          'value' => number_format($finalPrice, 2, '.', ''),
        ],
      ];
    })->toArray();

    $taxTotal = ($subtotal * $taxPercent) / 100;
    $grandTotal = $subtotal + $taxTotal;

    return Http::withToken($token)
      ->post(config('paypal.sandbox.base_url') . '/v2/checkout/orders', [
        'intent' => 'CAPTURE',
        'purchase_units' => [
          [
            'amount' => [
              'currency_code' => 'USD',
              'value' => number_format($grandTotal, 2, '.', ''),
              'breakdown' => [
                'item_total' => [
                  'currency_code' => 'USD',
                  'value' => number_format($subtotal, 2, '.', ''),
                ],
                'tax_total' => [
                  'currency_code' => 'USD',
                  'value' => number_format($taxTotal, 2, '.', ''),
                ]
              ]
            ],
            'items' => $items
          ]
        ],
        'application_context' => [
          'return_url' => route('paypal.success'),
          'cancel_url' => route('paypal.cancel'),
        ]
      ])
      ->json();
  }

  public function captureOrder(string $orderId)
  {
    $token = $this->getAccessToken();
    $url = rtrim(config('paypal.sandbox.base_url'), '/');

    $response = Http::withToken($token)
      ->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
      ])
      ->send(
        'POST',
        "{$url}/v2/checkout/orders/{$orderId}/capture",
        [
          'json' => new \stdClass()
        ]
      );

    return $response->json();
  }
}
