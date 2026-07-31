@extends('layouts.dashboard')

@pushIf($cart->products->count() > 0 && auth()->user()?->can('manage carts-details'),
'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
<script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
@endPushIf

@section('content')
  <x-sections.headerTitle classTitle="flex flex-wrap items-center gap-3"
    class="flex justify-between items-center flex-wrap">
    <x-slot:textTitle>
      Productos en el carrito actualmente
    </x-slot:textTitle>
    <div class="flex gap-4">
      <x-buttons.linkFill href="{{ url()->previous() ?: route('dashboard.index') }}"
        class="bg-slate-500 active:bg-slate-600">
        Volver
      </x-buttons.linkFill>
      @if (request()->routeIs('my.cart.index'))
        <form action="{{ route('cart.clearCart', $cart->id) }}" method="POST" class="flex justify-center items-start">
          @csrf
          @method('DELETE')
          <button type="submit" class="px-3 py-2 bg-red-700 active:bg-red-600 rounded-md cursor-pointer">
            Limpiar Carrito
          </button>
        </form>
        <x-buttons.linkFill href="{{ route('cart.index') }}"
          class="flex items-center gap-2 bg-green-800 hover:bg-green-700">
          Proceder a la compra
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M0 0h24v24H0z" fill="none" />
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5 12h14m-6 6l6-6m-6-6l6 6" />
          </svg>
        </x-buttons.linkFill>
      @endif
    </div>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr>
        <th>#</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th class="hidden md:table-cell">Subtotal</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot:head>
    <x-slot:tbody>
      @foreach ($cart->products as $index => $product)
        <tr class="[&>td]:text-slate-200">
          <td class="text-center">{{ $index + 1 }}</td>
          <td>
            <div class="flex items-center flex-wrap gap-2 text-base">
              <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                class="w-16 h-16 object-cover hidden lg:block">
              <span class="text-slate-400 font-semibold">{{ $product->name }}</span>
              <span class="me-2 font-bold">{{ $product->brand->name }}</span>
            </div>
          </td>
          <td class="text-center">{{ $product->pivot->quantity }}</td>
          <td class="text-center font-bold">${{ number_format($product->price, 2, ',', '.') }}</td>
          <td class="text-center hidden md:table-cell">
            ${{ number_format($product->price * $product->pivot->quantity, 2, ',', '.') }}
          </td>
          <td>
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chproduct-{{ $product->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                  @can('manage carts-details')
                    <li>
                      <a href="{{ route('products.show', $product->id) }}"
                        class="px-4 py-2.5 flex gap-3 hover:bg-slate-700">
                        <span>
                          <x-icons.show class="size-5" />
                        </span>
                        Ver Producto
                      </a>
                    </li>
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $product->id }}"
                        data-path="{{ $cart->id }}/product/{{ $product->id }}" data-modalID="cartDetailsCES"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar Cantidad
                      </button>
                    </li>
                    <li>
                      <button type="button" data-text="Producto: '{{ $product->name }}'" data-uid="{{ $product->id }}"
                        data-modalID="cartDeatailDelete" data-path="{{ $cart->id }}/products/{{ $product->id }}"
                        data-delete="true"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          <x-icons.trash class="size-5" />
                        </span>
                        Quitar Producto
                      </button>
                    </li>
                  @endcan
                </ul>
              </x-popups.contentWcheck>
            </div>
          </td>
        </tr>
      @endforeach
    </x-slot:tbody>
  </x-tables.table>
  <p
    class="w-fit px-8 py-3 ms-auto flex justify-between items-center gap-4 bg-slate-800 rounded-b-lg text-lg font-semibold">
    <span>Total sin impuestos</span>
    <span>${{ number_format($cart->total, 2, ',', '.') }}</span>
  </p>

  @if ($cart->products->count() > 0 && auth()->user()?->can('manage carts-details'))
    {{-- MODAL SHOW, EDIT --}}
    <x-modals.simple id="cartDetailsCES"
      class="max-w-lg w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
      <form enctype="multipart/form-data" method="POST" action="{{ route('cart.update') }}" data-persist="true"
        class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
        @csrf
        @method('PUT')
        <fieldset class="py-3 grid grid-cols-1 gap-6 text-gray-700 md:px-3">
          <input type="hidden" name="id">
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="quantity">Cantidad</label>
            <input type="number" id="quantity" name="quantity" min="1"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <button type="submit"
            class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
        </fieldset>
      </form>
      <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
        <button
          class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
      </form>
    </x-modals.simple>

    {{-- MODAL DELETE, RESTORE --}}
    <x-modals.delete id="cartDeatailDelete" />
  @endif
@endsection
