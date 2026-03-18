@extends('layouts.app')

@section('content')
  <x-breadcrumbs.categories :categoriesNav="$categoriesNav" />
  <div
    class="w-full px-3 py-2 mb-10 grid grid-cols-3 gap-3 items-center divide-x-2 divide-slate-300 border-b border-slate-300 bg-slate-200/65 rounded-xl">
    <div>
      <div class="hidden py-2 ms-5 text-base lg:flex lg:justify-start lg:items-baseline lg:gap-4">
        <h3 class="font-semibold">{{ end($categoriesNav) }}</h3>
        <span class="text-gray-500">{{ $products->count() }} productos</span>
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
      <label class="py-3 flex items-center justify-center gap-3 cursor-pointer rounded-lg hover:bg-slate-800/10">
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
        class="py-3 flex items-center justify-center cursor-pointer rounded-lg has-checked:bg-slate-800/10 has-checked:pointer-events-none hover:bg-slate-800/10">
        <input type="radio" name="vista" class="hidden">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M19 18c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5c-1.654 0-3 1.346-3 3s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3m0-5c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5c-1.654 0-3 1.346-3 3s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3m0-5c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1s.45-1 1-1zm0-2H5C3.346 2 2 3.346 2 5s1.346 3 3 3h14c1.654 0 3-1.346 3-3s-1.346-3-3-3" />
        </svg>
      </label>
      <label
        class="py-3 flex items-center justify-center cursor-pointer rounded-lg has-checked:bg-slate-800/10 has-checked:pointer-events-none hover:bg-slate-800/10">
        <input type="radio" name="vista" class="hidden" checked>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M2 18c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C3.689 14 4.46 14 6 14s2.31 0 2.876.347c.317.194.583.46.777.777C10 15.689 10 16.46 10 18s0 2.31-.347 2.877c-.194.316-.46.582-.777.776C8.311 22 7.54 22 6 22s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.776C2 20.31 2 19.54 2 18m12 0c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C15.689 14 16.46 14 18 14s2.31 0 2.877.347c.316.194.582.46.776.777C22 15.689 22 16.46 22 18s0 2.31-.347 2.877a2.36 2.36 0 0 1-.776.776C20.31 22 19.54 22 18 22s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.776C14 20.31 14 19.54 14 18M2 6c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C3.689 2 4.46 2 6 2s2.31 0 2.876.347c.317.194.583.46.777.777C10 3.689 10 4.46 10 6s0 2.31-.347 2.876c-.194.317-.46.583-.777.777C8.311 10 7.54 10 6 10s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.777C2 8.311 2 7.54 2 6m12 0c0-1.54 0-2.31.347-2.876c.194-.317.46-.583.777-.777C15.689 2 16.46 2 18 2s2.31 0 2.877.347c.316.194.582.46.776.777C22 3.689 22 4.46 22 6s0 2.31-.347 2.876c-.194.317-.46.583-.776.777C20.31 10 19.54 10 18 10s-2.31 0-2.876-.347a2.35 2.35 0 0 1-.777-.777C14 8.311 14 7.54 14 6"
            color="currentColor" />
        </svg>
      </label>
    </div>
  </div>
  <section class="relative px-3 pb-6 mb-8 lg:flex lg:gap-10">
    <input type="checkbox" id="toggle-filter" class="hidden peer/filter" />
    <aside
      class="absolute top-0 left-[5%] hidden w-[90%] px-4 pt-4 pb-8 bg-sky-800 text-white rounded-xl peer-checked/filter:block lg:static lg:w-72 lg:block lg:h-max lg:shadow-xl/20">
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
      <form class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-1 lg:m-0">
        <x-forms.fieldset>
          <x-slot:legend>Categorias</x-slot:legend>

          @foreach ($categoriesProduct as $key => $name)
            <label>
              <input type="checkbox" name="categorias" value="{{ $key }}">
              {{ $name }}
            </label>
          @endforeach
        </x-forms.fieldset>
        <x-forms.fieldset>
          <x-slot:legend>Marcas</x-slot:legend>
          @foreach ($brandsProducts as $brand)
            <label>
              <input type="checkbox" name="marcas" value="{{ $brand->id }}">
              {{ $brand->name }}
            </label>
          @endforeach
        </x-forms.fieldset>
      </form>
    </aside>
    <section class="grid grid-cols-[repeat(auto-fill,minmax(240px,1fr))] justify-items-center gap-5 lg:grow">
      <!-- Aquí van las tarjetas de productos -->
      @foreach ($products as $product)
        <x-cards.product :product="$product" />
      @endforeach
    </section>
    {{ $products->onEachSide(1)->links('pages.dashboard.partials.pagination') }}
  </section>
@endsection
