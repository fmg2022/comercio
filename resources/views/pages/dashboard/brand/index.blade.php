@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
@endPush

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Estados y Tipos de Secciones</x-slot:textTitle>

    <button type="button" data-type="create" data-modalID="brandCSE"
      class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
      <x-icons.plus class="size-6" />
      Nueva Marca
    </button>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Name</th>
        <th class="z-10 text-end">Opciones</th>
      </tr>
    </x-slot:head>
    <x-slot:tbody>
      @forelse ($brands as $index => $brand)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="font-bold">{{ $brand->name }}</td>
          <td>
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chbrand-{{ $brand->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                  <li>
                    <button type="button" data-type="edit" data-uid="{{ $brand->id }}" data-path="{{ $brand->id }}"
                      data-modalID="brandCSE"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                      <span>
                        <x-icons.edit class="size-5" />
                      </span>
                      Editar Marca
                    </button>
                  </li>
                  <li>
                    <button type="button" data-text="Marca: '{{ $brand->name }}'" data-uid="{{ $brand->id }}"
                      data-modalID="brandDelete" data-path="{{ $brand->id }}" data-delete="true"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                      <span>
                        <x-icons.trash class="size-5" />
                      </span>
                      Eliminar Marca
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td class="text-center font-semibold text-slate-300 col-span-full">No hay Marcas de productos registrados</td>
        </tr>
      @endforelse
    </x-slot:tbody>
  </x-tables.table>
  {{ $brands->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  <h2 class="mb-5 mt-10 px-4 text-2xl font-semibold text-gray-300">Marcas Eliminadas</h2>
  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Name</th>
        <th class="z-10 text-end">Opciones</th>
      </tr>
    </x-slot:head>
    <x-slot:tbody>
      @forelse ($brandsDeleted as $index => $brand)
        <tr class="text-slate-400">
          <td>{{ $index + 1 }}</td>
          <td class="font-bold">{{ $brand->name }}</td>
          <td>
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chbrandDeleted-{{ $brand->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                  <li>
                    <button type="button" data-text="Marca: '{{ $brand->name }}'" data-uid="{{ $brand->id }}"
                      data-modalID="brandDelete" data-path="{{ $brand->id }}/restore" data-delete="false"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                      <span>
                        <x-icons.restore class="size-5" />
                      </span>
                      Restaurar Marca
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td class="text-center font-semibold text-slate-300" colspan="3">No hay Marcas de productos eliminados</td>
        </tr>
      @endforelse
    </x-slot:tbody>
  </x-tables.table>
  {{ $brandsDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  {{-- MODAL CREATE, EDIT --}}
  <x-modals.simple id="brandCSE"
    class="max-w-xs w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
    <form enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')
      <fieldset class="py-3 grid grid-cols-[repeat(auto-fill,minmax(250px,1fr))] gap-2 text-gray-700 md:px-3">
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="name">Nombre</label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <button type="submit"
          class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
      <button class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  <x-modals.delete id="brandDelete" />
@endsection
