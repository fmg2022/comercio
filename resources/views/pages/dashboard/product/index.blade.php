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
        <th class="hidden sm:table-cell">SKU</th>
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
        <td class="hidden text-xs text-slate-300 sm:table-cell">{{ $product->sku }}</td>
        <td class="font-bold"><span class="me-px">$</span>{{ $product->price }}</td>
        <td class="text-slate-300">{{ $product->quantity }}</td>
        <td class="hidden text-xs text-slate-300 md:table-cell">{{ $product->category->name }}</td>
        <td class="relative flex justify-end">
          <x-popups.contentWcheck iid="chproduct-{{ $product->id }}" labelClass="dark:hover:bg-slate-900"
            class="right-14">
            <x-slot:label>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
              </svg>
            </x-slot:label>

            <ul
              class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800">
              <li>
                <button type="button"
                  class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                  data-show="true" data-id="{{ $product->id }}">
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
                  Ver Producto
                </button>
              </li>
              <li>
                <button type="button"
                  class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                  data-show="false" data-id="{{ $product->id }}">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                      <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2">
                        <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                      </g>
                    </svg>
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
                <button type="button" data-title="{{ $product->name . ' ' . $product->mark }}"
                  data-text="¿Estas seguro que quieres eliminar este producto?" data-uid="{{ $product->id }}"
                  data-modal="{{ $type1 }}" data-button="Eliminar"
                  class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M20 8.7H4a.75.75 0 1 1 0-1.5h16a.75.75 0 0 1 0 1.5" />
                      <path fill="currentColor"
                        d="M16.44 20.75H7.56A2.4 2.4 0 0 1 5 18.49V8a.75.75 0 0 1 1.5 0v10.49c0 .41.47.76 1 .76h8.88c.56 0 1-.35 1-.76V8A.75.75 0 1 1 19 8v10.49a2.4 2.4 0 0 1-2.56 2.26m.12-13a.74.74 0 0 1-.75-.75V5.51c0-.41-.48-.76-1-.76H9.22c-.55 0-1 .35-1 .76V7a.75.75 0 1 1-1.5 0V5.51a2.41 2.41 0 0 1 2.5-2.26h5.56a2.41 2.41 0 0 1 2.53 2.26V7a.75.75 0 0 1-.75.76Z" />
                      <path fill="currentColor"
                        d="M10.22 17a.76.76 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.75.75 0 0 1-.75.76m3.56 0a.75.75 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.76.76 0 0 1-.75.76" />
                    </svg>
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

  {{ $products->links('pages.dashboard.partials.pagination') }}

  @if ($productsDeleted->count() > 0)
    <section class="mt-10">
      <h2 class="mb-5 px-4 text-2xl font-semibold text-gray-300">Productos Eliminados</h2>
      <x-tables.table>
        <x-slot:thead>
          <tr class="text-left">
            <th>#</th>
            <th>Nombre</th>
            <th class="hidden sm:table-cell">SKU</th>
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
            <td class="hidden text-xs sm:table-cell">{{ $product->sku }}</td>
            <td class="font-bold"><span class="me-px">$</span>{{ $product->price }}</td>
            <td>{{ $product->quantity }}</td>
            <td class="hidden text-xs md:table-cell">{{ $product->category->name }}</td>
            <td class="relative flex justify-end">
              <x-popups.contentWcheck iid="chproduct-{{ $product->id }}" labelClass="dark:hover:bg-slate-900"
                class="right-14">
                <x-slot:label>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                  </svg>
                </x-slot:label>

                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:cursor-pointer[&>li]:transition-colors">
                  <li>
                    <button type="button"
                      class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors"
                      data-show="true" data-id="{{ $product->id }}">
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
                      Ver Producto
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
                  <li class="flex gap-3">
                    <button type="button" data-title="{{ $product->name . ' ' . $product->mark }}"
                      data-text="¿Estas seguro que quieres restaurar este producto?" data-uid="{{ $product->id }}"
                      data-modal="{{ $type1 }}" data-button="Restaurar"
                      class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 transition-colors">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                          <path fill="currentColor" fill-rule="evenodd"
                            d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                            clip-rule="evenodd" />
                        </svg>
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

      {{ $productsDeleted->onEachSide(5)->links('pages.dashboard.partials.pagination') }}
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
          <label class="block mb-2 font-semibold" for="mark"></label>
          <input type="text" id="mark" name="mark" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="price"></label>
          <input type="text" id="price" name="price"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="quantity"></label>
          <input type="number" min="1" id="quantity" name="quantity"
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
          <label for="category_id" class="block mb-2 font-semibold"></label>
          <select id="category_id" name="category_id"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" {{ $category->children->count() ? 'disabled' : '' }}
                class="font-semibold text-purple-700 bg-purple-50">
                {{ $category->name }}
              </option>
              @if ($category->children->count())
                @foreach ($category->children as $child)
                  <option value="{{ $child->id }}" {{ $child->children->count() ? 'disabled' : '' }}
                    class="text-purple-700">
                    {{ $child->name }}
                  </option>

                  @if ($child->children->count())
                    @foreach ($child->children as $grandChild)
                      <option value="{{ $grandChild->id }}" {{ $grandChild->children->count() ? 'disabled' : '' }}>
                        -- {{ $grandChild->name }}
                      </option>
                    @endforeach
                  @endif
                @endforeach
              @endif
            @endforeach
          </select>
        </div>
        <div class="col-span-2">
          <label class="block mb-2 font-semibold" for="description"></label>
          <textarea id="description" name="description"
            class="w-full min-h-[1lh] px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 field-sizing-content"></textarea>
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
  <x-modals.delete id="{{ $type1 }}" class="max-w-md" iconClass="text-slate-500">
    <x-slot:icon>
      <svg xmlns="http://www.w3.org/2000/svg" width="112" height="112" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
      </svg>
    </x-slot:icon>
  </x-modals.delete>
@endsection
