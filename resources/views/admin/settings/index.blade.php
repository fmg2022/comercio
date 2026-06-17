@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle>
    <x-slot:textTitle>Configuración de la tienda</x-slot:textTitle>
  </x-sections.headerTitle>

  <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full bg-slate-800 rounded-xl shadow-lg p-8">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Configuración de la tienda</h2>

      <form method="POST" action="{{ route('site.settings.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Nombre de la tienda -->
        <div>
          <label for="store_name" class="block text-sm font-medium text-gray-300 mb-1">Nombre de la tienda</label>
          <input type="text" name="store_name" id="store_name"
            value="{{ old('store_name', $settings['store_name'] ?? '') }}"
            class="w-full px-4 py-2 border border-gray-500 rounded-lg outline-0 focus:border-gray-300 transition">
          @error('store_name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Dirección -->
        <div>
          <label for="address" class="block text-sm font-medium text-gray-300 mb-1">Dirección</label>
          <textarea name="address" id="address" rows="3"
            class="w-full px-4 py-2 border border-gray-500 rounded-lg outline-0 focus:border-gray-300 transition">{{ old('address', $settings['address'] ?? '') }}</textarea>
          @error('address')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Teléfono -->
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Teléfono</label>
          <input type="text" name="phone" id="phone" value="{{ old('phone', $settings['phone'] ?? '') }}"
            class="w-full px-4 py-2 border border-gray-500 rounded-lg outline-0 focus:border-gray-300 transition">
          @error('phone')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email de contacto -->
        <div>
          <label for="email_contact" class="block text-sm font-medium text-gray-300 mb-1">Email de contacto</label>
          <input type="email" name="email_contact" id="email_contact"
            value="{{ old('email_contact', $settings['email_contact'] ?? '') }}"
            class="w-full px-4 py-2 border border-gray-500 rounded-lg outline-0 focus:border-gray-300 transition">
          @error('email_contact')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Sucursales (JSON) -->
        <div>
          <label for="branches" class="block text-sm font-medium text-gray-300 mb-1">Sucursales (JSON)</label>
          <textarea name="branches" id="branches" rows="5"
            class="w-full px-4 py-2 border border-gray-500 rounded-lg font-mono text-sm outline-0 focus:border-gray-300 transition">{{ old('branches', $settings['branches'] ?? '') }}</textarea>
          <p class="text-xs text-gray-400 mt-1">Formato JSON válido, ej: [{"nombre":"Local Centro","direccion":"Calle
            123"}]</p>
          @error('branches')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Botón submit -->
        <div class="pt-4">
          <button type="submit"
            class="w-full bg-purple-900 hover:bg-purple-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg cursor-pointer">
            Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
