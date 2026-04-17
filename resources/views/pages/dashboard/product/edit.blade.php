@extends('layouts.dashboard')

{{-- Agregar mensaje de error cuando sea necesario --}}

@section('content')
  <x-sections.headerTitle classTitle="grow text-center"
    class="flex flex-row-reverse items-center justify-end gap-4 sm:relative sm:justify-center">
    <x-slot:textTitle>Nuevo Producto</x-slot:textTitle>

    <x-buttons.linkFill href="{{ route('products.index') }}"
      class="bg-slate-700 active:bg-slate-600 sm:absolute sm:left-4 sm:top-1/2 sm:-translate-y-1/2">
      Volver
    </x-buttons.linkFill>
  </x-sections.headerTitle>

  <x-forms.grid2 action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-inputs.withLabel forLabel="name" title="Nombre" id="name" name="name" value="{{ $product->name }}" />
    <x-inputs.withLabel forLabel="price" title="Precio" id="price" name="price" value="{{ $product->price }}" />
    <x-inputs.withLabel forLabel="stock" title="Stock" id="stock" name="stock" type="number"
      value="{{ $product->stock }}" min="0" max="9999" />
    <x-inputs.withLabel forLabel="sku" title="SKU" id="sku" name="sku" value="{{ $product->sku }}" />
    <x-inputs.withLabel forLabel="image" title="Imagen" id="image" name="image" value="{{ $product->image }}" />

    <select name="brand_id" class="px-3 py-2 mb-5 text-black bg-white/75 rounded-md outline-none">
      <option value="" class="bg-slate-200 disabled:text-black" disabled selected>Selecciona una marca
      </option>
      @foreach ($brands as $brand)
        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
          {{ $brand->name }}
        </option>
      @endforeach
    </select>

    <select name="category_id" class="px-3 py-2 mb-5 text-black bg-white/75 rounded-md outline-none">
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

    <label class="flex flex-col items-center gap-5">
      <span class="me-auto">Descripción:</span>
      <textarea class='w-full max-w-xs px-3 py-2 field-sizing-fixed outline-none rounded-lg bg-white/10' name="description">{{ $product->description }}</textarea>
    </label>

    <div class="flex items-center gap-3 justify-end">
      <button type="submit"
        class="px-4 py-2 bg-emerald-700 rounded-md hover:bg-emerald-600 cursor-pointer">Guardar</button>
      <x-buttons.linkFill href="{{ route('products.index') }}"
        class="bg-red-900 rounded-md hover:bg-red-800">Cancelar</x-buttons.linkFill>
    </div>
  </x-forms.grid2>
@endsection
