@extends('layouts.dashboard')

@pushIf(auth()->check() && auth()->user()?->can('manage addresses'), 'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
<script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
@endpushIf

@php
  $type1 = 'addressDeleteRestore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Direcciones</x-slot:textTitle>

    @can('manage addresses')
      <button type="button" data-type="create" data-modalID="addressCSE"
        class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
        <x-icons.plus class="size-6" />
        Nueva Dirección
      </button>
    @endcan
  </x-sections.headerTitle>

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

    @forelse ($addresses as $index => $address)
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

              <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                @can('manage addresses')
                  <li>
                    <button type="button" data-type="show" data-uid="{{ $address->id }}" data-path="{{ $address->id }}"
                      data-modalID="addressCSE"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                      <span>
                        <x-icons.show class="size-5" />
                      </span>
                      Ver Dirección
                    </button>
                  </li>
                  <li>
                    <button type="button" data-type="edit" data-uid="{{ $address->id }}" data-path="{{ $address->id }}"
                      data-modalID="addressCSE"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
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
                @endcan
              </ul>
            </x-popups.contentWcheck>
          </div>
        </td>
      </tr>

    @empty
      <tr>
        <td colspan="8" class="text-center font-semibold text-slate-300 col-span-full">No hay direcciones registradas
        </td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $addresses->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  <h2 class="mb-5 mt-10 px-4 text-2xl font-semibold text-gray-300">Direcciones Eliminadas</h2>
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

    @forelse ($addressesDeleted as $index => $address)
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

              <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                @can('manage addresses')
                  <li>
                    <button type="button" data-type="show" data-uid="{{ $address->id }}" data-path="{{ $address->id }}"
                      data-modalID="addressCSE"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
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
                @endcan
              </ul>
            </x-popups.contentWcheck>
          </div>
        </td>
      </tr>

    @empty
      <tr>
        <td colspan="8" class="text-center font-semibold text-slate-300" colspan="3">No hay direcciones eliminadas
        </td>
      </tr>
    @endforelse
  </x-tables.table>
  </section>

  {{ $addressesDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  {{-- MODAL SHOW, EDIT --}}
  <x-modals.simple id="addressCSE"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')

      <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fill,minmax(210px,1fr))] gap-6 text-gray-700 md:px-3">
        @if (request()->routeIs('my.addresses.index'))
          <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
          <h3 class="my-3 font-semibold text-xl text-slate-900 text-center col-span-full">
            {{ auth()->user()->fullName() }}</h3>
        @else
          <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
            <label for="user" class="block ps-4 mb-2 font-semibold">Usuario</label>
            <select id="user" name="user_id"
              class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option value="" class="bg-slate-200 disabled:text-black" disabled selected>Selecciona un usuario
              </option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
              @endforeach
            </select>
          </div>
        @endif
        <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="street">Dirección</label>
          <input type="text" id="street" name="street" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="city">Ciudad</label>
          <input type="text" id="city" name="city" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="province">Provincia</label>
          <input type="text" id="province" name="province"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="postal_code">Código Postal</label>
          <input type="text" id="postal_code" name="postal_code" autocomplete="postal-code"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="name">Nombre</label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <section
          class="col-span-full py-3 flex flex-wrap gap-x-10 gap-y-5 items-center justify-center pointer-events-none group-[.editable]:pointer-events-auto">
          <p class="w-max">¿Establecer dirección por defecto?</p>
          <x-inputs.checkSwitch name="is_default"
            class="bg-slate-200 checked:bg-green-700 group-[.editable]:cursor-pointer"
            classLabel="z-10 bg-white border-slate-300 peer-checked/switch:border-green-700 group-[.editable]:cursor-pointer" />
        </section>
        <button type="submit"
          class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
      <button
        class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- Modal DELETE y RESTORE --}}
  <x-modals.delete id="{{ $type1 }}" />
@endsection
