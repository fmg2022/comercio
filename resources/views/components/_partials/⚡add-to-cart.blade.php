<?php

use App\Models\{Offer, Product};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{DB, Validator};
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public int $productId;
    public ?int $offerId;

    public int $quantity = 1;

    public ?string $addToCartErrorMessage = null;
    public ?string $errorMessage = null;
    public bool $inputError = false;

    protected function rules()
    {
        return [
            'quantity' => 'required|integer|min:1|max:' . ($this->product()?->stock ?? 0),
        ];
    }

    public function mount(int $productId, ?int $offerId)
    {
        $this->productId = $productId;
        $this->offerId = $offerId;
    }

    #[Computed]
    public function product()
    {
        return Product::find($this->productId);
    }

    #[Computed]
    public function offerTemplate()
    {
        return Offer::find($this->offerId)?->offerTemplate;
    }

    public function addToCart()
    {
        if (auth()->guest()) {
            $this->addToCartErrorMessage = 'Debe iniciar sesión para agregar productos.';
            return;
        }

        // Validaciones
        $validator = Validator::make(['quantity' => $this->quantity], $this->rules(), $this->messages());

        if ($validator->fails()) {
            $this->addToCartErrorMessage = $validator->errors()->first('quantity');
            return;
        }

        DB::beginTransaction();
        try {
            $cart = auth()->user()->cart;

            $item = $cart->products()->where('product_id', $this->productId)->lockForUpdate()->first();

            $existItem = $item && $item->exists();

            $offerTemplate = Offer::find($this->product->activeOffer())?->offerTemplate;
            $qty = $existItem ? $item->pivot->quantity + $this->quantity : $this->quantity;

            $discount = $offerTemplate ? $this->product->getDiscountTotal($qty, $offerTemplate->buy_qty, $offerTemplate->pay_qty, $offerTemplate->offerType->slug) : 0;

            if ($existItem) {
                $cart->updateProduct($this->productId, $qty, $discount);
            } else {
                $cart->attachProduct($this->productId, $qty, $discount);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->addToCartErrorMessage = 'Error al agregar al carrito';
        }
        $this->dispatch('cart-updated');

        $this->clearError();
        $this->reset('addToCartErrorMessage');
    }

    public function messages()
    {
        return [
            'quantity.max' => 'Stock insuficiente',
            'quantity.min' => 'Cantidad minima es 1',
        ];
    }

    public function updatedQuantity($value)
    {
        $this->reset('addToCartErrorMessage');

        if ($value < 1) {
            $this->inputError = true;
            $this->errorMessage = $this->messages()['quantity.min'] ?? 'Cantidad mínima es 1.';
            $this->quantity = 1;
        } elseif ($this->product && $value > $this->product->stock) {
            $this->inputError = true;
            $this->errorMessage = $this->messages()['quantity.max'] ?? 'Stock insuficiente.';
            $this->quantity = $this->product->stock;
        } else {
            $this->clearError();
        }
    }

    public function clearError()
    {
        $this->reset(['errorMessage', 'inputError']);
    }
    public function clearAddToCartError()
    {
        $this->reset('addToCartErrorMessage');
    }
};
?>

<section class="px-6 pt-2 pb-2 flex flex-col justify-between gap-5">
  <section class="flex justify-between items-center gap-4">
    <div class="flex flex-col items-start justify-center">
      @if ($offerId)
        <p class="flex justify-star items-center gap-3">
          @php
            $offerType = $this->offerTemplate->offerType->slug;

            if ($offerType === 'x_for_y') {
                $buy = (float) $this->offerTemplate->buy_qty;
                $newPrice = ($this->product->price * ($buy - (float) $this->offerTemplate->pay_qty)) / $buy;
            } else {
                $newPrice = $this->product->getDiscountTotal(
                    (float) $this->offerTemplate->buy_qty,
                    (float) $this->offerTemplate->buy_qty,
                    (float) $this->offerTemplate->pay_qty,
                    $offerType,
                );
            }
          @endphp
          <span class="text-lg font-bold text-slate-700">
            {!! "$" .
                number_format($this->product->price - $newPrice, 2, ',', '.') .
                ($offerType === 'x_for_y' ? '<sup>c/u</sup>' : '') !!}
          </span>
          <span class="p-1 bg-amber-400 rounded-lg text-xs">
            {{ $offerType === 'fixed'
                ? '-$' . $this->offerTemplate->pay_qty
                : ($offerType === 'percentage'
                    ? '-' . $this->offerTemplate->pay_qty * 100 . '%'
                    : $this->offerTemplate->buy_qty * 1 . 'x' . $this->offerTemplate->pay_qty * 1) }}
          </span>
        </p>
      @endif
      <p @class([
          'text-slate-600 line-through' => $offerId,
          'py-3 text-lg font-bold text-slate-700' => $offerId === null,
      ])>${{ number_format($this->product->price, 2, ',', '.') }}</p>
    </div>
    <div x-data="{ errorTimeout: null }" x-init="$watch('$wire.inputError', value => {
        if (value) {
            clearTimeout(errorTimeout);
            errorTimeout = setTimeout(() => {
                $wire.clearError();
            }, 4000);
        }
    })" class="relative w-full max-w-16">
      <label class="grid grid-cols-1">
        <input type="number" name="quantity" min="1" max="{{ $this->product?->stock }}"
          wire:model.live.debounce.250ms="quantity" wire:key="quantity-{{ $this->product?->id ?? 'non-product' }}"
          class="ps-2 pe-0.5 py-1.5 text-base text-gray-900 bg-white rounded-md outline-none transition-colors duration-200 sm:text-sm {{ $inputError ? 'border-red-500 ring-2 ring-red-500' : 'border-gray-400' }}" />
      </label>
      <div x-show="$wire.inputError" x-transition.opacity
        class="absolute top-full left-0 mt-1 w-fit max-w-24 border-red-500 text-red-600 bg-red-50 text-xs text-center p-1 rounded shadow-md"
        x-text="$wire.errorMessage">
      </div>
    </div>
  </section>
  <div x-data="{ btnErrorTimeout: null }" x-init="$watch('$wire.addToCartErrorMessage', value => {
      if (value) {
          clearTimeout(btnErrorTimeout);
          btnErrorTimeout = setTimeout(() => {
              $wire.clearAddToCartError();
          }, 3000);
      }
  })" class="relative flex flex-col items-center gap-1">
    <div x-show="$wire.addToCartErrorMessage" x-transition.opacity
      class="absolute bottom-full left-1/2 -translate-x-1/2 w-full px-3 py-1 mb-2 border border-red-500 text-red-600 bg-red-50 rounded-md text-sm text-center shadow-sm group-[.grilla]:max-w-62.5 group-[.list]:max-w-xs"
      x-text="$wire.addToCartErrorMessage">
    </div>
    <button wire:click="addToCart" wire:loading.attr="disabled"
      wire:loading.class="bg-slate-400/80 text-gray-600 font-semibold cursor-not-allowed"
      wire:loading.remove.class="bg-slate-700 text-white cursor-pointer hover:bg-slate-600"
      class="px-4 py-2 bg-slate-700 text-white rounded-md hover:bg-slate-600 cursor-pointer group-[.list]:w-xs group-[.list]:mx-auto group-[.group]:m-0 group-[.group]:w-auto">
      <span wire:loading.remove wire:target="addToCart">
        Agregar
      </span>
      <span wire:loading wire:target="addToCart">
        Agregando...
      </span>
    </button>
  </div>
</section>
