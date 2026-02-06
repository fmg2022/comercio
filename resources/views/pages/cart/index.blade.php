@extends('layouts.app')

@push('scripts-app')
  <script src="{{ asset('js/itemCart.js') }}" defer></script>
@endpush

@section('content')
  <article class="px-4 py-16 max-w-2xl mx-auto md:px-6 lg:px-8 lg:max-w-7xl">
    <h1 class="text-3xl text-gray-900 font-bold tracking-tight sm:text-4xl">Mi carrito</h1>

    <section class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
      <div class="lg:col-span-7">
        <ul role="list" class="border-y divide-y divide-gray-200 border-gray-200">
          @forelse ($cart as $details)
            <li class="py-6 flex gap-4 sm:py-10" data-id="{{ $details->id }}">
              <div class="shrink-0">
                <img src="{{ asset('images/products/' . $details->attributes->image) }}"
                  alt="{{ $details->attributes->description }}" class="size-24 rounded-md object-cover sm:size-40" />
              </div>
              <div class="flex flex-1 flex-col justify-around sm:ml-6">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">
                    <a href="{{ route('product.show', $details->id) }}">{{ $details->name }}</a>
                  </h3>
                  <p class="mt-1 text-gray-500">
                    {{ $details->attributes->brand->name }}
                    <span class="ms-2 ps-2 border-s border-gray-300">
                      {{ $details->category }}
                    </span>
                  </p>
                </div>
                <div class="flex flex-1 items-end justify-between text-sm">
                  <label class="w-full max-w-16 grid grid-cols-1" data-form>
                    <input type="number" name="quantity" value="{{ $details->quantity }}" min="1" max="99"
                      class="px-3 py-1.5 text-base text-gray-900 rounded-md outline outline-offset-1 outline-gray-300 focus:outline-indigo-600 focus:outline-offset-2 focus:outline-2 sm:text-sm">
                  </label>
                  <p class="ml-4 text-lg font-medium text-gray-900" data-value="{{ $details->price }}">
                    ${{ number_format($details->price, 2, ',', '.') }}
                  </p>
                </div>
              </div>
              <div class="flex justify-center items-start">
                <button type="button" class="p-2 text-gray-400 hover:text-gray-500 remove-item" data-delete="ok">
                  <x-icons.x class="size-6" />
                </button>
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
        @php $total = Cart::getSubTotalWithoutConditions(); @endphp
        <input type="hidden" name="cart_id" value="{{ auth()->user()->cart->id }}">
        <h2 class="text-lg font-medium text-gray-900">Resumen del pedido</h2>
        <div class="space-y-4">
          <div class="pt-4 flex items-center justify-between text-base">
            <p class="text-gray-600">Subtotal</p>
            <p class="font-medium text-gray-900" id="cart-subtotal">
              ${{ number_format($total, 2, ',', '.') }}
            </p>
          </div>
          <div class="pt-4 flex items-center justify-between text-base border-t border-gray-200">
            <p class="text-gray-600">Costo de envio estimado</p>
            <p class="font-medium text-gray-900" id="cart-shipping" data-value="{{ $shipping }}">
              ${{ number_format($shipping, 2, ',', '.') }}
            </p>
          </div>
          <div class="pt-4 flex items-center justify-between text-base border-t border-gray-200">
            @php $finalTax = $total * $tax; @endphp
            <p class="text-gray-600">Impuestos estimados</p>
            <p class="font-medium text-gray-900" id="cart-tax" data-value="{{ $tax }}">
              ${{ number_format($finalTax, 2, ',', '.') }}
            </p>
          </div>
          <div class="pt-4 flex items-center justify-between text-lg font-medium text-gray-900 border-t border-gray-200">
            <p>Total del pedido</p>
            <p id="cart-total">${{ number_format($total + $shipping + $finalTax, 2, ',', '.') }}
            </p>
          </div>
        </div>
        <button type="submit"
          class="w-full rounded-md bg-indigo-600 px-6 py-3 text-center text-base font-medium text-white shadow-xs hover:bg-indigo-700">
          Pagar</button>
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
