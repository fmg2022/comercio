<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RoutingService
{
  public function distance(
    float $fromLatitude,
    float $fromLongitude,
    float $toLatitude,
    float $toLongitude
  ): array {
    try {
      $response = Http::timeout(10)
        ->withToken(config('services.openrouteservice.key'))
        ->post(
          'https://api.openrouteservice.org/v2/directions/driving-car',
          [
            'coordinates' => [
              [
                $fromLongitude,
                $fromLatitude,
              ],
              [
                $toLongitude,
                $toLatitude,
              ],
            ],
          ]
        );
    } catch (\Exception $e) {
      return [
        'error' => true,
        'message' => 'No fue posible comunicarse con el servicio de rutas.',
        'distance_meters' => 0.0,
        'distance_km' => 0.0,
        'duration_seconds' => 0.0,
      ];
    }

    if ($response->failed()) {
      $message = data_get($response->json(), 'error.message')
        ?? data_get($response->json(), 'error')
        ?? 'No fue posible obtener la distancia de la ruta.';

      return [
        'error' => true,
        'message' => is_string($message)
          ? $message
          : 'No fue posible obtener la distancia de la ruta.',
        'distance_meters' => 0.0,
        'distance_km' => 0.0,
        'duration_seconds' => 0.0,
      ];
    }

    $data = $response->json();

    $summary = $data['routes'][0]['summary'] ?? null;

    if (!$summary) {
      $message = data_get($response->json(), 'error.message')
        ?? data_get($response->json(), 'error')
        ?? 'No fue posible obtener la distancia de la ruta.';

      return [
        'error' => true,
        'message' => is_string($message)
          ? $message
          : 'No fue posible obtener la distancia de la ruta.',
        'distance_meters' => 0.0,
        'distance_km' => 0.0,
        'duration_seconds' => 0.0,
      ];
    }

    return [
      'error' => false,
      'distance_meters' => (float) $summary['distance'],
      'distance_km' => round((float) $summary['distance'] / 1000, 2),
      'duration_seconds' => (float) $summary['duration'],
    ];
  }

  public function cachedDistance(
    string $cacheKey,
    float $fromLatitude,
    float $fromLongitude,
    float $toLatitude,
    float $toLongitude
  ): array {
    return Cache::remember(
      $cacheKey,
      now()->addMinutes(30),
      fn() => $this->distance(
        $fromLatitude,
        $fromLongitude,
        $toLatitude,
        $toLongitude
      )
    );
  }
}
