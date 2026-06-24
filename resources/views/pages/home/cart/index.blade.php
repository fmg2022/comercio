@extends('layouts.app')

@push('scripts-app')
  <script src="{{ asset('js/index.js') }}" defer></script>
@endPush

@section('content')
  <article class="px-4 py-10 max-w-2xl mx-auto md:py-14 md:px-6 lg:px-8 lg:max-w-7xl">
    <div class="flex items-center justify-between gap-4 sm:gap-8">
      <h1 class="text-3xl text-gray-900 font-bold tracking-tight sm:text-4xl">Mi carrito</h1>
      <form action="{{ route('cart.clearCart', $cart_id) }}" method="POST" class="flex justify-center items-start">
        @csrf
        @method('DELETE')
        <button type="submit" @class([
            'px-3 py-2 font-semibold rounded-md',
            'bg-slate-400 text-gray-100 cursor-not-allowed' =>
                $cartItems->count() === 0,
            'text-white bg-red-700 active:bg-red-600 cursor-pointer' =>
                $cartItems->count() !== 0,
        ]) @disabled($cartItems->count() === 0)>
          Limpiar Carrito
        </button>
      </form>
    </div>

    <section class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
      <div class="max-h-screen overflow-y-auto lg:col-span-7" style="scrollbar-color: #62748e transparent">
        <ul role="list" class="border-y divide-y divide-gray-200 border-gray-200 sm:pe-3">
          @php $total = 0; @endphp
          @forelse ($cartItems as $details)
            @php $total += $details->price * $details->quantity - $details->attributes->discount; @endphp

            <li class="py-6 flex gap-4 sm:py-10" data-id="{{ $details->id }}">
              <div class="shrink-0">
                <img src="{{ asset('images/products/' . $details->attributes->image) }}"
                  alt="{{ $details->attributes->description }}" class="size-24 rounded-md object-cover sm:size-40" />
              </div>
              <div class="pe-4 flex flex-1 flex-col gap-2 justify-around sm:ps-4">
                <div>
                  <div class="flex gap-4 justify-between">
                    <x-buttons.link href="{{ route('product.show', $details->id) }}"
                      class="text-lg font-medium text-gray-900 hover:text-gray-700">
                      {{ $details->name }}
                    </x-buttons.link>
                    <form action="{{ route('cart.remove', ['id' => $cart_id, 'id_product' => $details->id]) }}"
                      method="POST" class="flex justify-center items-start">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="p-0.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                        <x-icons.x class="size-6" />
                      </button>
                    </form>
                  </div>
                  <p class="mt-1 text-gray-500 text-sm sm:text-base">
                    {{ $details->attributes->brand }}
                    <span class="ms-2 ps-2 border-s border-gray-300">
                      {{-- {{ $details->attributes->category }} --}}
                    </span>
                  </p>
                </div>
                <form class="flex flex-1 items-end justify-between text-sm" action="{{ route('cart.update') }}"
                  method="POST" data-submit="empty">
                  @csrf
                  @method('PUT')
                  <label class="w-full max-w-16 grid grid-cols-1">
                    <input type="number" name="quantity" value="{{ $details->quantity }}" min="1" max="99"
                      class="px-3 py-1.5 text-base text-gray-900 rounded-md outline outline-offset-1 outline-gray-300 focus:outline-indigo-600 focus:outline-offset-2 focus:outline-2 sm:text-sm">
                  </label>
                  <input type="hidden" name="id" value="{{ $details->id }}">
                  <div @class([
                      'ml-4 text-slate-900 font-medium text-lg',
                      'flex flex-col items-start justify-center' =>
                          $details->attributes->discount !== 0,
                  ])>
                    <p @class([
                        'text-base font-normal text-slate-500 line-through' =>
                            $details->attributes->discount !== 0,
                    ])>
                      ${{ number_format($details->price * $details->quantity, 2, ',', '.') }}</p>
                    @if ($details->attributes->discount !== 0)
                      <span>
                        ${{ number_format($details->price * $details->quantity - $details->attributes->discount, 2, ',', '.') }}
                      </span>
                    @endif
                  </div>
                </form>
              </div>
            </li>
          @empty
            <li class="py-10 text-center text-2xl font-medium">Sin productos en el carrito</li>
          @endforelse
        </ul>
      </div>
      <form action="{{ route('orders.store') }}" method="POST"
        class="px-4 py-6 mt-16 rounded-lg bg-indigo-50 space-y-6 sm:p-6 lg:mt-0 lg:p-8 lg:col-span-5">
        @csrf
        <input type="hidden" name="cart_id" value="{{ auth()->user()->cart->id }}">
        <h2 class="text-lg font-medium text-gray-900">Resumen del pedido</h2>
        <div class="space-y-4">
          <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
              Notas adicionales (Opcional)
            </label>
            <textarea name="notes" id="notes" rows="3"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              placeholder="Ej: Prefiero retirar después de las 17hs..."></textarea>
          </div>
          <div class="pt-4 flex items-center justify-between text-base">
            <p class="text-gray-600">Subtotal</p>
            <p class="font-medium text-gray-900" id="cart-subtotal">
              ${{ number_format($total, 2, ',', '.') }}
            </p>
          </div>
          @php
            $tax = ($total * floatval(config('commerce.tax_rate'))) / 100;
          @endphp
          <div class="pt-4 flex items-center justify-between text-base border-t border-gray-200">
            <p class="text-gray-600">IVA ({{ floatval(config('commerce.tax_rate')) }}%)</p>
            <p class="font-medium text-gray-900">
              ${{ number_format($tax, 2, ',', '.') }}
            </p>
          </div>
          <div class="pt-4 flex items-center justify-between text-lg font-medium text-gray-900 border-t border-gray-200">
            <p>Total del pedido</p>
            <p id="cart-total">${{ number_format($total + $tax, 2, ',', '.') }}
            </p>
          </div>
          <div class="pt-4 border-t border-gray-200">
            <div class="rounded-md bg-yellow-50 p-4 text-sm text-yellow-800">
              <p class="font-medium">📦 Retiro en local</p>
              <p class="mt-1">Dirección: {{ config('app_settings.address') ?? 'Dirección no configurada' }}</p>
              <p class="mt-1">Horario: {{ config('app_settings.pickup_hours') ?? 'Consultar' }}</p>
              <p class="mt-1 text-xs">⚠️ Presentá tu DNI y el número de pedido al retirar.</p>
            </div>
          </div>
          <div class="pt-4 border-t border-gray-200">
            <fieldset>
              <legend class="text-sm font-medium text-gray-900 mb-3">Método de pago</legend>
              <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                  <input type="radio" name="payment_method" value="mercadopago"
                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" checked>
                  <span class="text-sm text-gray-700">💳 Mercado Pago (Tarjeta / Dinero en cuenta)</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                  <input type="radio" name="payment_method" value="store"
                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                  <span class="text-sm text-gray-700">🏪 Pago en tienda (Efectivo / Transferencia)</span>
                </label>
              </div>
            </fieldset>
          </div>
        </div>
        <button type="submit"
          class="w-full rounded-md bg-indigo-600 px-6 py-3 text-center text-base font-medium text-white shadow-xs hover:bg-indigo-700 cursor-pointer">
          Proceder al pago</button>
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
@endsection
