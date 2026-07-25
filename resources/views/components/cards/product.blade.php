@php $offerID = $product->activeOffer(); @endphp
<article
  class="h-full flex rounded-xl bg-slate-300 shadow-lg overflow-hidden group-[.list]:max-w-full group-[.list]:w-full group-[.list]:p-0 group-[.list]:flex-row group-[.grilla]:max-w-fit group-[.grilla]:pb-3 group-[.grilla]:flex-col">
  <a href="{{ route('products.show', $product->id) }}"
    class="relative hover:[&>span]:bg-slate-500 hover:[&>span]:sm:opacity-100 group-[.list]:flex group-[.list]:max-w-56">
    <img class="aspect-square" src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
      draggable="false" width="310">
    <span
      class="absolute bottom-0 left-1/2 -translate-x-1/2 px-3 py-2 text-nowrap text-white bg-slate-600 rounded-lg sm:bottom-1/2 sm:translate-y-1/2 sm:opacity-0 transition-opacity duration-300">
      Ver Producto
    </span>
  </a>
  <div class="grow flex flex-col group-[.grilla]:justify-between">
    <div
      class="px-6 py-4 text-pretty group-[.list]:grow-0 group-[.list]:max-w-full group-[.grilla]:grow group-[.grilla]:max-w-77.5">
      <h2 class="font-bold text-2xl mb-1 text-slate-700">{{ $product->brand->name }}</h2>
      <p class="text-slate-500 text-base">{{ "{$product->name} - {$product->weight}" }}</p>
    </div>
    <livewire:_partials.add-to-cart :productId="$product->id" :offerId="$offerID" :offers="$offers" />
  </div>
</article>
