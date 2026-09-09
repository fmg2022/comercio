<?php

namespace App\Exceptions;

class ShippingNotFeasibleException extends CheckoutException
{
    public function __construct(
        string $message = 'El envío no está disponible para esta dirección seleccionada.',
        public readonly int $distance_km = 0,
    ) {
        parent::__construct($message);
    }
}
