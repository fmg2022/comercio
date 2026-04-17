@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/productModalMix.js') }}" defer></script>
@endpush

<!-- Mostrar un mensaje para:
    - Los errores en las operaciones desde está página
    - El mensaje de éxito al crear un producto -->

@php
  $type1 = 'modal-delete-restore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Productos</x-slot:textTitle>

    <x-buttons.linkFill href="{{ route('products.create') }}"
      class="flex items-center gap-2 bg-purple-600 active:bg-purple-700">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M18 10h-4V6a2 2 0 0 0-4 0l.071 4H6a2 2 0 0 0 0 4l4.071-.071L10 18a2 2 0 0 0 4 0v-4.071L18 14a2 2 0 0 0 0-4" />
      </svg>
      Nuevo
    </x-buttons.linkFill>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Nombre</th>
        <th class="hidden sm:table-cell">Marca</th>
        <th class="hidden md:table-cell">SKU</th>
        <th>Precio</th>
        <th>Stock</th>
        <th class="hidden md:table-cell">Categoría</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($products as $index => $product)
      <tr>
        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
        <td>
          <a class="flex items-center gap-3" href="{{ route('products.show', $product->id) }}">
            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
              class="size-12 aspect-square">
            <span class="hidden text-base font-semibold sm:inline">{{ $product->name }}</span>
          </a>
        </td>
        <td class="hidden text-xs text-slate-300 sm:table-cell">{{ $product->brand->name }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $product->sku }}</td>
        <td class="font-bold"><span class="me-px">$</span>{{ $product->price }}</td>
        <td class="text-slate-300">{{ $product->stock }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $product->category->name }}</td>
        <td class="relative flex justify-end">
          <x-popups.contentWcheck iid="chproduct-{{ $product->id }}" labelClass="dark:hover:bg-slate-900"
            class="right-14">
            <x-slot:label>
              <x-icons.threeDotsX class="size-6" />
            </x-slot:label>

            <ul
              class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800">
              <li>
                <button type="button"
                  class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                  data-show="true" data-id="{{ $product->id }}">
                  <span>
                    <x-icons.show class="size-5" />
                  </span>
                  Ver Producto
                </button>
              </li>
              <li>
                <button type="button"
                  class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                  data-show="false" data-id="{{ $product->id }}">
                  <span>
                    <x-icons.edit class="size-5" />
                  </span>
                  Editar Producto
                </button>
              </li>
              <li>
                <a href="{{ route('products.orders', $product->id) }}"
                  class="flex gap-3 px-4 py-2.5 hover:bg-slate-700 transition-colors">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                      <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5" color="currentColor">
                        <path
                          d="M4.318 19.682C3 18.364 3 16.242 3 12s0-6.364 1.318-7.682S7.758 3 12 3s6.364 0 7.682 1.318S21 7.758 21 12s0 6.364-1.318 7.682S16.242 21 12 21s-6.364 0-7.682-1.318" />
                        <path d="M6 12h2.5l2-4l3 8l2-4H18" />
                      </g>
                    </svg>
                  </span>
                  Productos en Ordenes
                </a>
              </li>
              <li>
                <button type="button" data-text="Producto: '{{ $product->name }}'" data-uid="{{ $product->id }}"
                  data-modalID="{{ $type1 }}" data-path="{{ $product->id }}" data-delete="true"
                  class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                  <span>
                    <x-icons.trash class="size-5" />
                  </span>
                  Eliminar Producto
                </button>
              </li>
            </ul>
          </x-popups.contentWcheck>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="6" class="text-center font-semibold text-slate-300">Sin productos registrados</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $products->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  @if ($productsDeleted->count() > 0)
    <section class="mt-10">
      <h2 class="mb-5 px-4 text-2xl font-semibold text-gray-300">Productos Eliminados</h2>
      <x-tables.table>
        <x-slot:thead>
          <tr class="text-left">
            <th>#</th>
            <th>Nombre</th>
            <th class="hidden sm:table-cell">Marca</th>
            <th class="hidden md:table-cell">SKU</th>
            <th>Precio</th>
            <th>Stock</th>
            <th class="hidden md:table-cell">Categoría</th>
            <th class="text-end">Opciones</th>
          </tr>
        </x-slot>
        @foreach ($productsDeleted as $index => $product)
          <tr class="text-slate-400">
            <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
            <td>
              <a class="flex items-center gap-3" href="{{ route('products.show', $product->id) }}">
                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                  class="size-12 aspect-square">
                <span class="hidden text-base font-semibold sm:inline">{{ $product->name }}</span>
              </a>
            </td>
            <td class="hidden text-xs sm:table-cell">{{ $product->brand->name }}</td>
            <td class="hidden text-xs md:table-cell">{{ $product->sku }}</td>
            <td class="font-bold"><span class="me-px">$</span>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td class="hidden text-xs md:table-cell">{{ $product->category->name }}</td>
            <td class="relative flex justify-end">
              <x-popups.contentWcheck iid="chproduct-{{ $product->id }}" labelClass="dark:hover:bg-slate-900"
                class="right-14">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:cursor-pointer[&>li]:transition-colors">
                  <li>
                    <button type="button"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                      data-show="true" data-id="{{ $product->id }}">
                      <span>
                        <x-icons.show class="size-5" />
                      </span>
                      Ver Producto
                    </button>
                  </li>
                  <li>
                    <a href="{{ route('products.orders', $product->id) }}"
                      class="px-4 py-2.5 flex items-center gap-3 hover:bg-slate-700 transition-colors">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5" color="currentColor">
                            <path
                              d="M4.318 19.682C3 18.364 3 16.242 3 12s0-6.364 1.318-7.682S7.758 3 12 3s6.364 0 7.682 1.318S21 7.758 21 12s0 6.364-1.318 7.682S16.242 21 12 21s-6.364 0-7.682-1.318" />
                            <path d="M6 12h2.5l2-4l3 8l2-4H18" />
                          </g>
                        </svg>
                      </span>
                      Productos en Ordenes
                    </a>
                  </li>
                  <li class="flex gap-3">
                    <button type="button" data-text="Producto: '{{ $product->name }}'"
                      data-uid="{{ $product->id }}" data-modalID="{{ $type1 }}"
                      data-path="{{ $product->id . '/restore' }}" data-delete="false"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                      <span>
                        <x-icons.restore class="size-5" />
                      </span>
                      Restaurar Producto
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </td>
          </tr>
        @endforeach
      </x-tables.table>

      {{ $productsDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}
    </section>
  @else
    <h3 class="mb-3 mt-7 text-center text-xl font-semibold">Sin productos eliminados</h3>
  @endif

  {{-- MODAL SHOW, EDIT --}}
  <x-modals.simple id="modal-product-mix"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form id="form-product-mix" enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')
      <x-images.borderFill src="{{ asset('images/products') }}/zz_emptyProducto.webp"
        alt="Producto {{ $product->name }} sin imagen" />
      <fieldset class="py-3 grid grid-cols-[repeat(auto-fill,minmax(250px,1fr))] gap-2 text-gray-700 md:px-3">
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="name"></label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label for="brand" class="block mb-2 font-semibold"></label>
          <select id="brand" name="brand_id" class="px-3 py-2 mb-5 text-black bg-white/75 rounded-md outline-none">
            <option value="" class="bg-slate-200 disabled:text-black" disabled selected>Selecciona una marca
            </option>
            @foreach ($brands as $brand)
              <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="price"></label>
          <input type="text" id="price" name="price"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="stock"></label>
          <input type="number" min="1" id="stock" name="stock"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="sku"></label>
          <input type="text" id="sku" name="sku"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <select name="category_id" class="px-3 py-2 mb-5 text-black bg-white/75 rounded-md outline-none">
            <option value="" class="bg-slate-200 disabled:text-black" disabled selected>Selecciona una categoría
            </option>
            @foreach ($categories as $category)
              <option value="{{ $category['id'] }}" {{ $category['nivel'] != 2 ? 'disabled' : '' }}
                @class([
                    'text-slate-800',
                    'bg-purple-100 font-bold' => $category['nivel'] === 0,
                    'bg-purple-50 font-semibold' => $category['nivel'] === 1,
                    'bg-slate-50' => $category['nivel'] === 2,
                ])>
                {{ ($category['nivel'] === 3 ? '--' : '') . $category['name'] }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-span-2">
          <label class="block mb-2 font-semibold" for="description"></label>
          <textarea id="description" name="description"
            class="w-full min-h-lh px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 field-sizing-content"></textarea>
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

  {{-- MODAL DELETE, RESTORE --}}
  <x-modals.delete id="{{ $type1 }}" />
@endsection
