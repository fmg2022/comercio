@extends('layouts.dashboard')

@section('scripts')
  <script src="{{ asset('js/dashboard/modal.js') }}" defer></script>
@endsection

@php
  $titleEdit = 'Detalles de la Orden' . ($order->trashed() ? ' (Eliminada)' : '');
@endphp
@section('titleH1', $titleEdit)

@section('scripts')
  <script src="{{ asset('js/dashboard/modal.js') }}" defer></script>
@endsection

@section('content')
  <article class="px-3">
    <div class="mb-4 flex justify-between items-center">
      <h2 class="text-3xl font-semibold">Orden #{{ $order->id }}
        <span class="ms-2">({{ Str::substr($order->date, 0, 10) }})</span>
      </h2>
      <div class="flex gap-2">
        <x-buttons.ancorFill href="{{ route('orders.index') }}" class="bg-slate-500 active:bg-slate-600">
          Volver
        </x-buttons.ancorFill>
        @if ($order->trashed())
          <button type="button" onclick="openModal('restDialog')"
            class="px-3 py-2 bg-green-900 rounded-md hover:bg-green-800 cursor-pointer">
            Restaurar Orden
          </button>
          <x-modals.confirm id="restDialog" title="{{ 'Restaurar la orden ' . Str::substr($order->date, 0, 10) }}">
            <div class="flex flex-col items-center justify-center">
              <span class="text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
                </svg>
              </span>
              <p class="px-2 py-4 mb-3">¿Está seguro de que desea restaurar esta orden?</p>
            </div>
            <div class="flex justify-end gap-3 text-white">
              <form action="{{ route('orders.restore', $order->id) }}" method="POST">
                @csrf
                <button type="submit"
                  class="px-3 py-2 bg-green-900 rounded-md hover:bg-green-800 cursor-pointer">Restaurar</button>
              </form>
              <form method="dialog">
                <button class="px-3 py-2 bg-slate-700 rounded-md hover:bg-slate-600 cursor-pointer">Cancelar</button>
              </form>
            </div>
          </x-modals.confirm>
        @else
          <x-buttons.ancorFill href="" class="bg-red-700 active:bg-red-800">
            Generar PDF
          </x-buttons.ancorFill>
        @endif
      </div>
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
          <th class="text-end">Opciones</th>
        </tr>
      </x-slot>

      @foreach ($order->products as $index => $orderLine)
        <tr class="[&>td]:text-slate-200">
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
          <td class="text-center font-bold"><span class="me-px">$</span>{{ $orderLine->pivot->price + 0 }}</td>
          <td class="text-center hidden md:table-cell"><span class="me-px">$</span>{{ $orderLine->pivot->discount + 0 }}
          </td>
          <td class="text-center hidden md:table-cell">
            <span class="me-px">$</span>{{ $orderLine->pivot->subtotal() + 0 }}
          </td>
          <td>
            <div class="relative flex justify-end items-center">
              <input type="checkbox" id="chorder-{{ $orderLine->id }}" class="hidden peer/checkOption">
              <label for="chorder-{{ $orderLine->id }}"
                class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                </svg>
              </label>
              <div class="absolute right-12 -top-2/3 z-[5] hidden peer-checked/checkOption:block">
                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-200 font-semibold [&>li]:bg-slate-800 {{ !$order->trashed() ? '[&>li:hover]:bg-slate-700' : '' }} [&>li]:transition-colors">
                  @if (in_array($order->orderStatus->name, ['Pendiente', 'Procesando']))
                    <li>
                      @php
                        $dialogid = 'dialog' . $index . '-' . $orderLine->id;
                      @endphp
                      <button type="button" onclick="openModal('{{ $dialogid }}')"
                        class="w-full flex gap-3 px-4 py-2.5 {{ $order->trashed() ? 'pointer-events-none text-gray-400' : 'cursor-pointer' }}">
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
                      </button>
                      <x-modals.confirm id="{{ $dialogid }}" title="{{ 'Editar fila #' . ($index + 1) }}"
                        class="max-w-xl w-full">
                        <div class="relative flex flex-col items-center justify-center text-white">
                          <form action="{{ route('orderLine.edit', $order->id, $orderLine->id) }}" method="POST"
                            class="w-full">
                            @csrf
                            @method('PUT')
                            <div class="mb-14 flex flex-col items-center text-slate-900">
                              <div
                                class="p-5 mx-auto mb-4 bg-gradient-to-b from-purple-400 to-purple-200 border-purple-700 rounded-md">
                                <img src="{{ asset('images/products/' . $orderLine->image) }}"
                                  alt="{{ $orderLine->name }}" class="size-40 object-cover">
                              </div>
                              <input type="hidden" name="product_id" value="{{ $orderLine->id }}">
                              <div class="mb-3 text-2xl">
                                <h3 class="font-bold">
                                  {{ $orderLine->name }}
                                  <span class="text-purple-950">{{ $orderLine->mark }}</span>
                                </h3>
                                <p class="mb-3">
                                  <span class="text-lg text-slate-700">Precio:</span>
                                  <span class="me-px">$</span>
                                  {{ $orderLine->pivot->price + 0 }}
                                </p>
                                <div>
                                  <label class="text-lg text-slate-600" for="quantity{{ $orderLine->id }}">
                                    Cantidad:
                                  </label>
                                  <input id="quantity{{ $orderLine->id }}" type="number" name="quantity"
                                    min="1" max="{{ $orderLine->stock }}"
                                    value="{{ $orderLine->pivot->quantity }}"
                                    class="w-16 px-2 py-1 text-2xl rounded-md outline-none" required>
                                </div>
                              </div>
                            </div>
                            <button type="submit"
                              class="absolute bottom-0 left-1/2 px-3 py-2 bg-purple-900 text-lg rounded-md hover:bg-purple-800 cursor-pointer">Actualizar</button>
                          </form>
                          <form method="dialog" class="absolute bottom-0 right-1/2 -translate-x-3">
                            <button
                              class="px-3 py-2 bg-red-700 text-lg rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
                          </form>
                        </div>
                      </x-modals.confirm>
                    </li>
                  @endif
                  <li>
                    <a href="{{ route('products.show', $orderLine->id) }}" class="px-4 py-2.5 flex gap-3">
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
                </ul>
              </div>
            </div>
          </td>
        </tr>
      @endforeach
    </x-tables.table>
    <div
      class="w-full max-w-2xs px-10 py-3 ms-auto flex justify-between items-center text-xl font-bold bg-slate-700 rounded-b-md">
      <span>Total</span>
      <span><span class="me-px">$</span>{{ $order->total + 0 }}</span>
    </div>
  </article>
@endsection
