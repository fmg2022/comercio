<?php

use App\Models\Cart;
use Livewire\Component;

new class extends Component {
    public Cart $cart;

    public function mount()
    {
        $this->cart = auth()->user()->cart->load('products.brand');
    }

    public function removeProduct(int $productId)
    {
        $this->cart->products()->detach($productId);

        $this->dispatch('cart-updated');
    }
};
?>

<div>
  @if ($cart->products->isNotEmpty())
    <div class="mt-8 flow-root">
      <ul role="list" class="-my-6 divide-y divide-gray-200">
        @foreach ($this->cart->products as $item)
          <li wire:key="{{ $item->id }}" class="flex py-6">
            <div class="size-24 shrink-0 overflow-hidden rounded-md border border-gray-200">
              <img src="{{ asset('images/products/' . $item->image) }}" alt="{{ $item->description }}"
                class="size-full object-cover" />
            </div>
            <div class="ml-4 flex flex-1 flex-col">
              <div>
                <div class="flex justify-between gap-4 text-base font-medium text-gray-900">
                  <div>
                    <h3>
                      <a href="{{ route('product.show', $item->id) }}">{{ $item->name }}</a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $item->brand->name }}</p>
                  </div>
                  <div @class([
                      'text-slate-800',
                      'flex flex-col items-start justify-center' =>
                          floatval($item->pivot->discount) != 0,
                  ])>
                    <x-_partials.showPriceDiscount :item="$item" />
                  </div>
                </div>
              </div>
              <div class="flex flex-1 items-end justify-between text-sm">
                <p class="text-gray-500">Cantidad: {{ $item->pivot->quantity }}</p>
                <button wire:click="removeProduct({{ $item->id }})" wire:loading.attr="disabled"
                  class="font-medium">
                  <span wire:loading.remove wire:target="removeProduct({{ $item->id }})"
                    class="text-indigo-600
                            hover:text-indigo-500 cursor-pointer">
                    <x-icons.trash class="size-6" />
                  </span>
                  <span wire:loading wire:target="removeProduct({{ $item->id }})"
                    class="text-xs text-gray-600
                            cursor-not-allowed">
                    Eliminando...
                  </span>
                </button>
              </div>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  @else
    <h4 class="absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 font-bold text-2xl text-center">
      Sin productos en el carrito
    </h4>
  @endif
</div>
