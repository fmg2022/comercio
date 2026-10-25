<?php

use App\Livewire\Forms\CheckoutForm;
use App\Services\ShippingCalculationService;
use App\Exceptions\{CheckoutException, InsufficientStockException, RouteCalculationException, ShippingNotFeasibleException};
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public \App\Models\Cart $cart;
    public CheckoutForm $checkoutForm;

    public bool $isShippingFeasible = false;
    public ?string $shippingMessage = null;

    public array $quantities = [],
        $inputErrors = [];

    public float $total = 0.0,
        $tax = 0.0,
        $shippingCost = 0.0,
        $distanceKm = 0.0;

    protected function rules(int $stock)
    {
        return [
            'quantity' => 'required|integer|min:1|max:' . $stock,
        ];
    }

    public function messages()
    {
        return [
            'quantity.max' => 'Stock insuficiente',
            'quantity.min' => 'Cantidad minima es 1',
        ];
    }

    #[Computed]
    public function addresses()
    {
        return auth()->user()->addresses()->orderByDesc('is_default')->get();
    }

    public function mount()
    {
        $this->cart = auth()
            ->user()
            ->cart->load(['products.brand', 'products.category']);
        $this->checkoutForm->cartId = $this->cart->id;

        foreach ($this->cart->products as $product) {
            $this->quantities[$product->id] = $product->pivot->quantity;
        }

        $this->checkoutForm->addressId = auth()->user()->defaultAddress?->id;
        $this->calculateTotal();
    }

    public function updatedQuantities($value, $key)
    {
        $id = (int) str_replace('quantities.', '', $key);
        $product = $this->cart->products()->find($id);

        if (!$product) {
            return;
        }

        // Validaciones
        $validator = Validator::make(['quantity' => $value], $this->rules($product->stock), $this->messages());

        if ($validator->fails()) {
            $this->inputErrors[$id] = $validator->errors()->first('quantity');
            $this->quantities[$id] = $product->pivot->quantity;
            return;
        }

        unset($this->inputErrors[$id]);
        $this->updateQuantity($id);
    }

    public function updateQuantity(int $id): void
    {
        $product = $this->cart->products->find($id);

        if (!$product) {
            return;
        }

        $quantity = $this->quantities[$id] ?? $product->pivot->quantity;

        if ($quantity < 1 || $quantity > $product->stock) {
            return;
        }

        $this->cart->updateProduct($id, $quantity);
        $this->refreshCart();
    }

    public function updatedCheckoutFormAddressId(): void
    {
        $this->calculateTotal();
    }

    public function updatedCheckoutFormDeliveryMethod(): void
    {
        if ($this->checkoutForm->deliveryMethod === 'shipping' && !$this->isShippingFeasible) {
            $this->checkoutForm->deliveryMethod = 'pickup';
        }

        $this->calculateTotal();
    }

    public function saveCheckout()
    {
        try {
            $this->checkoutForm->save();
        } catch (InsufficientStockException $e) {
            // Quitar el producto sin stock del carrito
            $this->cart->products()->detach($e->productId);
            $this->refreshCart();
            $this->dispatch('notification', message: $e->getMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('notification', message: $e->getMessage(), type: 'error');
        }
    }

    public function calculateTotal(): void
    {
        $this->tax = ($this->cart->total * floatval(config('commerce.tax_rate'))) / 100;

        $this->shippingCost = 0.0;
        $this->distanceKm = 0.0;
        $this->isShippingFeasible = false;
        $this->shippingMessage = null;

        $address = auth()->user()->addresses()->find($this->checkoutForm->addressId);

        if (!$address) {
            $this->shippingMessage = 'La dirección seleccionada no existe.';
            return;
        }

        try {
            $shipping = app(ShippingCalculationService::class)->calculateForCheckout($address);

            $this->isShippingFeasible = true;
            $this->distanceKm = $shipping['distance_km'];

            if ($this->checkoutForm->deliveryMethod === 'shipping') {
                $this->shippingCost = (float) $shipping['shipping_cost'];
            }
            $this->updateOrderTotal();
        } catch (CheckoutException $e) {
            $this->shippingMessage = $e->getMessage();
            $this->addError('checkoutForm.addressId', $e->getMessage());
        } catch (RouteCalculationException $e) {
            $this->shippingMessage = $e->getMessage();
            $this->addError('checkoutForm.addressId', $e->getMessage());
        } catch (ShippingNotFeasibleException $e) {
            $this->shippingMessage = 'La dirección seleccionada se encuentra fuera de nuestra zona de envío.';
            $this->distanceKm = $e->distance_km;
        }
    }

    protected function updateOrderTotal(): void
    {
        $this->total = $this->cart->total + $this->tax + $this->shippingCost;
    }

    public function removeProduct(int $productId): void
    {
        $this->cart->products()->detach($productId);
        unset($this->quantities[$productId], $this->inputErrors[$productId]);
        $this->refreshCart();
    }

    public function clearCart(): void
    {
        $this->cart->detachProduct([]);
        $this->quantities = [];
        $this->inputErrors = [];
        $this->refreshCart();
    }

    protected function refreshCart(): void
    {
        if ($this->cart) {
            $this->cart->refresh();
            $this->cart->load('products.brand');
            $this->calculateTotal();
            $this->dispatch('cart-updated');
        }
    }

    public function clearProductError($id)
    {
        unset($this->inputErrors[$id]);
    }
};
?>

