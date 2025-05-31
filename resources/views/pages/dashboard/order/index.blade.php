@extends('layouts.dashboard')

@section('scripts')
  <script src="{{ asset('js/dashboard/modal.js') }}" defer></script>
@endsection

@section('titleH1', 'Ordenes')

<!-- Mostrar un mensaje para:
    - Los errores en las operaciones desde está página
    - El mensaje de éxito al crear un producto -->

@section('content')
  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <!-- <th class="hidden sm:table-cell">SKU</th> -->
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Total</th>
        <th class="hidden md:table-cell">M. Pago</th>
        <th class="hidden md:table-cell">Estado</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($orders as $index => $order)
      <tr>
        <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}
        </td>
        <td class="font-bold">{{ $order->user->fullName() }}</td>
        <td class="text-slate-300">{{ $order->date }}</td>
        <td class="text-slate-300"><span class="me-px">$</span>{{ $order->total }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $order->payments }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $order->status }}</td>
        <td class="relative flex justify-end">
          <input type="checkbox" id="chorder-{{ $order->id }}" class="hidden peer/checkOption">
          <label for="chorder-{{ $order->id }}"
            class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
            </svg>
          </label>
          <div class="absolute right-1/4 z-30 hidden peer-checked/checkOption:block">
            <ul
              class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:px-4 [&>li]:py-2.5 [&>li]:bg-slate-800 [&>li]:cursor-pointer [&>li:hover]:bg-slate-700 [&>li]:transition-colors">
              <li class="flex gap-3">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2">
                      <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                      <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                    </g>
                  </svg>
                </span>
                <span>Editar Orden</span>
              </li>
              <li class="flex gap-3">
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
                <a href="{{ route('orders.show', $order->id) }}">Detalles</a>
              </li>
              <li class="flex gap-3">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M20 8.7H4a.75.75 0 1 1 0-1.5h16a.75.75 0 0 1 0 1.5" />
                    <path fill="currentColor"
                      d="M16.44 20.75H7.56A2.4 2.4 0 0 1 5 18.49V8a.75.75 0 0 1 1.5 0v10.49c0 .41.47.76 1 .76h8.88c.56 0 1-.35 1-.76V8A.75.75 0 1 1 19 8v10.49a2.4 2.4 0 0 1-2.56 2.26m.12-13a.74.74 0 0 1-.75-.75V5.51c0-.41-.48-.76-1-.76H9.22c-.55 0-1 .35-1 .76V7a.75.75 0 1 1-1.5 0V5.51a2.41 2.41 0 0 1 2.5-2.26h5.56a2.41 2.41 0 0 1 2.53 2.26V7a.75.75 0 0 1-.75.76Z" />
                    <path fill="currentColor"
                      d="M10.22 17a.76.76 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.75.75 0 0 1-.75.76m3.56 0a.75.75 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.76.76 0 0 1-.75.76" />
                  </svg>
                </span>
                @php
                  $dialogid = 'dialog' . $order->id;
                @endphp
                <button type="button" onclick="openModal('{{ $dialogid }}')">Eliminar Orden</button>
                <x-modals.confirm id="{{ $dialogid }}" title="{{ 'Borrar el producto ' . $order->name }}">
                  <div class="flex flex-col items-center justify-center">
                    <span class="text-slate-500">
                      <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24">
                        <path fill="currentColor"
                          d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
                      </svg>
                    </span>
                    <p class="px-2 py-4 mb-3">¿Está seguro de que desea eliminar esta orden?</p>
                  </div>
                  <div class="flex justify-end gap-3 text-white">
                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
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
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center font-semibold text-slate-300">No hay ordenes registradas</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $orders->onEachSide(5)->links('pages.dashboard.partials.pagination') }}

  @if ($ordersDeleted->count() > 0)
    <section class="mt-10">
      <h2 class="mb-5 px-4 text-2xl font-semibold text-gray-300">Productos Eliminados</h2>
      <table
        class="table-auto w-full border-separate border-spacing-y-1 [&_th]:py-4 [&_th]:px-3 [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800">
        <thead class="text-sm dark:text-slate-400">
          <tr class="text-left">
            <th>Nombre</th>
            <!-- <th class="hidden sm:table-cell">SKU</th> -->
            <th>Precio</th>
            <th>Stock</th>
            <th class="hidden md:table-cell">Categoría</th>
            <th class="text-end">Opciones</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          @foreach ($productsDeleted as $product)
            <tr>
              <td class="flex items-center gap-3">
                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                  class="size-12 aspect-square">
                <span class="hidden font-semibold sm:inline">{{ $product->name }}</span>
              </td>
              <td class="font-bold"><span class=" me-px">$</span>{{ $product->price }}</td>
              <td class="text-xs text-slate-300">{{ $product->quantity }}</td>
              <!-- <td class="hidden text-xs text-slate-300 sm:table-cell">45</td> -->
              <td class="hidden text-xs text-slate-300 md:table-cell">{{ $product->category->name }}</td>
              <td class="relative flex justify-end">
                <input type="checkbox" id="chproduct-{{ $product->id }}" class="hidden peer/checkOption">
                <label for="chproduct-{{ $product->id }}"
                  class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                  </svg>
                </label>
                <div class="absolute right-1/4 z-30 hidden peer-checked/checkOption:block">
                  <ul
                    class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:px-4 [&>li]:py-2.5 [&>li.active]:bg-slate-800 [&>li.active]:cursor-pointer [&>li.active:hover]:bg-slate-700 [&>li.active]:transition-colors">
                    <li class="flex gap-3 text-gray-400 pointer-events-none">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                          </g>
                        </svg>
                      </span>
                      <span>Editar Producto</span>
                    </li>
                    <li class="flex gap-3 active">
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
                      <span>Ver Producto</span>
                    </li>
                    <li class="flex gap-3 text-gray-400 pointer-events-none">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5" color="currentColor">
                            <path
                              d="M4.318 19.682C3 18.364 3 16.242 3 12s0-6.364 1.318-7.682S7.758 3 12 3s6.364 0 7.682 1.318S21 7.758 21 12s0 6.364-1.318 7.682S16.242 21 12 21s-6.364 0-7.682-1.318" />
                            <path d="M6 12h2.5l2-4l3 8l2-4H18" />
                          </g>
                        </svg>
                      </span>
                      <span>Productos en Ordenes</span>
                    </li>
                    <li class="flex gap-3 active">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                          <path fill="currentColor" fill-rule="evenodd"
                            d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                            clip-rule="evenodd" />
                        </svg>
                      </span>
                      @php
                        $dialogid = 'dialog' . $product->id;
                      @endphp
                      <button type="button" onclick="openModal('{{ $dialogid }}')">Restaurar
                        Producto</button>
                      <x-modals.confirm id="{{ $dialogid }}"
                        title="{{ 'Restaurar el producto ' . $product->name }}">
                        <div class="flex flex-col items-center justify-center">
                          <span class="text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24">
                              <path fill="currentColor"
                                d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
                            </svg>
                          </span>
                          <p class="px-2 py-4 mb-3 text-lg">¿Está seguro de que desea restaurar este producto?</p>
                        </div>
                        <div class="flex justify-end gap-3 text-white">
                          <form action="{{ route('products.restore', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                              class="px-3 py-2 bg-green-900 rounded-md hover:bg-green-800 cursor-pointer">Restaurar</button>
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
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </section>

    {{ $productsDeleted->onEachSide(5)->links('pages.dashboard.partials.pagination') }}
  @else
    <h3 class="my-3 text-center text-xl font-semibold">Sin productos eliminados</h3>
  @endif
@endsection
