@extends('layouts.main')

@section('content')
<section class="flex flex-col items-center justify-evenly md:flex-row gap-8 p-4 my-8 bg-slate-300">
	<img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" draggable="false"
		class="w-full max-w-96 aspect-square">
	<div class="flex flex-col items-center justify-center md:justify-between md:h-72 w-full px-4">
		<div>
			<h3 class="text-base font-bold text-slate-500">{{ $product->mark }}</h3>
			<h1 class="text-2xl font-bold text-slate-800 pb-2">{{ $product->name }}</h1>
		</div>
		<p class="text-2xl text-slate-800 py-3">${{ str_replace('.00', '',$product->price) }}</p>
		<button class="bg-slate-700 text-white px-4 py-2 rounded-md hover:bg-slate-600">Agregar</button>
	</div>
</section>

<!-- SECCION: Slider de productos recomendados -->
<x-sections.carousel-img :listId="'list-product'" :btnsId="'btns-product'" :class="'px-3'"
	title="Productos recomendados">
	@foreach ($products as $product)
	<li class="item flex justify-center items-center snap-start">
		<x-card :product="$product" />
	</li>
	@endforeach
</x-sections.carousel-img>
@endsection