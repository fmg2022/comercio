@extends('layouts.app')

@section('content')
  <div
    class="w-full px-3 py-5 mb-7 grid grid-cols-3 gap-3 items-center divide-x dark:divide-slate-100/20 border-b dark:border-slate-100/20">
    <div>
      <div class="hidden py-2 ms-5 lg:block">
        {{-- <h3 class="text-lg font-semibold"></h3> --}}
        <span class="text-gray-300 text-sm">{{ $products->count() }} productos</span>
      </div>
      <label for="toggle-filter"
        class="py-3 flex items-center justify-center gap-3 cursor-pointer rounded-lg dark:hover:bg-white/10 lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M13.75 2.25a.75.75 0 0 1 .75.75v4A.75.75 0 0 1 13 7V5.75H3a.75.75 0 0 1 0-1.5h10V3a.75.75 0 0 1 .75-.75M17.25 5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75m-6.5 4.25a.75.75 0 0 1 .75.75v1.25H21a.75.75 0 0 1 0 1.5h-9.5V14a.75.75 0 0 1-1.5 0v-4a.75.75 0 0 1 .75-.75M2.25 12a.75.75 0 0 1 .75-.75h4a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75m11.5 4.25a.75.75 0 0 1 .75.75v4a.75.75 0 0 1-1.5 0v-1.25H3a.75.75 0 0 1 0-1.5h10V17a.75.75 0 0 1 .75-.75m3.5 2.75a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75" />
        </svg>
        <span class="hidden sm:inline">Filtrar por</span>
      </label>
    </div>
    <div class="ps-3">
      <label class="py-3 flex items-center justify-center gap-3 cursor-pointer rounded-lg dark:hover:bg-white/10">
        <input type="checkbox" id="" disabled class="hidden">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 3v18m3-15L7 3L4 6m16 12l-3 3l-3-3m3 3V3" />
        </svg>
        <span class="hidden sm:inline">Ordernar por</span>
      </label>
    </div>
    <div class="ps-3 grid grid-cols-3 gap-2">
      <span class="hidden sm:grid sm:place-content-center md:px-2">Ver en</span>
      <!-- Falta agregar funcionalidad de aplicar estilos en el estado checked a las tarjetas de los productos -->
      <label
        class="py-3 flex items-center justify-center cursor-pointer rounded-lg dark:has-[:checked]:bg-white/10 dark:hover:bg-white/10">
        <input type="radio" name="vista" class="hidden">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M19 18c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5c-1.654 0-3 1.346-3 3s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3m0-5c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5c-1.654 0-3 1.346-3 3s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3m0-5c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5C3.346 2 2 3.346 2 5s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3" />
        </svg>
      </label>
      <label
        class="py-3 flex items-center justify-center cursor-pointer rounded-lg dark:has-[:checked]:bg-white/10 dark:hover:bg-white/10">
        <input type="radio" name="vista" class="hidden" checked>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M2 18c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C3.689 14 4.46 14 6 14s2.31 0 2.876.347c.317.194.583.46.777.777C10 15.689 10 16.46 10 18s0 2.31-.347 2.877c-.194.316-.46.582-.777.776C8.311 22 7.54 22 6 22s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.776C2 20.31 2 19.54 2 18m12 0c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C15.689 14 16.46 14 18 14s2.31 0 2.877.347c.316.194.582.46.776.777C22 15.689 22 16.46 22 18s0 2.31-.347 2.877a2.36 2.36 0 0 1-.776.776C20.31 22 19.54 22 18 22s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.776C14 20.31 14 19.54 14 18M2 6c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C3.689 2 4.46 2 6 2s2.31 0 2.876.347c.317.194.583.46.777.777C10 3.689 10 4.46 10 6s0 2.31-.347 2.876c-.194.317-.46.583-.777.777C8.311 10 7.54 10 6 10s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.777C2 8.311 2 7.54 2 6m12 0c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C15.689 2 16.46 2 18 2s2.31 0 2.877.347c.316.194.582.46.776.777C22 3.689 22 4.46 22 6s0 2.31-.347 2.876c-.194.317-.46.583-.776.777C20.31 10 19.54 10 18 10s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.777C14 8.311 14 7.54 14 6"
            color="currentColor" />
        </svg>
      </label>
    </div>
  </div>
  <section class="relative px-3 pb-6 mb-8 lg:flex lg:gap-4">
    <input type="checkbox" id="toggle-filter" class="hidden peer/filter" />
    <aside
      class="absolute top-0 left-[5%] hidden w-[90%] px-4 py-6 bg-blue-50 dark:bg-sky-900/95 rounded-lg peer-checked/filter:block lg:static lg:w-72 lg:block lg:rounded-sm">
      <div class="relative">
        <label for="toggle-filter"
          class="absolute top-0 right-0 p-2 hover:text-slate-900 dark:hover:text-blue-200 hover:bg-black/10 dark:hover:bg-white/10 rounded-lg cursor-pointer lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
            <path
              d="M437.5 386.6L306.9 256l130.6-130.6c14.1-14.1 14.1-36.8 0-50.9-14.1-14.1-36.8-14.1-50.9 0L256 205.1 125.4 74.5c-14.1-14.1-36.8-14.1-50.9 0-14.1 14.1-14.1 36.8 0 50.9L205.1 256 74.5 386.6c-14.1 14.1-14.1 36.8 0 50.9 14.1 14.1 36.8 14.1 50.9 0L256 306.9l130.6 130.6c14.1 14.1 36.8 14.1 50.9 0 14-14.1 14-36.9 0-50.9z"
              fill="currentColor" />
          </svg>
        </label>
      </div>
      <form class="mt-4 grid grid-cols-2 gap-y-2 lg:grid-cols-1 lg:m-0">
        <fieldset class="flex flex-col mx-4">
          <legend class="font-semibold py-1 px-3 mb-2 border-b border-white/20 lg:text-center">Categorias</legend>
          {{-- @php
        $prodCategory = $product->category;
        @endphp
        @while ($prodCategory->parent_id != null)
        <label class="text-white/80">
          <input type="checkbox" name="categorias" value="{{ $prodCategory->id }}">
        {{ $prodCategory->name }}
        </label>
        @php
        $prodCategory = $prodCategory->parent;
        @endphp
        @endwhile --}}
        </fieldset>
        <fieldset class="flex flex-col mx-4">
          <legend class="font-semibold py-1 px-3 mb-2 border-b border-white/20 lg:text-center">Marcas</legend>
          <label class="text-white/80">
            <input type="checkbox" name="marcas" value="1888">
            1888
          </label>
          <label class="text-white/80">
            <input type="checkbox" name="marcas" value="1930">
            1930
          </label>
          <label class="text-white/80">
            <input type="checkbox" name="marcas" value="real">
            Real
          </label>
          <label class="text-white/80">
            <input type="checkbox" name="marcas" value="delValle">
            Del Valle
          </label>
        </fieldset>
      </form>
    </aside>
    <section class="grid grid-cols-[repeat(auto-fill,minmax(220px,1fr))] justify-items-center lg:grow">
      <!-- Aquí van las tarjetas de productos -->
      <article class="w-[200px] flex flex-col gap-2 rounded-lg overflow-hidden dark:bg-white/15">
        <a href="#">
          <img src="{{ asset('images/products') }}/zz_emptyProducto.webp" alt="producto" class="aspect-square">
          <span class="py-2 block text-lg font-semibold text-center">Nombre del producto</span>
        </a>
        <div class="mx-3 py-4 border-t border-black/20 dark:border-white/20">
          <div class="pb-3">
            <h4 class="ms-3 text-lg">$1.500</h4>
          </div>
          <form class="flex flex-col items-center gap-5">
            <div class="input-container flex items-center justify-center gap-3">
              <button class="bg-red-400/50 hover:bg-red-500/80 size-6 font-bold rounded-full">-</button>
              <input type="number" name="qty" value="1" class="w-12 p-2 bg-white/5 outline-none rounded-md">
              <button class="bg-green-400/50 hover:bg-green-500/80 size-6 font-bold rounded-full">+</button>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-green-500/50 hover:bg-green-500/80">Agergar</button>
          </form>
        </div>
      </article>
      <article class="w-[200px] flex flex-col gap-2 rounded-lg overflow-hidden dark:bg-white/15">
        <a href="#">
          <img src="{{ asset('images/products') }}/zz_emptyProducto.webp" alt="producto" class="aspect-square">
          <span class="py-2 block text-lg font-semibold text-center">Nombre del producto</span>
        </a>
        <div class="mx-3 py-4 border-t border-black/20 dark:border-white/20">
          <div class="pb-3">
            <h4 class="ms-3 text-lg">$1.500</h4>
          </div>
          <form class="flex flex-col items-center gap-5">
            <div class="input-container flex items-center justify-center gap-3">
              <button class="bg-red-400/50 hover:bg-red-500/80 size-6 font-bold rounded-full">-</button>
              <input type="number" name="qty" value="1" class="w-12 p-2 bg-white/5 outline-none rounded-md">
              <button class="bg-green-400/50 hover:bg-green-500/80 size-6 font-bold rounded-full">+</button>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-green-500/50 hover:bg-green-500/80">Agergar</button>
          </form>
        </div>
      </article>
    </section>
  </section>
@endsection
