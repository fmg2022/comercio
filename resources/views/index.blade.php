@extends('layouts.main')

@section('scripts')
<!-- Extras -->
<script type="module" src="{{ asset('js/index.js') }}" defer></script>
<script>
    // It's best to inline this in `head` to avoid FOUC (flash of unstyled content) when changing pages or themes
    if (
    localStorage.getItem('color-theme') === 'dark' ||
    (!('color-theme' in localStorage) &&
        window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
    document.documentElement.classList.add('dark')
    } else {
    document.documentElement.classList.remove('dark')
    }
</script>
@endsection

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
<x-sections.list-items :title="'Categorías Destacadas'" :items="$selectedCategories" />

<!-- SECCION: Slider de productos recomendados -->
<x-sections.carousel-img :listId="'list-product'" :btnsId="'btns-product'" :class="'px-3'"
    title="Productos recomendados">
    @foreach ($products as $product)
    <li class="item flex justify-center items-center snap-start">
        <x-card :product="$product" />
    </li>
    @endforeach
</x-sections.carousel-img>

<!-- SECCION: Listado de marcas -->
<x-sections.list-items :title="'Marcas Destacadas'" :items="$marks" />
@endsection