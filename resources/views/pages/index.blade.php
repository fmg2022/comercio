@extends('layouts.app')

@push('scripts-app')
  <script type="module" src="{{ asset('js/index.js') }}" defer></script>
@endpush

@section('content')
  <!-- SECCION: Carrusel de ofertas -->
  {{-- https://preview.themeforest.net/item/superkart-supermarket-bigcommerce-stencil-template/full_screen_preview/40302953 --}}
  <x-sections.carousel-img :listId="'list-oferta'" :btnsId="'btns-oferta'">
    @foreach ($offers as $offer)
      <li class="item snap-start">
        <img src="https://picsum.photos/seed/{{ $offer->id }}offer/768/360.webp" alt="{{ $offer->name }}"
          class="h-full w-full object-cover" draggable="false" />
      </li>
    @endforeach
  </x-sections.carousel-img>

  <!-- SECCION: Listado de categorias -->
  <x-sections.list-items :title="'Categorías Destacadas'" :items="$selectedCategories">
    <div class="flex items-center justify-center">
      <a class="px-4 py-2 rounded-lg bg-purple-700" href="!#">Ver más productos</a>
    </div>
  </x-sections.list-items>

  <!-- SECCION: Slider de productos recomendados -->
  <x-sections.carousel-img :listId="'list-product'" :btnsId="'btns-product'" :class="'px-3'" title="Productos recomendados">
    @foreach ($products as $product)
      <li class="item flex justify-center items-center snap-start">
        <x-cards.product :product="$product" />
      </li>
    @endforeach
  </x-sections.carousel-img>

  <!-- SECCION: Listado de marcas -->
  <x-sections.list-items :title="'Marcas Destacadas'" :items="$brands" />
@endsection
