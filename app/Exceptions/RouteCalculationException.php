<?php

namespace App\Exceptions;

class RouteCalculationException extends CheckoutException
{
    public function __construct(
        string $message = 'No fue posible calcular la distancia de la ruta.'
    ) {
        parent::__construct($message);
    }
}
