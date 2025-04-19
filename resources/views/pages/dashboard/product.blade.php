@extends('layouts.dashboard')

@section('titleH1', 'Productos')

@section('header-actions')
<x-buttons.ancorFill href="{{ route('products.create') }}" class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
    <path fill="currentColor"
      d="M18 10h-4V6a2 2 0 0 0-4 0l.071 4H6a2 2 0 0 0 0 4l4.071-.071L10 18a2 2 0 0 0 4 0v-4.071L18 14a2 2 0 0 0 0-4" />
  </svg>
  Nuevo
</x-buttons.ancorFill>
@endsection

@section('content')
<table
  class="table-auto w-full divide-y divide-slate-500 border-separate border-spacing-y-1 [&_th]:py-4 [&_th]:px-3 [&_td]:py-4 [&_td]:px-3 [&_tr]:bg-slate-800">
  <thead class="text-sm dark:text-slate-400">
    <!-- Obtener datos de DB -->
    <tr class="text-left">
      <th>Nombre</th>
      <!-- <th class="hidden sm:table-cell">SKU</th> -->
      <th>Precio</th>
      <th>Stock</th>
      <th class="hidden md:table-cell">Categoría</th>
      <th class="text-end">Opciones</th>
    </tr>
  </thead>
  <tbody class="text-sm">
    @foreach ($products as $product)
    <tr>
      <td class="flex items-center gap-3">
        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
          class="size-12 aspect-square">
        <span class="hidden font-semibold sm:inline">{{ $product->name }}</span>
      </td>
      <td class="font-bold"><span class=" me-px">$</span>{{ $product->price }}</td>
      <td class="text-xs text-slate-300">{{ $product->quantity }}</td>
      <!-- <td class="hidden text-xs text-slate-300 sm:table-cell">45</td> -->
      <td class="hidden text-xs text-slate-300 md:table-cell">{{ $product->category->name }}</td>
      <td class="relative flex justify-end">
        <input type="checkbox" id="chproduct-{{ $product->id }}" class="hidden peer/checkOption">
        <label for="chproduct-{{ $product->id }}"
          class="inline-block p-1.5 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
          </svg>
        </label>
        <div class="absolute right-1/4 z-30 hidden peer-checked/checkOption:block">
          <ul
            class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:px-4 [&>li]:py-2.5 [&>li]:bg-slate-800 [&>li]:cursor-pointer [&>li:hover]:bg-slate-700 [&>li]:transition-colors">
            <li class="flex gap-3">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                    <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                  </g>
                </svg>
              </span>
              <span>Editar Producto</span>
            </li>
            <li class="flex gap-3">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path
                      d="M3.587 13.779c1.78 1.769 4.883 4.22 8.413 4.22s6.634-2.451 8.413-4.22c.47-.467.705-.7.854-1.159c.107-.327.107-.913 0-1.24c-.15-.458-.385-.692-.854-1.159C18.633 8.452 15.531 6 12 6c-3.53 0-6.634 2.452-8.413 4.221c-.47.467-.705.7-.854 1.159c-.107.327-.107.913 0 1.24c.15.458.384.692.854 1.159" />
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                  </g>
                </svg>
              </span>
              <span>Ver Producto</span>
            </li>
            <li class="flex gap-3">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    color="currentColor">
                    <path
                      d="M4.318 19.682C3 18.364 3 16.242 3 12s0-6.364 1.318-7.682S7.758 3 12 3s6.364 0 7.682 1.318S21 7.758 21 12s0 6.364-1.318 7.682S16.242 21 12 21s-6.364 0-7.682-1.318" />
                    <path d="M6 12h2.5l2-4l3 8l2-4H18" />
                  </g>
                </svg>
              </span>
              <span>Productos en Ordenes</span>
            </li>
            <li class="flex gap-3">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M20 8.7H4a.75.75 0 1 1 0-1.5h16a.75.75 0 0 1 0 1.5" />
                  <path fill="currentColor"
                    d="M16.44 20.75H7.56A2.4 2.4 0 0 1 5 18.49V8a.75.75 0 0 1 1.5 0v10.49c0 .41.47.76 1 .76h8.88c.56 0 1-.35 1-.76V8A.75.75 0 1 1 19 8v10.49a2.4 2.4 0 0 1-2.56 2.26m.12-13a.74.74 0 0 1-.75-.75V5.51c0-.41-.48-.76-1-.76H9.22c-.55 0-1 .35-1 .76V7a.75.75 0 1 1-1.5 0V5.51a2.41 2.41 0 0 1 2.5-2.26h5.56a2.41 2.41 0 0 1 2.53 2.26V7a.75.75 0 0 1-.75.76Z" />
                  <path fill="currentColor"
                    d="M10.22 17a.76.76 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.75.75 0 0 1-.75.76m3.56 0a.75.75 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.76.76 0 0 1-.75.76" />
                </svg>
              </span>
              <span>Eliminar Producto</span>
            </li>
          </ul>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{ $products->onEachSide(5)->links('pages.dashboard.partials.pagination') }}

@endsection