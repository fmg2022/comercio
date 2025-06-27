@extends('layouts.dashboard')

@section('titleH1', 'Detalles de la Orden')

@section('scripts')
  <script src="{{ asset('js/dashboard/modal.js') }}" defer></script>
@endsection

@section('content')
  <article class="px-3">
    <div class="mb-4 flex justify-between items-center">
      <h2 class="text-3xl font-semibold">Orden #{{ $order->id }}
        <span class="ms-2">({{ Str::substr($order->date, 0, 10) }})</span>
      </h2>
      <x-buttons.ancorFill href="" class="bg-red-700 active:bg-red-800">
        Generar PDF
      </x-buttons.ancorFill>
      <x-buttons.ancorFill href="{{ route('orders.edit', $order->id) }}" class="bg-purple-600 active:bg-purple-700">
        Editar
      </x-buttons.ancorFill>
    </div>
    <x-tables.table>
      <x-slot:thead>
        <tr>
          <th>#</th>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio</th>
          <th class="hidden md:table-cell">Descuento</th>
          <th class="hidden md:table-cell">Subtotal</th>
          <th>Opciones</th>
        </tr>
      </x-slot>

      @foreach ($order->products as $index => $orderLine)
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td>
            <div class="ps-5 flex items-center gap-2 text-base">
              <img src="{{ asset('images/products/' . $orderLine->image) }}" alt="{{ $orderLine->name }}"
                class="w-16 h-16 object-cover hidden lg:block">
              <span class="text-slate-400 font-semibold">{{ $orderLine->name }}</span>
              <span class="me-2 font-bold">{{ $orderLine->mark }}</span>
            </div>
          </td>
          <td class="text-center">{{ $orderLine->pivot->quantity }}</td>
          <td class="text-center font-bold">$ {{ $orderLine->pivot->price + 0 }}</td>
          <td class="text-center hidden md:table-cell">$ {{ $orderLine->pivot->discount + 0 }}</td>
          <td class="text-center hidden md:table-cell">
            $ {{ $orderLine->pivot->price * $orderLine->pivot->quantity - $orderLine->pivot->discount }}
          </td>
          <td>
            <div class="relative flex justify-center items-center">
              <input type="checkbox" id="chorder-{{ $orderLine->id }}" class="hidden peer/checkOption">
              <label for="chorder-{{ $orderLine->id }}"
                class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                </svg>
              </label>
              <div class="absolute right-1/4 top-[120%] z-[5] hidden peer-checked/checkOption:block">
                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:px-4 [&>li]:py-2.5 [&>li]:bg-slate-800 [&>li:hover]:bg-slate-700 [&>li]:transition-colors">
                  <li>
                    <a href="" class="flex gap-3">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                          </g>
                        </svg>
                      </span>
                      Editar Linea
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('products.show', $orderLine->id) }}" class="flex gap-3">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path
                              d="M3.587 13.779c1.78 1.769 4.883 4.22 8.413 4.22s6.634-2.451 8.413-4.22c.47-.467.705-.7.854-1.159c.107-.327.107-.913 0-1.24c-.15-.458-.385-.692-.854-1.159C18.633 8.452 15.531 6 12 6c-3.53 0-6.634 2.452-8.413 4.221c-.47.467-.705.7-.854 1.159c-.107.327-.107.913 0 1.24c.15.458.384.692.854 1.159" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                          </g>
                        </svg>
                      </span>
                      Ver Producto
                    </a>
                  </li>
                  <li>
                    @php
                      $dialogid = 'dialog' . $index;
                    @endphp
                    <button type="button" onclick="openModal('{{ $dialogid }}')" class="flex gap-3 cursor-pointer">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <path fill="currentColor" d="M20 8.7H4a.75.75 0 1 1 0-1.5h16a.75.75 0 0 1 0 1.5" />
                          <path fill="currentColor"
                            d="M16.44 20.75H7.56A2.4 2.4 0 0 1 5 18.49V8a.75.75 0 0 1 1.5 0v10.49c0 .41.47.76 1 .76h8.88c.56 0 1-.35 1-.76V8A.75.75 0 1 1 19 8v10.49a2.4 2.4 0 0 1-2.56 2.26m.12-13a.74.74 0 0 1-.75-.75V5.51c0-.41-.48-.76-1-.76H9.22c-.55 0-1 .35-1 .76V7a.75.75 0 1 1-1.5 0V5.51a2.41 2.41 0 0 1 2.5-2.26h5.56a2.41 2.41 0 0 1 2.53 2.26V7a.75.75 0 0 1-.75.76Z" />
                          <path fill="currentColor"
                            d="M10.22 17a.76.76 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.75.75 0 0 1-.75.76m3.56 0a.75.75 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.76.76 0 0 1-.75.76" />
                        </svg>
                      </span>
                      Eliminar Fila
                    </button>
                    <x-modals.confirm id="{{ $dialogid }}" title="{{ 'Borrar fila #' . ($index + 1) }}">
                      <div class="flex flex-col items-center justify-center">
                        <span class="text-slate-500">
                          <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24">
                            <path fill="currentColor"
                              d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
                          </svg>
                        </span>
                        <p class="px-2 py-4 mb-3 text-base">¿Está seguro de que desea quitar el producto
                          "{{ $orderLine->name }}"?</p>
                      </div>
                      <div class="flex justify-end gap-3 text-white">
                        <form action="" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="px-3 py-2 bg-red-900 rounded-md hover:bg-red-800 cursor-pointer">Eliminar</button>
                        </form>
                        <form method="dialog">
                          <button
                            class="px-3 py-2 bg-slate-700 rounded-md hover:bg-slate-600 cursor-pointer">Cancelar</button>
                        </form>
                      </div>
                    </x-modals.confirm>
                  </li>
                </ul>
              </div>
            </div>
          </td>
        </tr>
      @endforeach
    </x-tables.table>
    <div
      class="w-full max-w-2xs px-8 py-3 ms-auto flex justify-between items-center text-xl font-bold bg-slate-700 rounded-b-md">
      <span>Total</span>
      <span>$ {{ $order->total + 0 }}</span>
    </div>
  </article>
@endsection
