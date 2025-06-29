@extends('layouts.dashboard')

@section('titleH1', 'Detalles del producto')

@section('content')
  <article class="px-3">
    <div class="flex justify-between items-center">
      <h2 class="text-3xl font-semibold">{{ $product->name }}</h2>
      <div class="flex gap-2">
        <x-buttons.ancorFill href="{{ route('products.index') }}" class="bg-slate-500 active:bg-slate-600">
          Volver
        </x-buttons.ancorFill>
        <x-buttons.ancorFill href="{{ route('products.edit', $product->id) }}" class="bg-purple-600 active:bg-purple-700">
          Editar
        </x-buttons.ancorFill>
      </div>
    </div>
    <div class="flex flex-col items-center gap-5 my-4 md:items-start md:flex-row">
      <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
        class="max-h-96 object-cover rounded-md">
      <div class="w-full px-6 py-3 flex flex-col gap-3">
        <h3 class="text-xl">Marca: {{ $product->mark }}</h3>
        <h3 class="text-xl">Precio: {{ $product->price }}</h3>
        <h3 class="text-xl">Stock: {{ $product->quantity }}</h3>
        <h3 class="text-xl">Categoría: {{ $product->category->name }}</h3>
      </div>
    </div>
  </article>
@endsection
