@extends('layouts.dashboard')

@pushIf(auth()->user()?->can('delete_product_attributes'), 'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
@endPushIf
@can('view_any_product_attributes')
  @push('scripts-dashboard')
    <script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
    <script>
      const choicesInstances = {};

      document.addEventListener('DOMContentLoaded', function() {
        const selectsConfig = [{
            id: 'parent',
            placeholder: 'Selecciona una categoría',
            searchPlaceholderValue: 'Buscar categoría...',
            searchEnabled: true,
          },
          {
            id: 'children',
            placeholder: 'Selecciona uno o más categorías',
            searchEnabled: false,
          },
        ]
        selectsConfig.forEach(config => {
          const choicesOptions = {
            placeholderValue: config.placeholder,
            searchPlaceholderValue: config.searchPlaceholderValue,
            searchEnabled: config.searchEnabled,
            removeItemButton: true,
            placeholder: true,
            shouldSort: false,
          };

          choicesInstances[config.id] = new Choices(`#${config.id}`, choicesOptions);
        });
      })
    </script>
  @endPush

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
  @endPush
@endcan

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Categorías</x-slot:textTitle>

    @can('create_product_attributes')
      <button type="button" data-type="create" data-modalID="categoryCSE"
        class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
        <x-icons.plus class="size-6" />
        Nueva Categoría
      </button>
    @endcan
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Categoría</th>
        <th class="hidden sm:table-cell">Subcategorías</th>
        <th class="text-right">Opciones</th>
      </tr>
    </x-slot:head>
    <x-slot:tbody>
      @forelse ($categories as $index => $category)
        <tr {{ $category->trashed() ? 'data-trash' : '' }}
          class="data-trash:[&>td]:bg-gray-700 data-trash:[&>td]:text-gray-300">
          <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</td>
          <td>{{ $category->name }}</td>
          <td class="relative hidden sm:table-cell">
            @php
              $contChildren = $category->children->count();
            @endphp
            <span {{ $category->trashed() ? 'class="px-4 text-gray-300"' : '' }}>
              @if ($category->trashed())
                ---
              @else
                {{ $contChildren > 0 ? $contChildren : 'Sin' }} subcategorías
              @endif
            </span>
          </td>
          <td>
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chcategory-{{ $category->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-max py-2 {{ $category->trashed() ? 'bg-gray-800 text-gray-300' : 'bg-slate-800 text-slate-300 ' }} border border-slate-700 rounded-md font-semibold text-xs">
                  @can('view_any_product_attributes')
                    <li>
                      <button type="button" data-type="show" data-uid="{{ $category->id }}"
                        data-path="{{ $category->id }}" data-modalID="categoryCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.show class="size-5" />
                        </span>
                        Ver Categoría
                      </button>
                    </li>
                  @endcan
                  @if (!$category->trashed() && auth()->user()->can('update_product_attributes'))
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $category->id }}"
                        data-path="{{ $category->id }}" data-modalID="categoryCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar Categoría
                      </button>
                    </li>
                  @endif
                  @can('delete_product_attributes')
                    <li>
                      <button type="button" data-text="Categoria: '{{ $category->name }}'" data-uid="{{ $category->id }}"
                        data-modalID="categoryDeleteRestore" data-delete="{{ $category->trashed() ? 'false' : 'true' }}"
                        data-path="{{ $category->id . ($category->trashed() ? '/restore' : '') }}"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          @if ($category->trashed())
                            <x-icons.restore class="size-5" />
                          @else
                            <x-icons.trash class="size-5" />
                          @endif
                        </span>
                        {{ $category->trashed() ? 'Restaurar' : 'Eliminar' }} Categoría
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
          <td colspan="4" class="text-center font-semibold text-slate-300 col-span-full">No hay Categorías registradas
          </td>
        </tr>
      @endforelse
    </x-slot:tbody>
  </x-tables.table>

  {{ $categories->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  {{-- MODAL SHOW, EDIT, CREATE --}}
  <x-modals.simple id="categoryCSE"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
    <form enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @can('update_product_attributes')
        @csrf
        @method('PUT')
      @endcan

      <fieldset class="w-full py-3 flex flex-col gap-6 text-gray-700 md:px-3">
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="name">Nombre</label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="parent">Categoría Padre</label>
          <select name="parent_id" id="parent"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md">
            <option value="" selected>-- Ninguna --</option>
            @foreach ($categoriesList as $category)
              <option value="{{ $category['id'] }}">
                {{ str_repeat('-', $category['nivel']) . ' ' . $category['name'] }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <h4 class="mb-2 text-xl font-semibold">Subcategorías</h4>
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label for="children" class="block text-sm font-medium text-gray-700">Estados (múltiple)</label>
            <select name="children[]" id="children" multiple>
              <option value="" selected>Ninguno</option>
              @foreach ($categoriesList as $category)
                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <button type="submit"
          class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
      <button class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- Modal DELETE y RESTORE --}}
  @can('delete_product_attributes')
    <x-modals.delete id="categoryDeleteRestore" />
  @endcan
@endsection
