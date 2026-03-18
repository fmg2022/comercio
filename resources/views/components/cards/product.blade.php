<article class="max-w-sm h-max pb-3 rounded-xl shadow-lg bg-slate-300 overflow-hidden">
  <a href="{{ route('product.show', $product->id) }}"
    class="relative hover:[&>span]:bg-slate-500 hover:[&>span]:sm:opacity-100">
    <img class="aspect-square" src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
      draggable="false" width="310">
    <span
      class="absolute bottom-0 left-1/2 -translate-x-1/2 px-3 py-2 text-white bg-slate-600 rounded-lg sm:bottom-1/2 sm:translate-y-1/2 sm:opacity-0 transition-opacity duration-300">
      Ver Producto
    </span>
  </a>
  <div class="px-6 py-4">
    <h2 class="font-bold text-2xl mb-1 text-slate-700">{{ $product->brand->name }}</h2>
    <p class="text-slate-500 text-base">{{ $product->name }}</p>
  </div>
  <form action="{{ route('cart.addToCart') }}" method="POST"
    class="px-6 pt-4 pb-2 flex flex-col justify-between gap-5">
    @csrf
    <input type="hidden" name="id" value="{{ $product->id }}">
    <section class="flex justify-between items-center">
      <p class="py-1 text-slate-600 text-xl">${{ $product->priceFormated }}</p>
      <div class="flex flex-col items-center justify-center">
        <label class="w-full max-w-16 grid grid-cols-1">
          <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
            class="px-3 py-1.5 text-base text-gray-900 bg-white rounded-md outline outline-offset-1 outline-gray-400 sm:text-sm">
        </label>
      </div>
    </section>
    <button type="submit"
      class="bg-slate-700 text-white px-4 py-2 rounded-md hover:bg-slate-600 cursor-pointer">Agregar</button>
  </form>
</article>