<article class="px-4 py-7 max-w-2xl mx-auto md:px-6 lg:px-8 lg:max-w-7xl">

  <div class="flex items-center justify-between gap-4 sm:gap-8">
    <h1 class="text-3xl text-gray-900 font-bold tracking-tight sm:text-4xl">🛒 Carrito de compras</h1>

    <button wire:click="clearCart" @class([
        'px-3 py-2 font-semibold rounded-md',
        'bg-slate-400 text-gray-100 cursor-not-allowed' =>
            $cart->products->count() === 0,
        'text-white bg-red-700 active:bg-red-600 cursor-pointer' =>
            $cart->products->count() !== 0,
    ]) @disabled($cart->products->count() === 0)>
      Limpiar Carrito
    </button>

  </div>

  <section class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
    <div class="max-h-screen overflow-y-auto lg:col-span-7 lg:max-h-275" style="scrollbar-color: #62748e transparent">
      <ul role="list" class="border-y divide-y divide-gray-200 border-gray-200 sm:pe-3">
        @forelse ($cart->products as $item)
          <li class="py-6 flex gap-4 sm:py-10">
            <div class="grid place-items-center shrink-0">
              <img src="{{ asset('images/products/' . $item->image) }}" alt="{{ $item->shortDescription }}"
                class="size-24 rounded-md object-cover sm:size-40" />
            </div>
            <div class="pe-4 flex flex-1 flex-col gap-2 justify-around sm:ps-4">
              <div>
                <div class="flex gap-4 justify-between">
                  <x-buttons.link href="{{ route('product.show', $item->id) }}"
                    class="text-lg font-medium text-gray-900 hover:text-purple-800">
                    {{ $item->name }}
                  </x-buttons.link>

                  <button wire:click="removeProduct({{ $item->id }})" wire:loading.attr="disabled"
                    class="p-0.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                    <span wire:loading.remove wire:target="removeProduct({{ $item->id }})" class="cursor-pointer">
                      <x-icons.x class="size-6" />
                    </span>
                    <span wire:loading wire:target="removeProduct({{ $item->id }})"
                      class="text-xs text-gray-600
                            cursor-not-allowed">
                      Eliminando...
                    </span>
                  </button>

                </div>
                <div class="space-y-2 text-gray-500 text-sm sm:text-base">
                  <p>
                    <span class="font-medium">{{ $item->brand->name }}</span>
                    <span class="ms-2 ps-2 border-s border-gray-300">
                      {{ $item->category->name }}
                    </span>
                  </p>
                  <p>
                    Precio unitario: <span class="font-medium">${{ number_format($item->price, 2, ',', '.') }}</span>
                  </p>
                </div>
              </div>

              <section class="flex flex-1 items-end justify-between text-sm">
                <div x-data="{
                    errorTimeout: null
                }" x-init=" $watch('$wire.inputErrors[{{ $item->id }}]', value => {
                     if (value) {
                         clearTimeout(errorTimeout);
                         errorTimeout = setTimeout(() => {
                             $wire.clearProductError({{ $item->id }});
                         }, 4000);
                     }
                 })" class="relative w-full max-w-16">
                  <label class="grid grid-cols-1">
                    <input type="number" name="quantity" min="1" max="{{ $item->stock }}"
                      wire:model.live.debounce.300ms="quantities.{{ $item->id }}"
                      wire:key="quantity-{{ $item->id }}"
                      class="px-3 py-1.5 text-base text-gray-900 rounded-md outline outline-offset-1 transition-colors duration-200 sm:text-sm {{ isset($inputErrors[$item->id]) ? 'outline-red-500  ring-2 ring-red-500' : 'outline-gray-300' }}" />
                  </label>
                  <div x-show="$wire.inputErrors[{{ $item->id }}]" x-transition.opacity
                    class="absolute top-full left-0 mt-1 w-fit max-w-24 border-red-500 text-red-600 bg-red-50 text-xs text-center p-1 rounded shadow-md"
                    x-text="$wire.inputErrors[{{ $item->id }}]">
                  </div>
                </div>
                <div @class([
                    'ml-4 text-slate-900 font-medium text-lg',
                    'flex flex-col items-start justify-center' =>
                        floatval($item->pivot->discount) != 0,
                ])>
                  <p class="text-gray-600 text-base">Subtotal</p>
                  <p wire:loading wire:target="quantities.{{ $item->id }}" class="text-sm text-gray-500">
                    ⏳ Actualizando...</p>
                  <div wire:loading.remove wire:target="quantities.{{ $item->id }}">
                    <x-_partials.showPriceDiscount :item="$item" />
                  </div>
                </div>
              </section>

            </div>
          </li>
        @empty
          <li class="py-10 text-center text-2xl font-medium">Sin productos en el carrito</li>
        @endforelse
      </ul>
    </div>
    <form method="POST" wire:submit="saveCheckout"
      class="px-4 py-6 mt-16 rounded-lg bg-indigo-50 space-y-6 sm:p-6 lg:mt-0 lg:p-8 lg:col-span-5">
      @csrf
      <h2 class="text-lg font-medium text-gray-900">Resumen del pedido</h2>
      <div class="space-y-4">
        <div class="mb-8">
          <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
            Notas adicionales (Opcional)
          </label>
          <textarea id="notes" rows="3" wire:model.defer="checkoutForm.notes"
            class="block w-full rounded-md border-gray-300 shadow-sm bg-white/50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            placeholder="Ej: Prefiero retirar después de las 17hs..."></textarea>
        </div>
        <div class="mb-8">
          <label for="address_id" class="mb-4 text-lg font-medium text-gray-900">Dirección de envío</label>
          <select id="address_id" wire:model.live="checkoutForm.addressId"
            class="block w-full px-3 py-2 rounded-md bg-white/50 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @forelse ($this->addresses as $address)
              <option value="{{ $address->id }}">
                {{ $address->street }}</option>
            @empty
              <option value="" selected>Sin direcciones disponibles</option>
            @endforelse
          </select>
        </div>
        <div class="border-t border-gray-200 pt-6">
          <fieldset>
            <legend class="text-lg font-medium text-gray-900 mb-4">Método de entrega</legend>
            <div class="space-y-3">
              <label @class([
                  'relative flex items-start gap-3 rounded-lg border p-4 transition',
                  'cursor-pointer border-gray-300 hover:bg-gray-50' => $isShippingFeasible,
                  'cursor-not-allowed border-gray-200 bg-gray-100 opacity-60' => !$isShippingFeasible,
              ]) wire:loading.class="bg-black/20 bg-blur pointer-events-none"
                wire:target="distanceKm,checkoutForm.addressId">
                <input type="radio" name="delivery_method" value="shipping"
                  wire:model.live="checkoutForm.deliveryMethod"
                  class="mt-1 h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  @disabled(!$isShippingFeasible)>
                <div>
                  <p class="font-medium text-gray-900">🚚 Envío a domicilio</p>
                  @if ($isShippingFeasible)
                    <p class="mt-1 text-sm text-gray-600">Podemos realizar el envío a esta dirección.</p>
                    <p class="mt-1 text-sm text-gray-600">
                      Distancia:
                      <strong>{{ number_format($distanceKm, 2, ',', '.') }} km</strong>
                    </p>
                  @else
                    <p class="mt-1 text-sm text-red-600">
                      {{ $shippingMessage ?? 'El envío no está disponible para esta dirección.' }}
                    </p>
                  @endif
                </div>
                <span wire:loading wire:target="distanceKm,checkoutForm.addressId"
                  class="absolute top-1/2 left-1/2 -translate-1/2 font-bold animate-pulse">⏳
                  Actualizando...</span>
              </label>
              <label
                class="flex items-start gap-3 rounded-lg border border-gray-300 p-4 cursor-pointer transition hover:bg-gray-50">
                <input type="radio" name="delivery_method" value="pickup"
                  wire:model.live="checkoutForm.deliveryMethod"
                  class="mt-1 h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <div>
                  <p class="font-medium text-gray-900">🏪 Retiro en local</p>
                  <p class="mt-1 text-sm text-gray-600">Retirá tu pedido directamente en nuestro local.</p>
                  <p class="mt-2 text-sm text-gray-600">
                    Dirección:
                    <strong>{{ config('app_settings.address') ?? 'Dirección no configurada' }}</strong>
                  </p>
                  <p class="mt-1 text-sm text-gray-600">
                    Horario:
                    <strong>{{ config('app_settings.pickup_hours') ?? 'Consultar horario' }}</strong>
                  </p>
                </div>
              </label>
            </div>
          </fieldset>
        </div>
        <div class="flex items-center justify-between text-base">
          <p class="text-gray-600">Subtotal</p>
          <p wire:loading class="text-gray-900 animate-pulse">⏳ Actualizando...</p>
          <p wire:loading.remove class="font-medium text-gray-900">
            ${{ number_format($cart->total, 2, ',', '.') }}
          </p>
        </div>
        <div class="pt-4 flex items-center justify-between text-base border-t border-gray-200">
          <p class="text-gray-600">IVA ({{ floatval(config('commerce.tax_rate')) }}%)</p>
          <p wire:loading class="text-gray-900 animate-pulse">⏳ Actualizando...</p>
          <p wire:loading.remove class="font-medium text-gray-900">
            ${{ number_format($tax, 2, ',', '.') }}
          </p>
        </div>
        <div class="pt-4 flex items-center justify-between text-base border-t border-gray-200">
          <p class="text-gray-600">
            {{ $checkoutForm->deliveryMethod === 'pickup' ? 'Costo de retiro' : 'Costo de envío' }}
          </p>
          <p wire:loading wire:target="calculateTotal" class="text-gray-900 animate-pulse">⏳ Actualizando...</p>
          <div wire:loading.remove wire:target="calculateTotal">
            @if ($checkoutForm->deliveryMethod === 'pickup')
              <p class="font-medium text-green-700">Gratis</p>
            @else
              <p class="font-medium text-gray-900">
                ${{ number_format($shippingCost, 2, ',', '.') }}
              </p>
            @endif
          </div>
        </div>
        <div class="pt-4 flex items-center justify-between text-lg font-medium text-gray-900 border-t border-gray-200">
          <p>Total del pedido</p>
          <p wire:loading class="animate-pulse">⏳ Actualizando...</p>
          <p wire:loading.remove>
            ${{ number_format($total, 2, ',', '.') }}
          </p>
        </div>
        <div class="pt-4 border-t border-gray-200">
          <fieldset>
            <legend class="text-sm font-medium text-gray-900 mb-3">Método de pago</legend>
            <div class="space-y-3">
              <label class="flex items-center gap-3 cursor-pointer">
                <input type="radio" name="payment_method" value="mercadopago"
                  wire:model="checkoutForm.paymentMethod"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" checked>
                <span class="text-sm text-gray-700">💳 <b>Mercado Pago</b> (Tarjeta / Transferencia)</span>
              </label>
              <label class="flex items-center gap-3 cursor-pointer">
                <input type="radio" name="payment_method" value="paypal" wire:model="checkoutForm.paymentMethod"
                  class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-700">💳 <b>PayPal</b> (Tarjeta / Transferencia) solo en Dolares</span>
              </label>
            </div>
          </fieldset>
        </div>
      </div>
      <button type="submit"
        class="w-full rounded-md bg-indigo-600 px-6 py-3 text-center text-base font-medium text-white shadow-xs hover:bg-indigo-700 cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed"
        wire:target="saveCheckout" wire:loading.attr="disabled">
        <span wire:loading wire:loading.flex wire:target="saveCheckout" class="gap-4 items-center justify-center">
          <x-icons.animate.spinner class="size-6" />
          Procesando...
        </span>
        <span wire:loading.remove wire:target="saveCheckout">Proceder al pago</span>
      </button>
      <div class="flex justify-center gap-2 text-sm text-gray-500">
        <span>o</span>
        <x-buttons.link href="{{ route('home') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
          Continue Comprando
          <span aria-hidden="true"> &rarr;</span>
        </x-buttons.link>
      </div>
    </form>
  </section>
</article>
