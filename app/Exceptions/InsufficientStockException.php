<?php

namespace App\Exceptions;

class InsufficientStockException extends CheckoutException
{
    public function __construct(
        public readonly int $productId,
        public readonly string $productName,
        public readonly int $available,
        public readonly int $requested,
    ) {
        parent::__construct("Sin productos en stock");
    }
}
