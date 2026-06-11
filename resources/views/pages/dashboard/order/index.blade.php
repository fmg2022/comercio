@extends('layouts.dashboard')

@can('manage state-type-tables')
  @push('scripts-dashboard')
    <script src="{{ asset('js/dashboard/modalStatus.js') }}" defer></script>
    @if (!request()->routeIs('my.orders.index'))
      <script src="{{ asset('js/modal.js') }}" defer></script>

      <script type="module">
        document.getElementById('exportForm').addEventListener('submit', function(e) {
          e.preventDefault();

          const form = this;
          const formData = new FormData(form);
          const exportBtn = form.querySelector('#exportBtn');
          const originalText = exportBtn.innerText;

          exportBtn.disabled = true;
          exportBtn.innerText = 'Verificando...';

          fetch("{{ route('orders.count') }}", {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
              },
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              if (data.count === 0) {
                alert('⚠️ Sin ordenes para exportar a Excel.');
              } else {
                form.submit();
                form.reset();
                document.getElementById('exportModal').close();
              }
              exportBtn.disabled = false;
              exportBtn.innerText = originalText;
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Ocurrió un error al verificar las órdenes. Inténtalo de nuevo.');
              exportBtn.disabled = false;
              exportBtn.innerText = originalText;
            });
        });
      </script>

      <script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const userSelect = new Choices('#users', {
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar usuario...',
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'Selecciona uno o más usuarios',
            shouldSort: false,
          });

          const stateSelect = new Choices('#states', {
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'Selecciona uno o más estados',
            shouldSort: false,
          });
        })
      </script>
    @endif
  @endPush

  @pushIf(!request()->routeIs('my.payments.index'), 'styles-dashboard')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css" />
  <style>
    .choices__inner {
      background-color: #fff !important;
      border-color: #e2e8f0 !important;
      border-radius: 0.375rem !important;
      min-height: 42px !important;
    }

    .choices__list--dropdown {
      z-index: 10 !important;
    }
  </style>
  @endPushIf
@endcan

