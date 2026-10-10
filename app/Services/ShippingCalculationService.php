<?php

namespace App\Services;

use App\Exceptions\{CheckoutException, RouteCalculationException, ShippingNotFeasibleException};
use App\Models\Address;
use Illuminate\Support\Facades\Log;

class ShippingCalculationService
{
    public function __construct(
        private StoreLocationService $storeLocationService,
        private RoutingService $routingService,
        private ShippingCostService $shippingCostService,
    ) {}

    public function calculatePreview(Address $address)
    {
        return $this->buildShippingResult(
            address: $address,
            useCache: true
        );
    }

    public function calculateForCheckout(Address $address): array
    {
        return $this->buildShippingResult(
            address: $address,
            useCache: false
        );
    }

    private function buildShippingResult(Address $address, bool $useCache = true): array
    {
        $localLocation = $this->storeLocationService->getStoreLocation();

        if (!isset($localLocation['latitude']) || !isset($localLocation['longitude'])) {
            throw new CheckoutException('No fue posible obtener la ubicación del local.');
        }

        $fromLatitude = (float) $localLocation['latitude'];
        $fromLongitude = (float) $localLocation['longitude'];

        $toLatitude = (float) $address->latitude;
        $toLongitude = (float) $address->longitude;

        if ($useCache) {
            $cacheKey = $this->buildCacheKey($fromLatitude, $fromLongitude, $toLatitude, $toLongitude);

            $route = $this->routingService->cachedDistance(
                cacheKey: $cacheKey,
                fromLatitude: $fromLatitude,
                fromLongitude: $fromLongitude,
                toLatitude: $toLatitude,
                toLongitude: $toLongitude,
            );
        } else {
            $route = $this->routingService->distance(
                fromLatitude: $fromLatitude,
                fromLongitude: $fromLongitude,
                toLatitude: $toLatitude,
                toLongitude: $toLongitude,
            );
        }

        if ($route['error']) {
            Log::error($route['message']);
            throw new RouteCalculationException('No fue posible calcular la distancia de la ruta.');
        }

        $shipping = $this->shippingCostService->calculateShippingCost($route['distance_km']);

        if (!$shipping['is_feasible']) {
            throw new ShippingNotFeasibleException(distance_km: $route['distance_km']);
        }

        return [
            'distance_km' => (float) $route['distance_km'],
            'shipping_rate_id' => $shipping['rate']->id,
            'shipping_cost' => (float) $shipping['rate']->cost,
        ];
    }

    private function buildCacheKey(string $localLatidude, string $localLongitude, float $addressLatitude, float $addressLongitude): string
    {
        return  sprintf('shipping:route:%s:%s:%s:%s', $localLatidude, $localLongitude, $addressLatitude, $addressLongitude);
    }
}
