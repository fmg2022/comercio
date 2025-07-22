@extends('layouts.dashboard')


{{-- Agregar mensaje de error cuando sea necesario --}}

@section('content')
  <x-sections.headerTitle classTitle="text-center">
    <x-slot:textTitle>Editar Producto</x-slot:textTitle>
  </x-sections.headerTitle>

  <div class="@container px-2 md:px-4">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
      class="px-3 py-4 mx-auto my-4 max-w-xl flex flex-col gap-5 border border-slate-100/20 rounded-md @xl:[&>label]:flex-row @xl:px-6">
      @csrf
      @method('PUT')
      <x-inputs.withLabel title="Nombre" name="name" value="{{ $product->name }}" />
      <x-inputs.withLabel title="Marca" name="mark" value="{{ $product->mark }}" />
      <x-inputs.withLabel title="Precio" name="price" value="{{ $product->price }}" />
      <x-inputs.withLabel title="Stock" name="quantity" type="number" value="{{ $product->quantity }}" min="0"
        max="9999" />
      <x-inputs.withLabel title="SKU" name="sku" value="{{ $product->sku }}" />
      <x-inputs.withLabel title="Imagen" name="image" value="{{ $product->image }}" />

      <select name="category_id" class="px-3 py-2 mb-5 text-black bg-white/75 rounded-md outline-none">
        @foreach ($categories as $category)
          <option value="{{ $category->id }}" {{ $category->children->count() ? 'disabled' : '' }}
            class="font-semibold text-purple-700 bg-purple-50">
            {{ $category->name }}
          </option>
          @if ($category->children->count())
            @foreach ($category->children as $child)
              <option value="{{ $child->id }}" {{ $child->children->count() ? 'disabled' : '' }}
                class="text-purple-700" {{ $product->category_id == $child->id ? 'selected' : '' }}>
                {{ $child->name }}
              </option>

              @if ($child->children->count())
                @foreach ($child->children as $grandChild)
                  <option value="{{ $grandChild->id }}" {{ $grandChild->children->count() ? 'disabled' : '' }}
                    {{ $product->category_id == $grandChild->id ? 'selected' : '' }}>
                    -- {{ $grandChild->name }}
                  </option>
                @endforeach
              @endif
            @endforeach
          @endif
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
    </form>
  </div>
@endsection
