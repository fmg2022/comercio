@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/categoryModalMix.js') }}" defer></script>
@endpush

@php
  $type1 = 'modal-delete-restore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Categorías</x-slot:textTitle>

    <button type="button"
      class="px-4 py-2 rounded-md cursor-pointer flex items-center gap-2 bg-purple-600 active:bg-purple-700"
      data-type="create" data-id="0">
      <span>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M18 10h-4V6a2 2 0 0 0-4 0l.071 4H6a2 2 0 0 0 0 4l4.071-.071L10 18a2 2 0 0 0 4 0v-4.071L18 14a2 2 0 0 0 0-4" />
        </svg>
      </span>
      Nuevo
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
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                  </svg>
                </x-slot:label>

                <ul
                  class="w-48 py-2 {{ $category->trashed() ? 'bg-gray-800 [&>li]:bg-gray-800 text-gray-300' : 'bg-slate-800 [&>li]:bg-slate-800 text-slate-300 ' }} border border-slate-700 rounded-md text-xsfont-semibold [&>li]:transition-colors">
                  <li>
                    <button type="button"
                      class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                      data-type="show" data-id="{{ $category->id }}">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path
                              d="M3.587 13.779c1.78 1.769 4.883 4.22 8.413 4.22s6.634-2.451 8.413-4.22c.47-.467.705-.7.854-1.159c.107-.327.107-.913 0-1.24c-.15-.458-.385-.692-.854-1.159C18.633 8.452 15.531 6 12 6c-3.53 0-6.634 2.452-8.413 4.221c-.47.467-.705.7-.854 1.159c-.107.327-.107.913 0 1.24c.15.458.384.692.854 1.159" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                          </g>
                        </svg>
                      </span>
                      Ver
                    </button>
                  </li>
                  @if (!$category->trashed())
                    <li>
                      <button type="button"
                        class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                        data-type="edit" data-id="{{ $category->id }}">
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
                  @endif
                  <li>
                    <button type="button" data-uid="{{ $category->id }}" data-modal="{{ $type1 }}"
                      data-button="{{ $category->trashed() ? 'Restaurar' : 'Eliminar' }}"
                      data-text="¿Está seguro de que desea {{ $category->trashed() ? 'restaurar' : 'eliminar' }}: {{ $category->name }}?"
                      data-title="{{ $category->trashed() ? 'Restaurar' : 'Eliminar' }} Categoría"
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
                      {{ $category->trashed() ? 'Restaurar' : 'Eliminar' }} Categoría
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </div>
          </td>
        </tr>
      @endforeach
    </x-tables.table>

    {{ $categories->onEachSide(0)->links('pages.dashboard.partials.pagination') }}
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
            <option value="">Ninguna</option>
            @foreach ($categoriesList as $category)
              <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
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
                  'mt-3 text-purple-900 font-bold' => $category['level'] === 0,
                  'mt-2 border border-slate-200 rounded-md text-purple-900 font-semibold' =>
                      $category['level'] === 1,
                  'bg-slate-100/75 rounded-md' => $category['level'] === 2,
              ])>
                <input type="checkbox" name="children[]" class="size-4 accent-purple-600"
                  value="{{ $category['id'] }}">
                <span class="ms-1">{{ $category['name'] }}</span>
              </label>
            @endforeach
          </div>
        </div>
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
  <x-modals.delete id="{{ $type1 }}" class="max-w-md" iconClass="text-slate-500">
    <x-slot:icon>
      <svg xmlns="http://www.w3.org/2000/svg" width="112" height="112" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
      </svg>
    </x-slot:icon>
  </x-modals.delete>
@endsection
