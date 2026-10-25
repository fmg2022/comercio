@extends('layouts.app')

@section('content')
  <x-sections.carousel :length="$offers?->count() ?? 0" :is_image="true">
    @foreach ($offers as $offer)
      <li class="relative">
        <x-buttons.link href="{{ route('product.index', ['offer_id' => $offer->id]) }}"
          class="group/offer block w-full h-full">
          <img src="{{ asset('images/hero/' . ($loop->index + 1) . '.webp') }}" alt="{{ $offer->offerTemplate->name }}"
            class="w-full max-h-105" draggable="false" />
          <span
            class="absolute bottom-0 left-1/2 -translate-x-1/2 px-3 py-2.5 bg-slate-800 text-white rounded-lg opacity-0 group-hover/offer:opacity-90 transition-opacity ease-in-out">
            Ver {{ $offer->name ?? $offer->offerTemplate->name }}
          </span>
        </x-buttons.link>
      </li>
    @endforeach
  </x-sections.carousel>

  <!-- SECCION: Listado de categorias -->
  <x-sections.list-items :title="'Categorías Destacadas'" :items="$selectedCategories" />

  <!-- SECCION: Slider de productos recomendados -->
  <div>
    <h2 class="py-3 mb-4 text-2xl font-bold text-center">Productos recomendados</h2>
    <x-sections.carousel :length="count($products)" class="group grilla">
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
