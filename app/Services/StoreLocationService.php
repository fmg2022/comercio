<?php

namespace App\Services;

use App\Models\Setting;

class StoreLocationService
{

  public function getStoreLocation(): array
  {
    $store = Setting::query()
      ->whereIn('key', ['longitude', 'latitude'])
      ->pluck('value', 'key');

    if (!isset($store['latitude'], $store['longitude'])) {
      return [
        'latitude' => null,
        'longitude' => null,
      ];
    }

    return [
      'latitude' => (float) $store['latitude'],
      'longitude' => (float) $store['longitude'],
    ];
  }
}
