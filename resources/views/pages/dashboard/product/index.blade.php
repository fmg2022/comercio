@extends('layouts.dashboard')

@push('scripts-dashboard')
  @can('manage products-and-attributes')
    <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
    <script>
      const choicesInstances = {};

      document.addEventListener('DOMContentLoaded', function() {
        const selectsConfig = [{
            id: 'brand_id',
            placeholder: 'Selecciona una marca',
            searchPlaceholderValue: 'Buscar marca...'
          },
          {
            id: 'category_id',
            placeholder: 'Selecciona una categoría',
            searchPlaceholderValue: 'Buscar categoría...'
          },
        ]
        selectsConfig.forEach(config => {
          const choicesOptions = {
            placeholderValue: config.placeholder,
            searchPlaceholderValue: config.searchPlaceholderValue,
            searchEnabled: true,
            removeItemButton: true,
            placeholder: true,
            shouldSort: false,
          };

          choicesInstances[config.id] = new Choices(`#${config.id}`, choicesOptions);
        });
      })
    </script>
  @endcan
  <script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
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

<!-- Mostrar un mensaje para:
    - Los errores en las operaciones desde está página
    - El mensaje de éxito al crear un producto -->

@php
  $type1 = 'modal-delete-restore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Productos</x-slot:textTitle>

    @can('manage products-and-attributes')
      <button type="button" data-type="create" data-modalID="productCSE"
        class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
        <x-icons.plus class="size-6" />
        Nuevo Producto
      </button>
    @endcan
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
        <th class="hidden xl:table-cell">Min. Stock</th>
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
        <td class="hidden text-slate-300 xl:table-cell">{{ $product->min_stock }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $product->category->name }}</td>
        <td class="relative flex justify-end">
          <x-popups.contentWcheck iid="chproduct-{{ $product->id }}" labelClass="dark:hover:bg-slate-900"
            class="right-14">
            <x-slot:label>
              <x-icons.threeDotsX class="size-6" />
            </x-slot:label>

            <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold ">
              <li>
                <button type="button" data-type="show" data-uid="{{ $product->id }}" data-path="{{ $product->id }}"
                  data-modalID="productCSE"
                  class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                  <span>
                    <x-icons.show class="size-5" />
                  </span>
                  Ver Producto
                </button>
              </li>
              @can('manage products-and-attributes')
                <li>
                  <button type="button" data-type="edit" data-uid="{{ $product->id }}" data-path="{{ $product->id }}"
                    data-modalID="productCSE"
                    class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                    <span>
                      <x-icons.edit class="size-5" />
                    </span>
                    Editar Producto
                  </button>
                </li>
                <li>
                  <a href="{{ route('products.orders', $product->id) }}"
                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-700 transition-colors">
                    <span>
                      <x-icons.statistics class="size-5" />
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
              @endcan
            </ul>
          </x-popups.contentWcheck>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="8" class="text-center font-semibold text-slate-300">Sin productos registrados</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $products->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  @can('manage products-and-attributes')
    <h2 class="mb-5 mt-10 px-4 text-2xl font-semibold text-gray-300">Productos Eliminados</h2>
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
      @forelse ($productsDeleted as $index => $product)
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
                  <button type="button" data-type="show" data-uid="{{ $product->id }}" data-path="{{ $product->id }}"
                    data-modalID="productCSE"
                    class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
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
                      <x-icons.statistics class="size-5" />
                    </span>
                    Productos en Ordenes
                  </a>
                </li>
                <li class="flex gap-3">
                  <button type="button" data-text="Producto: '{{ $product->name }}'" data-uid="{{ $product->id }}"
                    data-modalID="{{ $type1 }}" data-path="{{ $product->id . '/restore' }}" data-delete="false"
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

      @empty
        <tr>
          <td colspan="8" class="text-center font-semibold text-slate-300">Sin productos eliminados</td>
        </tr>
      @endforelse
    </x-tables.table>

    {{ $productsDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}
  @endcan

  {{-- MODAL SHOW, EDIT --}}
  <x-modals.simple id="productCSE"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
    <form enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @can('manage products-and-attributes')
        @csrf
        @method('PUT')
      @endcan
      <x-images.borderFill src="{{ asset('images/products') }}/zz_emptyProducto.webp" alt="Producto" />
      <fieldset class="py-3 grid grid-cols-2 gap-6 text-gray-700 md:px-3">
        <input type="hidden" name="image" value="{{ $product->image }}" />
        <div class="col-span-2 pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="name">Nombre</label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="col-span-2 pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="sku">SKU</label>
          <input type="text" id="sku" name="sku"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block ps-4 mb-2 font-semibold" for="price">Precio</label>
          <p class="flex flex-row gap-1 items-baseline justify-center">
            $ <input type="number" id="price" name="price" step="0.01" min="0"
              class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </p>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label for="brand_id" class="block mb-2 font-semibold">Marcas</label>
          <select id="brand_id" name="brand_id"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            @foreach ($brands as $brand)
              <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="stock">Stock Disponible</label>
          <input type="number" min="1" id="stock" name="stock" value="1"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="min_stock">Stock Mínimo</label>
          <input type="number" min="1" id="min_stock" name="min_stock" value="1"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="weight">Peso</label>
          <input id="weight" name="weight" autocomplete="off" placeholder="1 lt, 1kg, 100gr, 350ml, ..."
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="container">Envase</label>
          <input id="container" name="container" autocomplete="off" placeholder="Caja, Paquete, Sachet, ..."
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="col-span-2 pointer-events-none group-[.editable]:pointer-events-auto">
          <label for="category_id" class="block mb-2 font-semibold">Categorías</label>
          <select name="category_id" id="category_id"
            class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
            @foreach ($categories as $category)
              <option value="{{ $category['id'] }}" @class([
                  'text-slate-800',
                  'bg-purple-100 font-bold' => $category['nivel'] === 0,
                  'bg-purple-50 font-semibold' => $category['nivel'] === 1,
                  'bg-slate-50' => $category['nivel'] === 2,
              ])>
                {{ $category['name'] }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-span-2 pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="description">Descripción</label>
          <textarea id="description" name="description" rows="4"
            class="w-full max-w-xl min-h-lh px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 field-sizing-content"></textarea>
        </div>
        <button type="submit"
          class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-green-800 text-lg text-white rounded-md hover:bg-green-700 cursor-pointer sm:right-3/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
      <button
        class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- MODAL DELETE, RESTORE --}}
  @can('manage products-and-attributes')
    <x-modals.delete id="{{ $type1 }}" />
  @endcan
@endsection