{{-- https://github.com/Choices-js/Choices --}}
{{-- Mostrar un mensaje para:
    - Los errores en las operaciones desde está página
    - El mensaje de éxito al crear un producto --}}

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>{{ request()->routeIs('my.orders.index') ? 'Mis' : '' }} Ordenes</x-slot:textTitle>
    @can('manage state-type-tables')
      @if (!request()->routeIs('my.orders.index'))
        <div>
          @if (request()->routeIs('orders.filter'))
            <x-buttons.linkFill href="{{ route('orders.index') }}"
              class="py-2.5 me-4 font-semibold bg-slate-700 hover:bg-slate-600">
              Volver a la lista
            </x-buttons.linkFill>
          @endif

          <button onclick="openModal('exportModal')"
            class="px-3 py-2 rounded-lg font-semibold bg-green-700 hover:bg-green-600 cursor-pointer">
            Exportar a Excel
          </button>
        </div>
      @endif
    @endcan
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        @if (!request()->routeIs('my.orders.index'))
          <th>Usuario</th>
        @endif
        <th>Fecha</th>
        <th>Total</th>
        <th class="hidden lg:table-cell">M. Pago</th>
        <th class="hidden md:table-cell">Estado</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($orders as $index => $order)
      @php
        $fullName = $order->user()->withTrashed()->first()->fullName();
      @endphp
      <tr>
        <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}</td>
        @if (!request()->routeIs('my.orders.index'))
          <td class="font-bold">{{ $fullName }}</td>
        @endif
        <td class="text-slate-300">{{ $order->date->format('d/m/Y H:i') }}</td>
        <td><span class="me-px font-semibold">$</span>{{ $order->total_formated }}</td>
        <td class="hidden text-slate-300 capitalize lg:table-cell">
          {{ $order->payment?->paymentProvider->name ?? 'Sin pago' }}</td>
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
                <x-icons.threeDotsX class="size-6" />
              </x-slot:label>

              <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                @can('show orders')
                  @php
                    $route = request()->routeIs('my.orders.index') ? 'my.orders.show' : 'orders.show';
                  @endphp
                  <li>
                    <a href="{{ route($route, $order->id) }}"
                      class="px-4 py-2.5 flex items-center gap-3 hover:bg-slate-700">
                      <span>
                        <x-icons.show class="size-5" />
                      </span>
                      Detalles
                    </a>
                  </li>
                @endcan
                @can('manage state-type-tables')
                  <li>
                    <button type="button" data-modal="modal-change-status" data-uid="{{ $order->id }}"
                      data-from="{{ $fullName }}" data-amount="{{ $order->total_formated }}"
                      data-status="{{ $order->orderState->code }}"
                      class="w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-700">
                      <span>
                        <x-icons.edit class="size-5" />
                      </span>
                      Cambiar Estado
                    </button>
                  </li>
                @endcan
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
  @can('manage state-type-tables')
    <x-modals.simple id="modal-change-status" class="max-w-md w-full" title="Cambiar el estado de la orden">
      <div class="relative mt-4 flex flex-col items-center justify-center text-white">
        <form method="POST" class="w-full" id="form-modalSimple" action="{{ route('orders.updateStates', 0) }}">
          @csrf
          @method('PUT')
          <div class="mb-16 grid place-items-center text-slate-900">
            <div class="px-5 pb-4 flex gap-5">
              <div class="flex flex-col gap-3 justify-between text-lg text-slate-700">
                <span>De:</span>
                <span>Monto:</span>
                <span class="py-2">Estado de la orden:</span>
              </div>
              <div class="flex flex-col gap-3 justify-between text-base">
                <h3 class="font-bold"></h3>
                <div class="font-semibold">
                  <span>$ </span>
                  <p class="inline-block">0</p>
                </div>
                <select id="select_states" name="states"
                  class="outline-none px-3 py-2 rounded-md border border-slate-300 text-slate-900">
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
            class="absolute bottom-0 left-1/2 px-3 py-2 bg-green-800 text-lg rounded-md hover:bg-green-700 cursor-pointer">Actualizar</button>
        </form>
        <form method="dialog" class="absolute bottom-0 right-1/2 -translate-x-3">
          <button class="px-3 py-2 bg-red-800 text-lg rounded-md hover:bg-red-700 cursor-pointer">Cancelar</button>
        </form>
      </div>
    </x-modals.simple>

    @if (!request()->routeIs('my.orders.index'))
      <x-modals.simple id="exportModal" title="Filtros para la Orden"
        class="max-w-lg w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
        <form id="exportForm" method="GET" action="{{ route('orders.export') }}" class="w-full">
          @csrf

          <div class="px-4 py-2 space-y-6">
            <div>
              <label for="users" class="block text-sm font-medium text-gray-700">Usuarios (múltiple)</label>
              <div class="mt-2">
                <select name="users[]" id="users" multiple required>
                  <option value="" selected>Todos</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->fullName() }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div>
              <label for="states" class="block text-sm font-medium text-gray-700">Estados (múltiple)</label>
              <select name="states[]" id="states" multiple required>
                <option value="" selected>Todos</option>
                @foreach ($orderStates as $state)
                  <option value="{{ $state->id }}">{{ $state->code }}</option>
                @endforeach
              </select>
            </div>

            <div class="flex flex-wrap justify-around items-center">
              <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">Desde fecha</label>
                <input type="date" name="date_from" id="date_from"
                  class="w-44 px-2 py-1 border border-slate-200 rounded-md outline-none">
              </div>

              <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">Hasta fecha</label>
                <input type="date" name="date_to" id="date_to" value="{{ now()->format('Y-m-d') }}"
                  class="w-44 px-2 py-1 border border-slate-200 rounded-md outline-none">
              </div>
            </div>

            <div class="flex flex-wrap justify-around items-center">
              <div>
                <label for="total_from" class="block text-sm font-medium text-gray-700">Monto desde</label>
                <p>
                  $
                  <input type="number" name="total_from" id="total_from" placeholder="0" min="0"
                    class="w-40 px-2 py-1 border-b-2 border-b-slate-300 rounded-md bg-slate-50 outline-none">
                </p>
              </div>

              <div>
                <label for="total_to" class="block text-sm font-medium text-gray-700">Monto hasta</label>
                <p>
                  $
                  <input type="number" name="total_to" id="total_to" placeholder="1.000.000" min="0"
                    class="w-40 px-2 py-1 border-b-2 border-b-slate-300 rounded-md bg-slate-50 outline-none">
                </p>
              </div>
            </div>
            <div class="pt-3 flex justify-end gap-6 text-white">
              <button type="reset"
                class="px-3 py-2 bg-slate-600 font-semibold rounded-md hover:bg-slate-500 cursor-pointer">Limpiar</button>
              <button type="submit" id="exportBtn"
                class="px-3 py-2 bg-green-700 font-semibold rounded-md hover:bg-green-600 cursor-pointer">
                Exportar
              </button>
            </div>
          </div>
        </form>
      </x-modals.simple>
    @endif
  @endcan
@endsection
