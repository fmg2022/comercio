<?php

use App\Models\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public Cart $cart;

    public function mount()
    {
        $this->cart = auth()->user()->cart->load('products');
    }

    #[On('cart-updated')]
    public function refreshSummary()
    {
        $this->cart->load('products');
    }
};
?>

<div class="flex items-center justify-between text-base font-semibold">
  @if ($this->cart->products->isNotEmpty())
    <p class="text-gray-600">Subtotal</p>
    <p wire:loading class="text-gray-900 animate-pulse">Calculando...</p>
    <p wire:loading.remove class="font-medium text-gray-900">
      ${{ number_format($this->cart->total, 2, ',', '.') }}
    </p>
  @endif
</div>
