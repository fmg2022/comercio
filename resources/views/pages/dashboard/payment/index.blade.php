@extends('layouts.dashboard')

@can('manage state-type-tables')
  @push('scripts-dashboard')
    <script src="{{ asset('js/dashboard/modalStatus.js') }}" defer></script>

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
  @endpush

  @push('styles-dashboard')
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
  @endpush
@endcan

@php
  $type2 = 'modal-change-status';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Pagos</x-slot:textTitle>
    @can('manage state-type-tables')
      @can('manage state-type-tables')
        @if (!request()->routeIs('my.payments.index'))
          <button onclick="openModal('exportModal')"
            class="px-3 py-2 rounded-lg font-semibold bg-green-700 hover:bg-green-600 cursor-pointer">
            Exportar a Excel
          </button>
        @endif
      @endcan
    @endcan
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Orden</th>
        <th>M. Pago</th>
        <th>Fecha</th>
        <th class="hidden sm:table-cell">N° Cuota</th>
        <th>Monto</th>
        <th class="hidden md:table-cell">Estado</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($payments as $index => $payment)
      <tr>
        <td>{{ ($payments->currentPage() - 1) * $payments->perPage() + $index + 1 }}</td>
        <td class="font-bold">
          <x-buttons.link href="{{ route('orders.show', $payment->order->id) }}" class="text-purple-600 ">
            #{{ $payment->order->id }}
          </x-buttons.link>
        </td>
        <td class="text-slate-300">{{ $payment->paymentProvider->name }}</td>
        <td class="text-slate-300">{{ $payment->date_formated }}</td>
        <td class="hidden sm:table-cell">{{ $payment->nro_fee }}</td>
        <td class="sm:table-cell">{{ $payment->amount_formated }}</td>
        <td class="hidden md:table-cell">
          <span @class([
              "font-semibold before:content-['●'] before:me-px",
              'text-amber-400' => $payment->paymentState->code === 'PENDIENTE',
              'text-green-400' => $payment->paymentState->code === 'APROBADO',
              'text-blue-400' => $payment->paymentState->code === 'EN_PROCESO',
              'text-cyan-400' => $payment->paymentState->code === 'REEMBOLSADO',
              'text-lime-400' => $payment->paymentState->code === 'EXPIRADO',
              'text-rose-400' => $payment->paymentState->code === 'EN_DEVOLUCION',
              'text-purple-400' => $payment->paymentState->code === 'RECHAZADO',
              'text-red-400' => $payment->paymentState->code === 'CANCELADO',
          ])>
            {{ $payment->paymentState->codeFormated }}
          </span>
        </td>
        <td>
          @can('manage state-type-tables')
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chpayment-{{ $payment->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                  <li>
                    <button type="button" data-modal="{{ $type2 }}" data-uid="{{ $payment->id }}"
                      data-from="{{ $payment->paymentProvider->name }}" data-amount="{{ $payment->amount_formated }}"
                      data-status="{{ $payment->paymentState->code }}"
                      class="w-full px-4 py-2.5 flex gap-3 hover:bg-slate-700">
                      <span>
                        <x-icons.edit class="size-5" />
                      </span>
                      Cambiar Estado
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </div>
          @else
            <p class="px-2 text-end">---</p>
          @endcan
        </td>
      </tr>
    @empty
      <tr>
        <td class="text-center font-semibold text-slate-300 col-span-full">No hay pagos registradas</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $payments->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  {{-- Modal CHANGE STATUS --}}
  @can('manage state-type-tables')
    <x-modals.simple id="{{ $type2 }}" class="max-w-xl w-full" title="Cambiar el estado del pago">
      <div class="relative mt-4 flex flex-col items-center justify-center text-white">
        <form method="POST" class="w-full" id="form-modalSimple" action="{{ route('payments.updateStates', 0) }}">
          @csrf
          @method('PUT')
          <div class="mb-16 grid place-items-center text-slate-900">
            <div class="px-5 pb-4 flex gap-5 text-2xl">
              <div class="flex flex-col gap-5">
                <span class="text-lg text-slate-600">De:</span>
                <span class="text-lg text-slate-600">Monto:</span>
                <label class="text-lg text-slate-600" for="select_states">
                  Estado del pago:
                </label>
              </div>
              <div class="flex flex-col gap-4">
                <h3 class="font-bold"></h3>
                <p>$0</p>
                <select id="select_states" name="states"
                  class="outline-none px-2 py-1 rounded-md bg-slate-200 text-lg text-slate-900">
                  @foreach ($statuses as $states)
                    <option value="{{ $states->code }}">
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

    @if (!request()->routeIs('my.payments.index'))
      <x-modals.simple id="exportModal" title="Filtros para la Orden"
        class="max-w-lg w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
        <form id="exportForm" method="GET" action="{{ route('payments.export') }}" class="w-full">
          @csrf

          <div class="px-4 py-2 space-y-6">
            <div>
              <label for="users" class="block text-sm font-medium text-gray-700">Usuarios (múltiple)</label>
              <select name="users[]" id="users" multiple required>
                <option value="" selected>Todos</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->fullName() }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="states" class="block text-sm font-medium text-gray-700">Estados (múltiple)</label>
              <select name="states[]" id="states" multiple required>
                <option value="" selected>Todos</option>
                @foreach ($statuses as $state)
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
                <label for="amount_from" class="block text-sm font-medium text-gray-700">Monto desde</label>
                <p>
                  $
                  <input type="number" name="amount_from" id="amount_from" placeholder="0" min="0"
                    class="w-40 px-2 py-1 border-b-2 border-b-slate-300 rounded-md bg-slate-50 outline-none">
                </p>
              </div>

              <div>
                <label for="amount_to" class="block text-sm font-medium text-gray-700">Monto hasta</label>
                <p>
                  $
                  <input type="number" name="amount_to" id="amount_to" placeholder="1.000.000" min="0"
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
