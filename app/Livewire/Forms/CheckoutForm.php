<?php

namespace App\Livewire\Forms;

use App\DTOs\CheckoutDTO;
use App\Services\CheckoutService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CheckoutForm extends Form
{
    #[Validate('required|exists:carts,id')]
    public int $cartId;
    #[Validate('nullable|exists:addresses,id|required_if:deliveryMethod,shipping')]
    public ?int $addressId = null;
    #[Validate('nullable|string')]
    public ?string $notes = null;
    #[Validate('required|string|in:shipping,pickup')]
    public string $deliveryMethod = 'pickup';
    #[Validate('required|string|in:mercadopago,paypal')]
    public string $paymentMethod = 'mercadopago';


    public function messages()
    {
        return [
            'cartId.required' => 'Debes seleccionar un carrito.',
            'cartId.exists' => 'El carrito seleccionado no existe.',
            'addressId.exists' => 'La dirección seleccionada no existe.',
            'addressId.required_if' => 'Debes seleccionar una dirección para el envío.',
            'deliveryMethod.required' => 'Debes seleccionar un método de entrega.',
            'deliveryMethod.in' => 'El método de entrega seleccionado no es válido.',
            'paymentMethod.required' => 'Debes seleccionar un método de pago.',
            'paymentMethod.in' => 'El método de pago seleccionado no es válido.',
        ];
    }

    public function save()
    {
        $this->validate();
        $user = auth()->user();

        if ($this->deliveryMethod === 'shipping' && $this->addressId === null) {
            $this->addError('addressId', 'Debes seleccionar una dirección para el envío.');
            return;
        }

        $data = CheckoutDTO::fromArray($this->validate());
        $result = app(CheckoutService::class)->checkout($user, $data);

        $orderId = $result['order'];
        $paymentId = $result['payment'];
        $shippingId = $result['shipping'];

        return match ($this->paymentMethod) {
            'mercadopago' => redirect()->route(
                'checkout.process',
                [
                    'order' => $orderId,
                    'payment' => $paymentId,
                    'shipping' => $shippingId,
                ]
            ),

            'paypal' => redirect()->route(
                'paypal.checkout',
                [
                    'order' => $orderId,
                    'payment' => $paymentId,
                    'shipping' => $shippingId,
                ]
            ),
        };
    }
}
