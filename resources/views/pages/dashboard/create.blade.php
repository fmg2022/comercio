@extends('layouts.dashboard')

@section('titleH1', 'Nuevo Producto')

{{-- Agregar mensaje de error cuando sea necesario --}}

@section('content')
  <div class="@container px-2 md:px-4">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
      class="px-3 py-4 mx-auto my-4 max-w-xl flex flex-col gap-5 border border-slate-100/20 rounded-md @xl:[&>label]:flex-row @xl:px-6">
      @csrf
      <x-inputs.withLabel title="Nombre" name="name" placeholder="Nombre del producto" required />
      <x-inputs.withLabel title="Marca" name="mark" placeholder="Marca del producto" required />
      <x-inputs.withLabel title="Precio" name="price" placeholder="$1550.65" required />
      <x-inputs.withLabel title="Stock" name="quantity" type="number" value="0" min="0" max="9999" />
      <x-inputs.withLabel title="Imagen" name="image" placeholder=".jpg, .webp, .png, ..." />

      <select name="category_id" class="px-3 py-2 mb-5 text-black bg-white/75 rounded-md outline-none">
        <option value="" disabled selected>Selecciona una categoría</option>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}" {{ $category->children ? 'disabled' : '' }}
            class="font-semibold
        text-purple-700">
            {{ $category->name }}
          </option>
          @if ($category->children->count())
            @foreach ($category->children as $child)
              <option value="{{ $child->id }}" {{ $child->children ? 'disabled' : '' }}>
                -- {{ $child->name }}
              </option>

              @if ($child->children->count())
                @foreach ($child->children as $grandChild)
                  <option value="{{ $grandChild->id }}">
                    ---- {{ $grandChild->name }}
                  </option>
                @endforeach
              @endif
            @endforeach
          @endif
        @endforeach
      </select>

      <div class="flex items-center gap-3 justify-end">
        <button type="submit"
          class="px-3 py-2 bg-emerald-700 rounded-md hover:bg-emerald-600 cursor-pointer">Agregar</button>
        <button type="reset" class="px-3 py-2 bg-red-900 rounded-md hover:bg-red-800 cursor-pointer">Limpiar</button>
      </div>
    </form>
    <x-buttons.ancorShadow href="{{ route('products.index') }}">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M13.83 19a1 1 0 0 1-.78-.37l-4.83-6a1 1 0 0 1 0-1.27l5-6a1 1 0 0 1 1.54 1.28L10.29 12l4.32 5.36a1 1 0 0 1-.78 1.64" />
      </svg>
      Volver
    </x-buttons.ancorShadow>
  </div>
@endsection

{{--
<!-- component -->
<div class="min-h-screen flex items-center justify-center">
  <div class="relative group">
    <button id="dropdown-button"
      class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
      <span class="mr-2">Open Dropdown</span>
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor"
        aria-hidden="true">
        <path fill-rule="evenodd"
          d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
          clip-rule="evenodd" />
      </svg>
    </button>
    <div id="dropdown-menu"
      class="hidden absolute right-0 mt-2 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-1 space-y-1">
      <!-- Search input -->
      <input id="search-input"
        class="block w-full px-4 py-2 text-gray-800 border rounded-md  border-gray-300 focus:outline-none" type="text"
        placeholder="Search items" autocomplete="off">
      <!-- Dropdown content goes here -->
      <a href="#"
        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">Uppercase</a>
      <a href="#"
        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">Lowercase</a>
      <a href="#"
        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">Camel
        Case</a>
      <a href="#"
        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">Kebab
        Case</a>
    </div>
  </div>
</div>
<script>
  // JavaScript to toggle the dropdown
        const dropdownButton = document.getElementById('dropdown-button');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const searchInput = document.getElementById('search-input');
        let isOpen = false; // Set to true to open the dropdown by default
        
        // Function to toggle the dropdown state
        function toggleDropdown() {
          isOpen = !isOpen;
          dropdownMenu.classList.toggle('hidden', !isOpen);
        }
        
        // Set initial state
        toggleDropdown();
        
        dropdownButton.addEventListener('click', () => {
          toggleDropdown();
        });
        
        // Add event listener to filter items based on input
        searchInput.addEventListener('input', () => {
          const searchTerm = searchInput.value.toLowerCase();
          const items = dropdownMenu.querySelectorAll('a');
        
          items.forEach((item) => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
              item.style.display = 'block';
            } else {
              item.style.display = 'none';
            }
          });
        });
</script> --}}
