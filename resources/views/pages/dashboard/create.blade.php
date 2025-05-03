@extends('layouts.dashboard')

@section('titleH1', 'Nuevo Producto')

{{-- Agregar mensaje de error cuando sea necesario --}}

@section('content')
<div class="@container px-2 md:px-4">
  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
    class="px-3 py-4 mx-auto my-4 max-w-xl flex flex-col gap-5 border border-slate-100/20 rounded-md @xl:[&>label]:flex-row @xl:px-6">
    @csrf
    <x-inputs.withLabel title="Nombre" name="name" placeholder="Nombre del producto" required />
    <x-inputs.withLabel title="Marca" name="mark" placeholder="Marca del producto" required />
    <x-inputs.withLabel title="Precio" name="price" placeholder="$1550.65" required />
    <x-inputs.withLabel title="Stock" name="quantity" type="number" value="0" min="0" max="9999" />
    <x-inputs.withLabel title="Imagen" name="image" placeholder=".jpg, .webp, .png, ..." />

    <select name="category" class="px-3 py-2 mb-5 text-black bg-white/75 rounded-md outline-none">
      <option value="" disabled selected>Selecciona una categoría</option>
      @foreach ($categories as $category)
      <option value="{{ $category->id }}">{{ $category->name }}</option>
      @endforeach
    </select>

    <div class="flex items-center gap-3 justify-end">
      <button type="submit"
        class="px-3 py-2 bg-emerald-700 rounded-md hover:bg-emerald-600 cursor-pointer">Agregar</button>
      <button type="reset" class="px-3 py-2 bg-red-900 rounded-md hover:bg-red-800 cursor-pointer">Limpiar</button>
    </div>
  </form>
  <x-buttons.ancorShadow href="{{ route('products.index') }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
      <path fill="currentColor"
        d="M13.83 19a1 1 0 0 1-.78-.37l-4.83-6a1 1 0 0 1 0-1.27l5-6a1 1 0 0 1 1.54 1.28L10.29 12l4.32 5.36a1 1 0 0 1-.78 1.64" />
    </svg>
    Volver
  </x-buttons.ancorShadow>
</div>
@endsection