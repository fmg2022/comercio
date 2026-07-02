@extends('layouts.app')

@section('content')
  <x-sections.carousel :length="count($offers)" :is_image="true">
    @foreach ($offers as $offerItem)
      <li>
        <img src="{{ asset('images/hero/' . ($loop->index + 1) . '.webp') }}"
          alt="{{ $offerItem['offer_template']['name'] }}" class="w-full max-h-[420px]" draggable="false" />
      </li>
    @endforeach
  </x-sections.carousel>

  <!-- SECCION: Listado de categorias -->
  <x-sections.list-items :title="'Categorías Destacadas'" :items="$selectedCategories" />

  <!-- SECCION: Slider de productos recomendados -->
  <div>
    <h2 class="py-3 mb-4 text-2xl font-bold text-center">Productos recomendados</h2>
    <x-sections.carousel :length="count($products)">
      @foreach ($products as $product)
        <li class="flex-none">
          <x-cards.product :product="$product" :offers="$offers" />
        </li>
      @endforeach
    </x-sections.carousel>
  </div>

  <!-- SECCION: Listado de marcas -->
  <x-sections.list-items :title="'Marcas Destacadas'" :items="$brands" />
@endsection
