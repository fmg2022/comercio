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
        <article class="max-w-sm h-max rounded-xl shadow-lg bg-slate-300 pb-3 overflow-hidden">
          <a href="{{ route('product.show', $product->id) }}" class="block">
            <img class="aspect-square" src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
              draggable="false" width="310">
          </a>
          <div class="px-6 py-4">
            <h2 class="font-bold text-2xl mb-1 text-slate-700">{{ $product->mark }}</h2>
            <p class="text-slate-500 text-base">{{ $product->name }}</p>
          </div>
          <form action="{{ route('cart.addToCart') }}" method="POST"
            class="px-6 pt-4 pb-2 flex flex-col justify-between gap-5">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
            <section class="flex justify-between items-center">
              <p class="py-1 text-slate-600 text-xl">${{ $product->price }}</p>
              <div class="flex flex-col items-center justify-center">
                <label class="w-full max-w-16 grid grid-cols-1">
                  <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}"
                    class="px-3 py-1.5 text-base text-gray-900 bg-white rounded-md outline outline-offset-1 outline-gray-400 sm:text-sm">
                </label>
              </div>
            </section>
            <button type="submit"
              class="bg-slate-700 text-white px-4 py-2 rounded-md hover:bg-slate-600 cursor-pointer">Agregar</button>
          </form>
        </article>
      </li>
    @endforeach
  </x-sections.carousel-img>

  <!-- SECCION: Listado de marcas -->
  <x-sections.list-items :title="'Marcas Destacadas'" :items="$marks" />
@endsection
