@extends('layouts.main')

@section('content')
<section>
	<img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" draggable="false">
	<div>
		<h3>{{ $product->mark }}</h3>
		<h1>{{ $product->name }}</h1>
		<p>${{ $product->price }}</p>
		<button>Agregar al carrito</button>
	</div>
</section>
@endsection