@props(['product'])

<article class="max-w-sm h-max rounded-xl shadow-lg bg-slate-300 pb-3 overflow-hidden">
	<a href="{{ route('product.show', $product->id) }}" class="block">
		<img class="aspect-square" src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
			draggable="false" width="310">
	</a>
	<div class="px-6 py-4">
		<h2 class="font-bold text-2xl mb-1 text-slate-700">{{ $product->mark }}</h2>
		<p class="text-slate-500 text-base">{{ $product->name }}</p>
	</div>
	<form class="px-6 pt-4 pb-2 flex flex-col justify-between gap-5">
		<p class="text-slate-600 text-xl py-1">${{ $product->price }}</p>
		<button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded-md hover:bg-slate-600">Agregar</button>
	</form>
</article>