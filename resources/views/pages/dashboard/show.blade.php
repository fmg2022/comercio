@extends('layouts.dashboard')

@section('titleH1', 'Detalles del producto')

@section('content')
  <article>
    <div class="flex justify-between items-center">
      <h2>{{ $product->name }}</h2>
      <a href="{{ route('product.edit') }}">Editar</a>
    </div>
    <div>
      <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
        class="w-32 h-32 object-cover rounded-md">
      <div>
        <h3>Marca: {{ $product->mark }}</h3>
        <h3>Precio: {{ $product->price }}</h3>
        <h3>Stock: {{ $product->quantity }}</h3>
        <h3>Categoría: {{ $product->category->name }}</h3>
      </div>
    </div>
  </article>
@endsection
