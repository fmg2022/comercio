@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalStatus.js') }}" defer></script>
@endpush

{{-- Mostrar un mensaje para:
    - Los errores en las operaciones desde está página
    - El mensaje de éxito al crear un producto --}}

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
        $type2 = 'modal-change-status';
        $fullName = $order->user()->withTrashed()->first()->fullName();
      @endphp
      <tr>
        <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}</td>
        <td class="font-bold">{{ $fullName }}</td>
        <td class="text-slate-300">{{ $order->date_formated }}</td>
        <td><span class="me-px font-semibold">$</span>{{ $order->total_formated }}</td>
        <td class="hidden text-slate-300 capitalize md:table-cell">{{ $order->payment->paymentProvider->name }}</td>
        <td class="hidden md:table-cell">
          <span @class([
              "font-semibold before:content-['●'] before:me-px",
              'text-amber-400' => $order->orderState->code === 'CREADO',
              'text-blue-400' => $order->orderState->code === 'PENDIENTE',
              'text-cyan-400' => $order->orderState->code === 'PAGADO',
              'text-green-400' => $order->orderState->code === 'COMPLETO',
              'text-purple-400' => $order->orderState->code === 'REEMBOLSADO',
              'text-red-400' => $order->orderState->code === 'CANCELADO',
          ])>
            {{ $order->orderState->code }}
          </span>
        </td>
        <td>
          <div class="relative flex justify-end">
            <x-popups.contentWcheck iid="chorder-{{ $order->id }}" labelClass="hover:bg-slate-900"
              class="right-12 -top-1/4">
              <x-slot:label>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                </svg>
              </x-slot:label>

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
                  <button type="button" data-modal="{{ $type2 }}" data-uid="{{ $order->id }}"
                    data-from="{{ $fullName }}" data-amount="{{ $order->total_formated }}"
                    data-status="{{ $order->orderState->code }}"
                    class="w-full px-4 py-2.5 flex gap-3 hover:bg-slate-700">
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
                </li>
              </ul>
            </x-popups.contentWcheck>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center font-semibold text-slate-300">No hay ordenes registradas</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $orders->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  {{-- Modal CHANGE STATUS --}}
  <x-modals.simple id="{{ $type2 }}" class="max-w-xl w-full" title="Cambiar el estado de la orden">
    <div class="relative mt-4 flex flex-col items-center justify-center text-white">
      <form method="POST" class="w-full" id="form-modalSimple">
        @csrf
        @method('PUT')
        <div class="mb-16 grid place-items-center text-slate-900">
          <div class="px-5 pb-4 flex gap-5 text-2xl">
            <div class="flex flex-col gap-5">
              <span class="text-lg text-slate-600">De:</span>
              <span class="text-lg text-slate-600">Monto:</span>
              <label class="text-lg text-slate-600" for="select_states">
                Estado de la orden:
              </label>
            </div>
            <div class="flex flex-col gap-4">
              <h3 class="font-bold"></h3>
              <p>$0</p>
              <select id="select_states" name="states"
                class="outline-none px-2 py-1 rounded-md bg-slate-200 text-lg text-slate-900">
                @foreach ($orderStates as $states)
                  <option value="{{ $states->id }}">
                    {{ $states->code }}
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
        <button class="px-3 py-2 bg-red-700 text-lg rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
      </form>
    </div>
  </x-modals.simple>
@endsection
