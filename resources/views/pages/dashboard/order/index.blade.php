@extends('layouts.dashboard')

@push('scripts')
  <script src="{{ asset('js/dashboard/modal.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/modalSimple.js') }}" defer></script>
@endpush

{{-- Mostrar un mensaje para:
    - Los errores en las operaciones desde está página
    - El mensaje de éxito al crear un producto --}}

@php
  $type1 = 'modalSimpleConfirm';
@endphp

@section('content')
  <x-sections.headerTitle>
    <x-slot:textTitle>Ordenes</x-slot:textTitle>
  </x-sections.headerTitle>

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
        <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}</td>
        <td class="font-bold">{{ $order->user->fullName() }}</td>
        <td class="text-slate-300">{{ $OrderDate }}</td>
        <td><span class="me-px font-semibold">$</span>{{ $order->total + 0 }}</td>
        <td class="hidden text-slate-300 capitalize md:table-cell">{{ $order->payments[0]->type }}</td>
        <td class="hidden md:table-cell">
          <span @class([
              "px-2 py-1 font-semibold rounded-xl before:content-['●'] before:me-px",
              'bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100' =>
                  $order->orderStatus->name === 'Pendiente',
              'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' =>
                  $order->orderStatus->name === 'Procesando',
              'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' =>
                  $order->orderStatus->name === 'Completo',
              'bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100' =>
                  $order->orderStatus->name === 'Delivery',
              'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100' =>
                  $order->orderStatus->name === 'Retirar',
              'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' =>
                  $order->orderStatus->name === 'Entregado',
              'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' =>
                  $order->orderStatus->name === 'Cancelado',
          ])>
            {{ $order->orderStatus->name }}
          </span>
        </td>
        <td>
          <div class="relative flex justify-end">
            <input type="checkbox" id="chorder-{{ $order->id }}" class="hidden peer/checkOption" name="toggle-btns">
            <label for="chorder-{{ $order->id }}"
              class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
              </svg>
            </label>
            <div class="absolute right-12 -top-2/3 z-[5] hidden peer-checked/checkOption:block">
              <ul
                class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:transition-colors">
                <li>
                  <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2.5 flex gap-3 hover:bg-slate-700">
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
                  <button type="button" onclick="openModal('{{ $dialogid }}')"
                    class="w-full px-4 py-2.5 flex gap-3 {{ !in_array($order->orderStatus->name, ['Entregado', 'Cancelado'])
                        ? 'cursor-pointer hover:bg-slate-700'
                        : 'text-slate-500 pointer-events-none' }}">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2">
                          <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                          <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                        </g>
                      </svg>
                    </span>
                    Cambiar Estado
                  </button>
                  <x-modals.simple id="{{ $dialogid }}" class="max-w-xl w-full"
                    title="Cambiar el estado de la orden ({{ $OrderDate }})">
                    <div class="relative flex flex-col items-center justify-center text-white">
                      <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="w-full">
                        @csrf
                        @method('PUT')
                        <div class="mb-14 grid place-items-center text-slate-900">
                          <div class="px-5 pb-4 flex gap-5 text-2xl">
                            <div class="flex flex-col gap-5">
                              <span class="text-lg text-slate-600">De:</span>
                              <span class="text-lg text-slate-600">Monto:</span>
                              <label class="text-lg text-slate-600" for="quantity{{ $order->id }}">
                                Estado de la orden:
                              </label>
                            </div>
                            <div class="flex flex-col gap-4">
                              <h3 class="font-bold">{{ $order->user->fullName() }}</h3>
                              <p><span class="me-px">$</span>{{ $order->total + 0 }}</p>
                              <select name="status" id="quantity{{ $order->id }}"
                                class="outline-none px-2 py-1 rounded-md bg-slate-200 text-lg text-slate-900">
                                @foreach ($orderStatuses as $status)
                                  <option value="{{ $status->id }}"
                                    {{ $status->id === $order->orderStatus->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                  </option>
                                @endforeach
                              </select>
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
                  </x-modals.simple>
                </li>
                <li>
                  <button type="button" data-uid="{{ $order->id }}" data-modal="{{ $type1 }}"
                    data-title="{{ 'Borrar la orden ' . $OrderDate }}"
                    data-text="¿Está seguro de que desea eliminar esta orden?"
                    class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 ">
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

  <x-modals.simple id="{{ $type1 }}" class="max-w-md">
    <div class="flex flex-col items-center justify-center">
      <span class="my-6 text-slate-500">
        <svg xmlns="http://www.w3.org/2000/svg" width="112" height="112" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
        </svg>
      </span>
      <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold"></h2>
      <p class="px-2 py-4 mb-3"></p>
    </div>
    <div class="flex justify-end gap-3 text-white">
      <form id="form-modalSimple" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-3 py-2 bg-red-900 rounded-md hover:bg-red-800 cursor-pointer">Eliminar</button>
      </form>
      <form method="dialog">
        <button class="px-3 py-2 bg-slate-700 rounded-md hover:bg-slate-600 cursor-pointer">Cancelar</button>
      </form>
    </div>
  </x-modals.simple>

  @if ($ordersDeleted->count() > 0)
    <section class="mt-10">
      <h2 class="mb-5 px-4 text-2xl font-semibold text-gray-300">Productos Eliminados</h2>
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
        </x-slot:thead>

        @forelse ($ordersDeleted as $index => $order)
          <tr class="[&>td]:text-slate-400">
            <td>{{ ($ordersDeleted->currentPage() - 1) * $ordersDeleted->perPage() + $index + 1 }}
            </td>
            <td class="font-bold">{{ $order->user->fullName() }}</td>
            <td>{{ $OrderDate }}</td>
            <td><span class="me-px">$</span>{{ $order->total + 0 }}</td>
            <td class="hidden capitalize md:table-cell">{{ $order->payments[0]->type }}</td>
            <td class="hidden md:table-cell">
              <span
                class="px-2 py-1 font-semibold rounded-xl before:content-['●'] before:me-1">{{ $order->orderStatus->name }}</span>
            </td>
            <td>
              <div class="relative flex justify-end">
                <input type="checkbox" id="chorder-{{ $order->id }}" class="hidden peer/checkOption"
                  name="toggle-btns">
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
                    class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:transition-colors">
                    <li>
                      <a href="{{ route('orders.show', $order->id) }}"
                        class="px-4 py-2.5 flex gap-3 hover:bg-slate-700">
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
                        class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700">
                        <span>
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                            <path fill="currentColor" fill-rule="evenodd"
                              d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                              clip-rule="evenodd" />
                          </svg>
                        </span>
                        Restaurar Orden
                      </button>
                      <x-modals.simple id="{{ $dialogid }}" title="{{ 'Restaurar la orden ' . $OrderDate }}">
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
                            <button
                              class="px-3 py-2 bg-slate-700 rounded-md hover:bg-slate-600 cursor-pointer">Cancelar</button>
                          </form>
                        </div>
                      </x-modals.simple>
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </x-tables.table>
    </section>

    {{ $ordersDeleted->onEachSide(5)->links('pages.dashboard.partials.pagination') }}
  @else
    <h3 class="my-3 text-center text-xl font-semibold">Sin ordenes eliminados</h3>
  @endif
@endsection
