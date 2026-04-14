@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/addressModalMix.js') }}" defer></script>
@endpush

@php
  $type1 = 'addressDeleteRestore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Direcciones</x-slot:textTitle>

    <x-buttons.linkFill href="{{ route('addresses.create') }}"
      class="flex items-center gap-2 bg-purple-600 active:bg-purple-700">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M18 10h-4V6a2 2 0 0 0-4 0l.071 4H6a2 2 0 0 0 0 4l4.071-.071L10 18a2 2 0 0 0 4 0v-4.071L18 14a2 2 0 0 0 0-4" />
      </svg>
      Nuevo
    </x-buttons.linkFill>
  </x-sections.headerTitle>

  @if ($addresses->count() > 0)
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>#</th>
          <th>Usuario</th>
          <th>Dirección</th>
          <th>Ciudad</th>
          <th class="hidden sm:table-cell">Provincia</th>
          <th class="hidden md:table-cell">Código Postal</th>
          <th class="hidden lg:table-cell">Nombre</th>
          <th class="text-right">Opciones</th>
        </tr>
      </x-slot>

      @foreach ($addresses as $index => $address)
        <tr>
          <td>{{ ($addresses->currentPage() - 1) * $addresses->perPage() + $index + 1 }}</td>
          <td class="relative">
            <x-buttons.link href="{{ route('users.show', $address->user) }}"
              class="text-slate-100 hover:text-purple-500 peer/popup">
              {{ $address->user->fullName() }}
            </x-buttons.link>
            <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 peer-hover/popup:inline-block">
              Ver Usuario
            </x-popups.text>
          </td>
          <td>{{ $address->street }}</td>
          <td>{{ $address->city }}</td>
          <td class="hidden sm:table-cell">{{ $address->province }}</td>
          <td class="hidden md:table-cell">{{ $address->postal_code }}</td>
          <td class="hidden lg:table-cell">{{ $address->name }}</td>
          <td>
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chaddress-{{ $address->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:transition-colors">
                  <li>
                    <button type="button"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                      data-show="true" data-id="{{ $address->id }}">
                      <span>
                        <x-icons.show class="size-5" />
                      </span>
                      Ver Dirección
                    </button>
                  </li>
                  <li>
                    <button type="button"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                      data-show="false" data-id="{{ $address->id }}">
                      <span>
                        <x-icons.edit class="size-5" />
                      </span>
                      Editar Dirección
                    </button>
                  </li>
                  <li>
                    <button type="button" data-text="Dirección: '{{ $address->fullAddress() }}'"
                      data-uid="{{ $address->id }}" data-modalID="{{ $type1 }}" data-path="{{ $address->id }}"
                      data-delete="true"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                      <span>
                        <x-icons.trash class="size-5" />
                      </span>
                      Eliminar Dirección
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </div>
          </td>
        </tr>
      @endforeach
    </x-tables.table>

    {{ $addresses->onEachSide(1)->links('pages.dashboard.partials.pagination') }}
  @else
    <h3 class="my-3 text-center text-xl font-semibold">Sin Direcciones registradas</h3>
  @endif

  @if ($addressesDeleted->count() > 0)
    <section class="mt-10">
      <h2 class="mb-5 px-4 text-2xl font-semibold text-gray-300">Direcciones Eliminadas</h2>
      <x-tables.table>
        <x-slot:thead>
          <tr class="text-left">
            <th>#</th>
            <th>Usuario</th>
            <th>Dirección</th>
            <th>Ciudad</th>
            <th class="hidden sm:table-cell">Provincia</th>
            <th class="hidden md:table-cell">Código Postal</th>
            <th class="hidden lg:table-cell">Nombre</th>
            <th class="text-right">Opciones</th>
          </tr>
        </x-slot:thead>

        @foreach ($addressesDeleted as $index => $address)
          <tr>
            <td>{{ ($addressesDeleted->currentPage() - 1) * $addressesDeleted->perPage() + $index + 1 }}</td>
            <td class="relative">
              <x-buttons.link href="{{ route('users.show', $address->user) }}"
                class="text-slate-100 hover:text-purple-500 peer/popup">
                {{ $address->user->fullName() }}
              </x-buttons.link>
              <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 peer-hover/popup:inline-block">
                Ver Usuario
              </x-popups.text>
            </td>
            <td>{{ $address->street }}</td>
            <td>{{ $address->city }}</td>
            <td class="hidden sm:table-cell">{{ $address->province }}</td>
            <td class="hidden md:table-cell">{{ $address->postal_code }}</td>
            <td class="hidden lg:table-cell">{{ $address->name }}</td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chaddress-{{ $address->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:transition-colors">
                    <li>
                      <button type="button"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                        data-show="true" data-id="{{ $address->id }}">
                        <span>
                          <x-icons.show class="size-5" />
                        </span>
                        Ver Dirección
                      </button>
                    </li>
                    <li>
                      <button type="button" data-text="Dirección: '{{ $address->fullAddress() }}'"
                        data-uid="{{ $address->id }}" data-modalID="{{ $type1 }}"
                        data-path="{{ $address->id . '/restore' }}" data-delete="false"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          <x-icons.restore class="size-5" />
                        </span>
                        Restaurar Dirección
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

    {{ $addressesDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}
  @else
    <h3 class="my-3 text-center text-xl font-semibold">Sin Direcciones eliminadas</h3>
  @endif

  {{-- MODAL SHOW, EDIT --}}
  <x-modals.simple id="modal-address-mix"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form id="form-address-mix" enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')

      <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fill,minmax(210px,1fr))] gap-2 text-gray-700 md:px-3">
        <div class="mb-4 col-span-full">
          <label class="block mb-2 font-semibold" for="name">Nombre</label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4 col-span-full">
          <label class="block mb-2 font-semibold" for="street">Dirección</label>
          <input type="text" id="street" name="street" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="city">Ciudad</label>
          <input type="text" id="city" name="city" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="province">Provincia</label>
          <input type="text" id="province" name="province"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="postal_code">Código Postal</label>
          <input type="text" id="postal_code" name="postal_code"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <section class="mb-4 col-span-full flex flex-wrap gap-x-10 gap-y-5 items-center justify-center">
          <p class="w-max">¿Establecer dirección por defecto?</p>
          <x-inputs.checkSwitch
            class="bg-slate-200 checked:bg-green-700 pointer-events-none group-[.editable]:cursor-pointer group-[.editable]:pointer-events-auto"
            classLabel="z-10 bg-white border-slate-300 peer-checked/switch:border-green-700 pointer-events-none group-[.editable]:cursor-pointer group-[.editable]:pointer-events-auto" />
        </section>
        <button type="submit"
          class="absolute bottom-4 right-1/12 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-1/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-1/12 sm:left-1/5">
      <button
        class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- Modal DELETE y RESTORE --}}
  <x-modals.delete id="{{ $type1 }}" />
@endsection
