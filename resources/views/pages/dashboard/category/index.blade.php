@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/categoryModalMix.js') }}" defer></script>
@endpush

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Categorías</x-slot:textTitle>

    <button type="button"
      class="px-4 py-2 rounded-md cursor-pointer flex items-center gap-2 bg-purple-600 active:bg-purple-700"
      data-type="create" data-id="0">
      <x-icons.plus class="size-6" />
      Nueva Categoría
    </button>
  </x-sections.headerTitle>

  @if ($categories->count() > 0)
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>#</th>
          <th>Categoría</th>
          <th class="hidden sm:table-cell">Subcategorías</th>
          <th class="text-right">Opciones</th>
        </tr>
      </x-slot>

      @foreach ($categories as $index => $category)
        <tr {{ $category->trashed() ? 'data-trash' : '' }}
          class="data-trash:[&>td]:bg-gray-700 data-trash:[&>td]:text-gray-300">
          <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</td>
          <td>{{ $category->name }}</td>
          <td class="relative hidden sm:table-cell">
            @if ($category->children->count() > 0 && !$category->trashed())
              <x-popups.contentWcheck iid="chcategory-children-{{ $category->id }}"
                class="left-0 top-full md:top-1/2 md:-translate-y-1/2 md:left-36"
                labelClass="underline-offset-4 hover:underline hover:text-purple-500">
                <x-slot:label>Ver subcategorías</x-slot:label>

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:transition-colors">
                  @foreach ($category->children as $item)
                    <li class="w-full px-4 py-2.5 flex gap-3 hover:bg-slate-700 transition-colors">
                      {{ $item->name }}</li>
                  @endforeach
                </ul>
              </x-popups.contentWcheck>
            @else
              <span class="text-center">---</span>
            @endif
          </td>
          <td>
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chcategory-{{ $category->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-48 py-2 {{ $category->trashed() ? 'bg-gray-800 [&>li]:bg-gray-800 text-gray-300' : 'bg-slate-800 [&>li]:bg-slate-800 text-slate-300 ' }} border border-slate-700 rounded-md text-xsfont-semibold [&>li]:transition-colors">
                  <li>
                    <button type="button"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                      data-type="show" data-id="{{ $category->id }}">
                      <span>
                        <x-icons.show class="size-5" />
                      </span>
                      Ver
                    </button>
                  </li>
                  @if (!$category->trashed())
                    <li>
                      <button type="button"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                        data-type="edit" data-id="{{ $category->id }}">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar
                      </button>
                    </li>
                  @endif
                  <li>
                    <button type="button" data-text="Categoria: '{{ $category->name }}'" data-uid="{{ $category->id }}"
                      data-modalID="categoryDeleteRestore"
                      data-path="{{ $category->id . $category->trashed() ? '/restore' : '' }}" data-delete="true"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                      <span>
                        @if ($category->trashed())
                          <x-icons.restore class="size-5" />
                        @else
                          <x-icons.trash class="size-5" />
                        @endif
                      </span>
                      {{ $category->trashed() ? 'Restaurar' : 'Eliminar' }}
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </div>
          </td>
        </tr>
      @endforeach
    </x-tables.table>

    {{ $categories->onEachSide(1)->links('pages.dashboard.partials.pagination') }}
  @else
    <h3 class="my-3 text-center text-xl font-semibold">Sin Categorías registradas</h3>
  @endif

  {{-- MODAL SHOW, EDIT, CREATE --}}
  <x-modals.simple id="modal-category-mix"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form id="form-category-mix" enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')

      <fieldset class="w-full py-3 flex flex-col gap-2 text-gray-700 md:px-3">
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="name">Nombre</label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
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
        <div class="mb-4">
          <h4 class="mb-2 text-xl font-semibold">Subcategorías</h4>
          <div class="max-h-52 flex flex-col overflow-x-auto" id="categories-list">
            <h3 class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md outline-none">
              Ninguna</h3>
            @foreach ($categoriesList as $category)
              <label @class([
                  'px-3 py-1 mx-4 flex items-center gap-2 [&:has(:disabled:not(:checked))]:hidden',
                  'mt-3 text-purple-900 font-bold' => $category['nivel'] === 0,
                  'mt-2 border border-slate-200 rounded-md text-purple-900 font-semibold' =>
                      $category['nivel'] === 1,
                  'bg-slate-100/75 rounded-md' => $category['nivel'] === 2,
              ])>
                <input type="checkbox" name="children[]" class="size-4 accent-purple-600" value="{{ $category['id'] }}">
                <span class="ms-1">{{ $category['name'] }}</span>
              </label>
            @endforeach
          </div>
        </div>
        <button type="submit"
          class="absolute bottom-4 right-1/6 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-1/3">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-1/6 sm:left-1/3">
      <button class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- Modal DELETE y RESTORE --}}
  <x-modals.delete id="categoryDeleteRestore" />
@endsection
