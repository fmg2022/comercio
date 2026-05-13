@extends('layouts.app')

@push('scripts-app')
  <script type="module">
    const $nav = document.getElementById('nav-info')
    const inputs = $nav.querySelectorAll('input')
    const infoCont = $nav.nextElementSibling

    infoCont.style.width = inputs.length * 100 + '%'

    inputs.forEach((input, i) => {
      input.addEventListener('change', () => {
        infoCont.style.left = `-${i * 100}%`
      })
    })
  </script>
@endpush

@section('content')
  <x-breadcrumbs.categories :categoriesNav="$categoriesNav" />

  <section class="px-3 py-4 border border-slate-300 rounded-md bg-slate-200">
    <article class=" py-3 flex flex-col md:divide-x-2 md:divide-black/10 md:flex-row">
      <section class="pe-5 flex items-center justify-center gap-3 md:w-3/7">
        <!-- Pre-visualización de las imágenes -->
        <div class="h-full w-1/5 hidden flex-col justify-center items-center gap-2 md:flex">
          <img class="w-full max-w-32" src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
        </div>
        <div class="md:w-4/5">
          <img class="max-w-md w-full" src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
        </div>
      </section>
      <section class="w-full px-4 py-8 md:w-4/7 md:py-4">
        <div>
          <h3 class="text-2xl font-semibold">{{ $product->name }} x {{ $product->weight }}</h3>
          <p class="text-sm">
            <span class="me-3 font-bold uppercase">{{ $product->category->name }}</span> |
            <span class="ms-3 font-semibold">SKU: {{ $product->sku }}</span>
          </p>
        </div>
        <h4 class="my-8 text-xl font-bold sm:text-2xl">${{ number_format($product->price, 2, ',', '.') }}</h4>
        <ul class="ms-6 mb-12 list-disc text-sm">
          <li>Tipo de producto: {{ $product->name }}</li>
          <li>Contenido: {{ $product->weight }}</li>
          <li>Envase: {{ $product->container }}</li>
        </ul>
        <div class="flex justify-center">
          <button
            class="w-full max-w-sm p-4 bg-emerald-800 rounded-lg font-bold uppercase text-white cursor-pointer hover:bg-emerald-700 active:bg-emerald-800">Agregar</button>
        </div>
      </section>
    </article>
  </section>
  <section class="container px-3 py-6 my-4 mx-auto overflow-x-hidden lg">
    <nav id="nav-info" class="py-4 mb-5 flex justify-center gap-12 border-b border-slate-800 dark:border-slate-600">
      <label class="cursor-pointer hover:text-purple-500">
        <input type="radio" name="tabs" checked class="hidden">
        Descripción
      </label>
      <label class="cursor-pointer hover:text-purple-500">
        <input type="radio" name="tabs" class="hidden">
        Información
      </label>
    </nav>
    <div class="relative grid grid-cols-2 transition-all">
      <article class="px-3 flex flex-col gap-5">
        <h2 class="text-xl">Descripción</h2>
        <p>{{ $product->description }}</p>
      </article>
      <article class="px-3 flex flex-col gap-5">
        <h2 class="text-xl">Información</h2>
        <ul class="ms-4 [&>li]:before:content-['\2022'] [&>li]:before:me-[.5rem]">
          <li>Tipo de producto: {{ $product->name }}</li>
          <li>Contenido: {{ $product->weight }}</li>
          <li>Envase: {{ $product->container }}</li>
        </ul>
      </article>
    </div>
  </section>

  <!-- SECCION: Slider de productos recomendados -->
  <div>
    <h2 class="py-3 mb-4 text-2xl font-bold text-center">Productos recomendados</h2>
    <x-sections.carousel :length="count($products)">
      @foreach ($products as $product)
        <li class="item flex justify-center items-center snap-start">
          <x-card :product="$product" />
        </li>
      @endforeach
    </x-sections.carousel>
  </div>
@endsection
