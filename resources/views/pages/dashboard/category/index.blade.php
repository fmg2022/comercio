@extends('layouts.dashboard')

@pushIf(auth()->check() && auth()->user()?->can('manage products-and-attributes'), 'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
@endpushIf
@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
@endpush

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Categorías</x-slot:textTitle>

    <button type="button" data-type="create" data-modalID="categoryCSE"
      class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
      <x-icons.plus class="size-6" />
      Nueva Categoría
    </button>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Categoría</th>
        <th class="hidden sm:table-cell">Subcategorías</th>
        <th class="text-right">Opciones</th>
      </tr>
    </x-slot>

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
                class="w-48 py-2 {{ $category->trashed() ? 'bg-gray-800 text-gray-300' : 'bg-slate-800 text-slate-300 ' }} border border-slate-700 rounded-md font-semibold text-xs">
                <li>
                  <button type="button" data-type="show" data-uid="{{ $category->id }}" data-path="{{ $category->id }}"
                    data-modalID="categoryCSE"
                    class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                    <span>
                      <x-icons.show class="size-5" />
                    </span>
                    Ver Categoría
                  </button>
                </li>
                @can('manage products-and-attributes')
                  @if (!$category->trashed())
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
  </x-tables.table>

  {{ $categories->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  {{-- MODAL SHOW, EDIT, CREATE --}}
  <x-modals.simple id="categoryCSE"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @can('manage products-and-attributes')
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
            <option value="">-- Ninguna --</option>
            @foreach ($categoriesList as $category)
              <option value="{{ $category['id'] }}">
                {{ str_repeat('-', $category['nivel']) . ' ' . $category['name'] }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <h4 class="mb-2 text-xl font-semibold">Subcategorías</h4>
          <div class="max-h-60 flex flex-col overflow-x-auto">
            @foreach ($categoriesList as $category)
              <label @class([
                  'px-3 py-1 mx-4 flex items-center gap-2 group-[&:not(.editable)]:has-[input:not(:checked)]:hidden pointer-events-none group-[.editable]:pointer-events-auto',
                  'mt-3 text-purple-900 font-bold bg-slate-100 rounded-lg' =>
                      $category['nivel'] === 0,
                  'mt-2 text-purple-900 font-semibold' => $category['nivel'] === 1,
                  'bg-slate-100/75 rounded-lg' => $category['nivel'] === 2,
              ])>
                <input type="checkbox" name="children[]" class="size-4 accent-purple-600" value="{{ $category['id'] }}">
                <span class="ms-1">{{ $category['name'] }}</span>
              </label>
            @endforeach
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
  @can('manage products-and-attributes')
    <x-modals.delete id="categoryDeleteRestore" />
  @endcan
@endsection
