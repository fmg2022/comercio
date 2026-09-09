<?php

namespace App\DTOs;

use Illuminate\Http\Client\Request;

final readonly class CheckoutDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $cartId,
        public ?int $addressId,
        public string $deliveryMethod,
        public string $paymentMethod,
        public ?string $notes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            cartId: $data['cartId'],
            addressId: $data['addressId'],
            deliveryMethod: $data['deliveryMethod'],
            paymentMethod: $data['paymentMethod'],
            notes: $data['notes'],
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'cartId' => $this->cartId,
            'addressId' => $this->addressId,
            'deliveryMethod' => $this->deliveryMethod,
            'paymentMethod' => $this->paymentMethod,
            'notes' => $this->notes,
        ];
    }
}
