@extends('layouts.dashboard')

@section('titleH1', 'Nuevo Producto')

@section('content')
<div class="@container px-2 md:px-4">
  <form
    class="px-3 py-4 mx-auto max-w-xl flex flex-col gap-5 border border-slate-100/20 rounded-md @xl:[&>label]:flex-row @xl:px-6">
    <label class="flex flex-col items-center gap-5">
      <span class="me-auto">Nombre:</span>
      <input type="text" name="name" class="w-full max-w-xs px-3 py-2 outline-none rounded-lg bg-white/10"
        placeholder="Nombre del producto" required>
    </label>
    <label class="flex flex-col items-center gap-5">
      <span class="me-auto">Precio:</span>
      <input type="text" name="price" class="w-full max-w-xs px-3 py-2 outline-none rounded-lg bg-white/10"
        placeholder="$1500,50" required>
    </label>
    <div class="flex items-center gap-3 justify-end">
      <button type="submit"
        class="px-3 py-2 bg-emerald-700 rounded-md hover:bg-emerald-600 cursor-pointer">Agregar</button>
      <button type="reset" class="px-3 py-2 bg-red-900 rounded-md hover:bg-red-800 cursor-pointer">Limpiar</button>
    </div>
  </form>
  <a href="{{ route('products.index') }}"
    class="max-w-max px-4 py-2 mt-5 flex gap-1 items-center justify-baseline text-xl border-b border-t border-purple-500/30 rounded-xl shadow-md hover:text-purple-600 hover:shadow-purple-200/15 transition-all duration-150 ease-in-out">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
      <path fill="currentColor"
        d="M13.83 19a1 1 0 0 1-.78-.37l-4.83-6a1 1 0 0 1 0-1.27l5-6a1 1 0 0 1 1.54 1.28L10.29 12l4.32 5.36a1 1 0 0 1-.78 1.64" />
    </svg>
    Volver
  </a>
</div>
@endsection