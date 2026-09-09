<?php

namespace App\Services;

use App\Models\ShippingRate;

class ShippingCostService
{
  public function calculateShippingCost(float $distance): array
  {
    $rate = ShippingRate::query()
      ->where('is_active', true)
      ->where('min_distance', '<=', $distance)
      ->where('max_distance', '>', $distance)
      ->select('id', 'cost', 'min_distance', 'max_distance')
      ->first();

    if (!$rate) {
      return [
        'is_feasible' => false,
        'rate' => null,
      ];
    }

    return [
      'is_feasible' => true,
      'rate' => $rate,
    ];
  }
}
