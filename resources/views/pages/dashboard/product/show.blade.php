@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="text-center text-3xl"
    class="flex flex-col-reverse items-center md:flex-row-reverse md:justify-around">
    <x-slot:textTitle>{{ $product->name }}</x-slot:textTitle>

    <div class="w-full mb-4 flex justify-around md:mb-0 md:w-max md:gap-4 md:justify-normal">
      <x-buttons.linkFill href="{{ route('products.index') }}" class="bg-slate-500 active:bg-slate-600">
        Volver
      </x-buttons.linkFill>
      <x-buttons.linkFill href="{{ route('products.edit', $product->id) }}" class="bg-purple-600 active:bg-purple-700">
        Editar
      </x-buttons.linkFill>
    </div>
  </x-sections.headerTitle>

  <article class="px-3 my-4 flex flex-col items-center gap-5 md:items-start md:flex-row">
    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
      class="max-h-96 object-cover rounded-md">
    <div class="w-full px-6 py-3 flex flex-col gap-3">
      <h3 class="text-xl">Marca: {{ $product->mark }}</h3>
      <h3 class="text-xl">Precio: {{ $product->price }}</h3>
      <h3 class="text-xl">Stock: {{ $product->quantity }}</h3>
      <h3 class="text-xl">Categoría: {{ $product->category->name }}</h3>
      <p class="text-xl">Descripción: {{ $product->description }}</p>
    </div>
  </article>
@endsection
