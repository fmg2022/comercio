@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="text-center text-3xl md:grow"
    class="flex flex-col-reverse items-center md:flex-row-reverse md:justify-around">
    <x-slot:textTitle>{{ $product->name }}</x-slot:textTitle>

    <div class="w-full px-3 mb-4 flex justify-around md:mb-0 md:w-max md:gap-4 md:justify-normal">
      <x-buttons.linkFill href="{{ url()->previous() }}" class="bg-slate-500 active:bg-slate-600">
        Volver
      </x-buttons.linkFill>
      @can('update_products')
        <x-buttons.linkFill href="{{ route('products.edit', $product->id) }}" class="bg-purple-600 active:bg-purple-700">
          Editar
        </x-buttons.linkFill>
      @endcan
      @can('view_any_products')
        <x-buttons.linkFill href="{{ route('products.index') }}" class="bg-indigo-500 active:bg-indigo-600">
          Ver listado
        </x-buttons.linkFill>
      @endcan
    </div>
  </x-sections.headerTitle>

  <article class="px-3 my-4 flex flex-col items-center gap-5 md:items-start md:flex-row">
    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
      class="max-h-96 object-cover rounded-md">
    <div class="w-full px-6 py-3 flex flex-col gap-3 text-xl">
      <h3>Marca: {{ $product->brand->name }}</h3>
      <h3>SKU: {{ $product->sku }}</h3>
      <h3>Precio: {{ $product->price }}</h3>
      <h3>Stock: {{ $product->stock }}</h3>
      <h3>Peso: {{ $product->weight }}</h3>
      <h3>Envase: {{ $product->container }}</h3>
      <h3>Categoría: {{ $product->category->name }}</h3>
      <p>Descripción: {{ $product->description }}</p>
    </div>
  </article>
@endsection
