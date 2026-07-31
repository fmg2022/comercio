@extends('layouts.dashboard')

@pushIf(auth()->user()?->can('manage providers'), 'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
<script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
@endPushIf

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Proveedores</x-slot:textTitle>

    @can('manage providers')
      <button type="button" data-type="create" data-modalID="providerCES"
        class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
        <x-icons.plus class="size-6" />
        Nuevo Proveedor
      </button>
    @endcan
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Empresa</th>
        <th class="hidden lg:table-cell">Teléfono</th>
        <th class="hidden sm:table-cell">Nombre Contacto</th>
        <th class="hidden md:table-cell">Telefono Contacto</th>
        <th>N° Productos</th>
        <th>Estado</th>
        <th class="text-right">Opciones</th>
      </tr>
    </x-slot:thead>
    <x-slot:tbody>
      @forelse ($providers as $index => $provider)
        <tr {{ $provider->trashed() ? 'data-trash' : '' }}
          class="data-trash:[&>td]:bg-gray-700 data-trash:[&>td]:text-gray-300">
          <td>{{ ($providers->currentPage() - 1) * $providers->perPage() + $index + 1 }}</td>
          <td class="font-bold">{{ $provider->trade_name }}</td>
          <td class="hidden lg:table-cell">{{ $provider->phone }}</td>
          <td class="hidden sm:table-cell">{{ $provider->contact_name }}</td>
          <td class="hidden md:table-cell">{{ $provider->contact_phone }}</td>
          <td class="text-center">{{ $provider->products->count() }}</td>
          <td>{{ $provider->active ? 'Activo' : 'Inactivo' }}</td>
          <td>
            @can('manage providers')
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chprovider-{{ $provider->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-max py-2 {{ $provider->trashed() ? 'bg-gray-800 text-gray-300' : 'bg-slate-800 text-slate-300 ' }} border border-slate-700 rounded-md font-semibold">
                    <li>
                      <button type="button" data-type="show" data-uid="{{ $provider->id }}"
                        data-path="{{ $provider->id }}" data-modalID="providerCES"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Ver Proveedor
                      </button>
                    </li>
                    @if (!$provider->trashed())
                      <li>
                        <button type="button" data-type="edit" data-uid="{{ $provider->id }}"
                          data-path="{{ $provider->id }}" data-modalID="providerCES"
                          class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                          <span>
                            <x-icons.edit class="size-5" />
                          </span>
                          Editar Proveedor
                        </button>
                      </li>
                    @endif
                    <li>
                      <button type="button" data-text="Proveedor: '{{ $provider->name }}'" data-uid="{{ $provider->id }}"
                        data-modalID="providerDelete"
                        data-path="{{ $provider->id . ($provider->trashed() ? '/restore' : '') }}"
                        data-delete="{{ $provider->trashed() ? 'false' : 'true' }}"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          @if ($provider->trashed())
                            <x-icons.restore class="size-5" />
                          @else
                            <x-icons.trash class="size-5" />
                          @endif
                        </span>
                        {{ $provider->trashed() ? 'Restaurar' : 'Eliminar' }} Proveedor
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
          <td class="text-center font-semibold text-slate-300 col-span-full">No hay proveedores registrados</td>
        </tr>
      @endforelse
    </x-slot:tbody>
  </x-tables.table>

  @can('manage providers')
    {{-- MODAL SHOW, EDIT --}}
    <x-modals.simple id="providerCES"
      class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
      <form enctype="multipart/form-data" method="POST"
        class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
        @csrf
        @method('PUT')
        <fieldset class="py-3 grid grid-cols-2 gap-6 text-gray-700 md:px-3">
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="trade_name">Empresa</label>
            <input type="text" id="trade_name" name="trade_name" autocomplete="off"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="phone">Teléfono</label>
            <input type="text" id="phone" name="phone" autocomplete="tel"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="email">Email</label>
            <input type="email" id="email" name="email" autocomplete="email"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="address_full">Dirección</label>
            <input type="text" id="address_full" name="address_full" autocomplete="address-line1"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="contact_name">Nombre de Contacto</label>
            <input type="text" id="contact_name" name="contact_name" autocomplete="name"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="contact_phone">Teléfono de Contacto</label>
            <input type="text" id="contact_phone" name="contact_phone" autocomplete="tel"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="contact_email">Email de Contacto</label>
            <input type="text" id="contact_email" name="contact_email" autocomplete="tel"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="col-span-full">
            <h4 class="mb-2 text-xl font-semibold">Productos Disponibles</h4>
            <div class="max-h-60 flex flex-col overflow-x-auto">
              @foreach ($products as $product)
                <label
                  class="px-3 py-1 mx-4 flex items-center gap-2 group-[&:not(.editable)]:has-[input:not(:checked)]:hidden pointer-events-none group-[.editable]:pointer-events-auto">
                  <input type="checkbox" name="products_ids[]" class="size-4 accent-purple-600"
                    value="{{ $product->id }}">
                  <span class="ms-1">{{ $product->name }}</span>
                </label>
              @endforeach
            </div>
          </div>
          <section
            class="col-span-full py-3 flex flex-wrap gap-x-10 gap-y-5 items-center justify-center pointer-events-none group-[.editable]:pointer-events-auto">
            <p class="w-max">¿Establecer proveedor por defecto?</p>
            <x-inputs.checkSwitch name="active"
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

    {{-- MODAL DELETE, RESTORE --}}
    <x-modals.delete id="providerDelete" />
  @endcan
@endsection
