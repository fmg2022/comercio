<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeocodingService
{
  public function geocode(string $address): array
  {
    $response = Http::timeout(10)
      ->connectTimeout(5)
      ->withHeaders([
        'User-Agent' => config('app.name') . '/1.0',
        'Accept-Language' => 'es',
      ])
      ->get(config('services.nominatim.url') . '/search', [
        'q' => $address,
        'format' => 'jsonv2',
        'limit' => 1,
        'countrycodes' => config('services.nominatim.country'),
      ]);

    if ($response->failed()) {
      throw new RuntimeException(
        'No fue posible consultar el servicio de geocodificación.'
      );
    }

    $result = $response->json();

    if (
      empty($result) ||
      ! isset($result[0]['lat'], $result[0]['lon'])
    ) {
      throw new RuntimeException(
        'No se pudo encontrar la ubicación de la dirección.'
      );
    }

    return [
      'latitude' => (float) $result[0]['lat'],
      'longitude' => (float) $result[0]['lon'],
      'display_name' => $result[0]['display_name'] ?? null,
    ];
  }
}
