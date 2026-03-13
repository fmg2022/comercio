@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/paymentModalMix.js') }}" defer></script>
@endpush

@php
  $type1 = 'modal-delete-restore';
@endphp

@section('content')
  <x-sections.headerTitle>
    <x-slot:textTitle>Pagos</x-slot:textTitle>
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
        <td class="hidden sm:table-cell">{{ $payment->nr_fee }}</td>
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
          <div class="relative flex justify-end">
            <x-popups.contentWcheck iid="chpayment-{{ $payment->id }}" labelClass="hover:bg-slate-900"
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
                  <button type="button"
                    class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                    data-type="edit" data-id="{{ $payment->id }}">
                    <span>
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2">
                          <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                          <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                        </g>
                      </svg>
                    </span>
                    Editar
                  </button>
                </li>
                <li>
                  <button type="button" data-uid="{{ $payment->id }}" data-modal="{{ $type1 }}"
                    data-title="Pago #{{ $payment->id . ' (' . $payment->date_formated }})" data-button="Eliminar"
                    data-text="¿Está seguro de que desea eliminalo?"
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
                    Eliminar Pago
                  </button>
                </li>
              </ul>
            </x-popups.contentWcheck>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td class="text-center font-semibold text-slate-300 col-span-full">No hay pagos registradas</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $payments->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  @if ($paymentsDeleted->count() > 0)
    <section class="mt-10">
      <h2 class="mb-5 px-4 text-2xl font-semibold text-gray-300">Pagos Eliminados</h2>
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
        </x-slot:thead>

        @forelse ($paymentsDeleted as $index => $payment)
          <tr>
            <td>{{ ($payments->currentPage() - 1) * $payments->perPage() + $index + 1 }}</td>
            <td class="font-bold">
              <x-buttons.link href="{{ route('orders.show', $payment->order->id) }}" class="text-purple-600 ">
                #{{ $payment->order->id }}
              </x-buttons.link>
            </td>
            <td class="text-slate-300">{{ $payment->paymentProvider->name }}</td>
            <td class="text-slate-300">{{ $payment->date_formated }}</td>
            <td class="hidden sm:table-cell">{{ $payment->nr_fee }}</td>
            <td class="sm:table-cell">{{ $payment->amount_formated }}</td>
            <td class="hidden md:table-cell">
              <span class="text-gray-300 font-semibold before:content-['●'] before:me-px">
                {{ $payment->paymentState->codeFormated }}
              </span>
            </td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chpayment-{{ $payment->id }}" labelClass="hover:bg-slate-900"
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
                      <button type="button" data-uid="{{ $payment->id }}" data-modal="{{ $type1 }}"
                        data-title="Pago #{{ $payment->id . ' (' . $payment->date_formated }})" data-button="Restaurar"
                        data-text="¿Está seguro de que desea eliminalo?"
                        class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 ">
                        <span>
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                            <path fill="currentColor" fill-rule="evenodd"
                              d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                              clip-rule="evenodd" />
                          </svg>
                        </span>
                        Restaurar Pago
                      </button>
                    </li>
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @endforeach
      </x-tables.table>
    </section>

    {{ $paymentsDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}
  @else
    <h3 class="my-3 text-center text-xl font-semibold">Sin Pagos eliminados</h3>
  @endif

  {{-- MODAL EDIT --}}
  <x-modals.simple id="modal-payment-mix"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form id="form-payment-mix" enctype="multipart/form-data" method="POST"
      class="w-full mb-12 flex flex-col gap-4 items-center justify-center">
      @csrf
      @method('PUT')

      <fieldset class="w-full py-3 flex flex-col gap-2 text-gray-700 md:px-3">
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="paid_at">Fecha</label>
          <input type="date" id="paid_at" name="paid_at" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="amount">Monto</label>
          <input id="amount" name="amount" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="method">Método</label>
          <input id="method" name="method" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="status">Estado del Pago</label>
          <select name="status" id="status"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md">
            <option value="">Ninguna</option>
            @foreach ($statuses as $status)
              <option value="{{ $status->id }}">{{ $status->name }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit"
          class="absolute bottom-4 right-1/12 px-3 py-2 bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-1/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="absolute bottom-4 left-1/12 sm:left-1/5">
      <button
        class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- Modal DELETE y RESTORE --}}
  <x-modals.delete id="{{ $type1 }}" class="max-w-md" iconClass="text-slate-500">
    <x-slot:icon>
      <svg xmlns="http://www.w3.org/2000/svg" width="112" height="112" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
      </svg>
    </x-slot:icon>
  </x-modals.delete>
@endsection
