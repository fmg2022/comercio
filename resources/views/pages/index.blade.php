@extends('layouts.app')

@section('content')
  <!-- SECCION: Carrusel de ofertas -->
  <x-sections.carousel-img :listId="'list-oferta'" :btnsId="'btns-oferta'" :fullwidth="true">
    @foreach ($offers as $offer)
      <li class="item snap-start">
        <img src="https://picsum.photos/seed/{{ $offer->code }}/768/360.webp" alt="Offer"
          class="h-full w-full object-cover" draggable="false" />
      </li>
    @endforeach
  </x-sections.carousel-img>

  <!-- SECCION: Listado de categorias -->
  <x-sections.list-items :title="'Categorías Destacadas'" :items="$selectedCategories">
    <div class="flex items-center justify-center">
      <a class="px-4 py-2 rounded-lg bg-purple-700" href="{{ route('product.listAll') }}">Ver más productos</a>
    </div>
  </x-sections.list-items>

  <!-- SECCION: Slider de productos recomendados -->
  <x-sections.carousel-img :listId="'list-product'" :btnsId="'btns-product'" :class="'px-3'" title="Productos recomendados">
    @foreach ($products as $product)
      <li class="item flex justify-center items-center snap-start">
        <x-card :product="$product" />
      </li>
    @endforeach
  </x-sections.carousel-img>

  <!-- SECCION: Listado de marcas -->
  <x-sections.list-items :title="'Marcas Destacadas'" :items="$marks" />
@endsection
