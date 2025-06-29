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
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Total</th>
        <th class="hidden md:table-cell">M. Pago</th>
        <th class="hidden md:table-cell">Estado</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($orders as $index => $order)
      @php
        $OrderDate = Str::substr($order->date, 0, 10);
      @endphp
      <tr>
        <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}
        </td>
        <td class="font-bold">{{ $order->user->fullName() }}</td>
        <td class="text-slate-300">{{ $OrderDate }}</td>
        <td><span class="me-px">$</span>{{ $order->total + 0 }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $order->payments[0]->type }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $order->orderStatus->name }}</td>
        <td>
          <div class="relative flex justify-end">
            <input type="checkbox" id="chorder-{{ $order->id }}" class="hidden peer/checkOption">
            <label for="chorder-{{ $order->id }}"
              class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
              </svg>
            </label>
            <div class="absolute right-12 -top-2/3 z-[5] hidden peer-checked/checkOption:block">
              <ul
                class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:cursor-pointer [&>li:hover]:bg-slate-700 [&>li]:transition-colors">
                <li>
                  <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2.5 flex gap-3">
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
                    Detalles
                  </a>
                </li>
                <li>
                  @php
                    $dialogid = 'dialog' . $order->id;
                  @endphp
                  <button type="button" onclick="openModal('{{ $dialogid }}')"
                    class="px-4 py-2.5 flex gap-3 cursor-pointer">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M20 8.7H4a.75.75 0 1 1 0-1.5h16a.75.75 0 0 1 0 1.5" />
                        <path fill="currentColor"
                          d="M16.44 20.75H7.56A2.4 2.4 0 0 1 5 18.49V8a.75.75 0 0 1 1.5 0v10.49c0 .41.47.76 1 .76h8.88c.56 0 1-.35 1-.76V8A.75.75 0 1 1 19 8v10.49a2.4 2.4 0 0 1-2.56 2.26m.12-13a.74.74 0 0 1-.75-.75V5.51c0-.41-.48-.76-1-.76H9.22c-.55 0-1 .35-1 .76V7a.75.75 0 1 1-1.5 0V5.51a2.41 2.41 0 0 1 2.5-2.26h5.56a2.41 2.41 0 0 1 2.53 2.26V7a.75.75 0 0 1-.75.76Z" />
                        <path fill="currentColor"
                          d="M10.22 17a.76.76 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.75.75 0 0 1-.75.76m3.56 0a.75.75 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.76.76 0 0 1-.75.76" />
                      </svg>
                    </span>
                    Eliminar Orden
                  </button>
                  <x-modals.confirm id="{{ $dialogid }}" title="{{ 'Borrar la orden ' . $OrderDate }}">
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
            <th>#</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Total</th>
            <th class="hidden md:table-cell">M. Pago</th>
            <th class="hidden md:table-cell">Estado</th>
            <th class="text-end">Opciones</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          @forelse ($ordersDeleted as $index => $order)
            <tr class="[&>td]:text-slate-400">
              <td>{{ ($ordersDeleted->currentPage() - 1) * $ordersDeleted->perPage() + $index + 1 }}
              </td>
              <td class="font-bold">{{ $order->user->fullName() }}</td>
              <td class="text-slate-300">{{ $OrderDate }}</td>
              <td class="text-slate-300"><span class="me-px">$</span>{{ $order->total + 0 }}</td>
              <td class="hidden text-xs text-slate-300 md:table-cell">{{ $order->payments[0]->type }}</td>
              <td class="hidden text-xs text-slate-300 md:table-cell">{{ $order->orderStatus->name }}</td>
              <td>
                <div class="relative flex justify-end">
                  <input type="checkbox" id="chorder-{{ $order->id }}" class="hidden peer/checkOption">
                  <label for="chorder-{{ $order->id }}"
                    class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                    </svg>
                  </label>
                  <div class="absolute right-12 -top-2/3 z-[5] hidden peer-checked/checkOption:block">
                    <ul
                      class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li:hover]:bg-slate-700 [&>li]:transition-colors">
                      <li>
                        <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2.5 flex gap-3">
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
                          Detalles
                        </a>
                      </li>
                      <li>
                        @php
                          $dialogid = 'dialog' . $order->id;
                        @endphp
                        <button type="button" onclick="openModal('{{ $dialogid }}')"
                          class="px-4 py-2.5 flex gap-3 cursor-pointer">
                          <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                              viewBox="0 0 512 512">
                              <path fill="currentColor" fill-rule="evenodd"
                                d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                                clip-rule="evenodd" />
                            </svg>
                          </span>
                          Restaurar Orden
                        </button>
                        <x-modals.confirm id="{{ $dialogid }}" title="{{ 'Restaurar la orden ' . $OrderDate }}">
                          <div class="flex flex-col items-center justify-center">
                            <span class="text-slate-500">
                              <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96"
                                viewBox="0 0 24 24">
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
        </tbody>
      </table>
    </section>

    {{ $ordersDeleted->onEachSide(5)->links('pages.dashboard.partials.pagination') }}
  @else
    <h3 class="my-3 text-center text-xl font-semibold">Sin ordenes eliminados</h3>
  @endif
@endsection
