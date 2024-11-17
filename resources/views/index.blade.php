<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Comercio')}}</title>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Extras -->
    <script type="module" src="{{ asset('js/index.js') }}" defer></script>
</head>

<body class="antialiased dark:bg-slate-900 dark:text-white/50">
    <!-- Header Section -->
    @include('layouts.header', ['categories' => $categories])

    <main class="container mx-auto">
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
    </main>

    <!-- Footer section -->
    @include('layouts.footer')
</body>

</html>
{{-- https://unpkg.com/flowbite@1.4.0/dist/flowbite.js --}}
{{-- carousel / slider --}}