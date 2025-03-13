@extends('layouts.main')

@section('scripts')
<script defer>
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
@endsection

@section('content')
<nav class="px-2 my-4 flex" aria-label="Breadcrumb">
	<ol
		class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse text-xs font-medium text-gray-700 dark:text-slate-300 [&_a]:hover:text-black [&_a]:dark:hover:text-purple-500">
		<li class="inline-flex items-center">
			<a href="catag.html" class="inline-flex items-center gap-2">
				<svg width=" 12" height="12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
					viewBox="0 0 20 20">
					<path
						d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
				</svg>
				Home
			</a>
		</li>
		<li>
			<div class="flex items-center gap-1">
				<svg width="10" height="10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
					viewBox="0 0 6 10">
					<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						d="m1 9 4-4-4-4" />
				</svg>
				<a href="#" class="ms-1 md:ms-2">Categoria</a>
			</div>
		</li>
		<li>
			<div class="flex items-center gap-1">
				<svg width="10" height="10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
					viewBox="0 0 6 10">
					<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						d="m1 9 4-4-4-4" />
				</svg>
				<a href="#" class="ms-1 md:ms-2">SubCategoria</a>
			</div>
		</li>
		<li aria-current="page">
			<div class="flex items-center gap-1">
				<svg width="10" height="10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
					viewBox="0 0 6 10">
					<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						d="m1 9 4-4-4-4" />
				</svg>
				<span class="ms-1 md:ms-2">Producto 1</span>
			</div>
		</li>
	</ol>
</nav>
<section class="px-3 py-4 border border-slate-300 dark:border-slate-700 rounded-md bg-slate-200 dark:bg-black/10">
	<article class=" py-3 flex flex-col dark:divide-white/10 md:divide-x-2 md:divide-black/10 md:flex-row">
		<section class="flex items-center justify-center md:w-4/7">
			<!-- Pre-visualización de las imágenes -->
			<div class="w-1/5 hidden md:block">
				<img src="product.webp" alt="producto">
				<img src="product.webp" alt="producto">
			</div>
			<div class="md:w-4/5">
				<img src="product.webp" alt="producto">
			</div>
		</section>
		<section class="w-full px-4 py-8 md:w-3/7 md:py-4">
			<!-- Nomre producto: Categoria + nombre del producto -->
			<div>
				<h3 class="text-2xl font-semibold">Nombre producto x 2Lt</h3>
				<p class="text-sm">
					<span class="me-3 font-bold uppercase">Categoria</span> |
					<span class="ms-3 font-semibold">SKU: 12345678901</span>
				</p>
			</div>
			<h4 class="my-8 text-xl font-bold">$1.234,56</h4>
			<ul class="ms-6 mb-8 list-disc text-sm">
				<li>Tipo de producto: Producto</li>
				<li>Contenido: 2Lt</li>
				<li>Envase: Botella de vidrio</li>
			</ul>
			<button
				class="w-full px-4 py-3 bg-emerald-800 rounded-lg font-bold cursor-pointer hover:bg-emerald-700 active:bg-emerald-800">Agregar</button>
		</section>
	</article>
</section>
<section class="w-full px-3 py-6 my-4 overflow-x-hidden lg:w-1/2">
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
			<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Impedit perferendis magnam libero! Consectetur
				libero nisi vitae, earum tenetur optio, corrupti aut quasi ratione similique quod, accusamus ipsa porro
				atque
				molestias!</p>
		</article>
		<article class="px-3 flex flex-col gap-5">
			<h2 class="text-xl">Información</h2>
			<ul class="ms-4 [&>li]:before:content-['\2022'] [&>li]:before:me-[.5rem]">
				<li>Tipo de producto: Producto</li>
				<li>Contenido: 2Lt</li>
				<li>Envase: Botella de vidrio</li>
			</ul>
		</article>
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