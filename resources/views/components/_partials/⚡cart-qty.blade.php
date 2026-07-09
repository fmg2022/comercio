<?php

use App\Models\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public Cart $cart;
    public string $startText = '';
    public string $endText = '';

    public function mount(string $startText = '', string $endText = '')
    {
        $this->cart = auth()->user()->cart->load('products');
        $this->startText = $startText;
        $this->endText = $endText;
    }

    #[On('cart-updated')]
    public function refreshSummary()
    {
        $this->cart->load('products');
    }
};
?>

<div>
  {{ $this->cart->products->isNotEmpty() ? "{$this->startText}" . $this->cart->quantity . "{$this->endText}" : '' }}
</div>
