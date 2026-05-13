@extends('layouts.dashboard')

{{-- Agregar mensaje de error cuando sea necesario --}}

@section('content')
  <x-sections.headerTitle classTitle="grow text-center"
    class="flex flex-row-reverse items-center justify-end gap-4 sm:relative sm:justify-center">
    <x-slot:textTitle>Editar Producto</x-slot:textTitle>

    <x-buttons.linkFill href="{{ route('products.index') }}"
      class="bg-slate-700 active:bg-slate-600 sm:absolute sm:left-4 sm:top-1/2 sm:-translate-y-1/2">
      Ir al listado
    </x-buttons.linkFill>
  </x-sections.headerTitle>

  <x-forms.grid2 class="max-w-xl" action="{{ route('products.update', $product->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-inputs.withLabel forLabel="name" title="Nombre" id="name" name="name" value="{{ $product->name }}" />
    <x-inputs.withLabel forLabel="price" title="Precio" id="price" name="price" value="{{ $product->price }}" />
    <x-inputs.withLabel forLabel="stock" title="Stock" id="stock" name="stock" type="number"
      value="{{ $product->stock }}" min="0" max="999" />
    <x-inputs.withLabel forLabel="weight" title="Peso" id="weight" name="weight" value="{{ $product->weight }}" />
    <x-inputs.withLabel forLabel="container" title="Envase" id="container" name="container"
      value="{{ $product->container }}" />
    <x-inputs.withLabel forLabel="sku" title="SKU" id="sku" name="sku" value="{{ $product->sku }}" />
    <x-inputs.withLabel forLabel="image" title="Imagen" id="image" name="image" value="{{ $product->image }}" />

    <div class="relative w-full max-w-xs mt-6">
      <label for="brand_id" class="absolute left-4 -top-4 -translate-y-1/2 text-slate-300">
        Marca
      </label>
      <select id="brand_id" name="brand_id" class="px-3 py-2 text-black bg-white/75 rounded-md outline-none self-end">
        <option value="" class="bg-slate-200 disabled:text-black" disabled selected>Selecciona una marca
        </option>
        @foreach ($brands as $brand)
          <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
            {{ $brand->name }}
          </option>
        @endforeach
      </select>
    </div>

    <select name="category_id" class="px-3 py-2 text-black bg-white/75 rounded-md outline-none col-span-full">
      <option value="" class="bg-slate-200 disabled:text-black" disabled selected>Selecciona una categoría
      </option>
      @foreach ($categories as $category)
        <option value="{{ $category['id'] }}" {{ $category['nivel'] != 2 ? 'disabled' : '' }}
          @class([
              'text-slate-800',
              'bg-purple-100 font-bold' => $category['nivel'] === 0,
              'bg-purple-50 font-semibold' => $category['nivel'] === 1,
              'bg-slate-50' => $category['nivel'] === 2,
          ])>
          {{ ($category['nivel'] === 3 ? '--' : '') . $category['name'] }}
        </option>
      @endforeach
    </select>

    <label class="flex flex-col items-center gap-5 col-span-full">
      <span class="me-auto">Descripción:</span>
      <textarea class='w-full max-w-xs px-3 py-2 field-sizing-fixed outline-none rounded-lg bg-white/10' name="description">{{ $product->description }}</textarea>
    </label>

    <div class="flex items-center gap-3 justify-end col-span-full">
      <button type="submit"
        class="px-4 py-2 bg-emerald-700 rounded-md hover:bg-emerald-600 cursor-pointer">Guardar</button>
      <x-buttons.linkFill href="{{ route('products.index') }}"
        class="bg-red-900 rounded-md hover:bg-red-800">Cancelar</x-buttons.linkFill>
    </div>
  </x-forms.grid2>
@endsection
