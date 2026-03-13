@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="grow text-center">
    <x-slot:textTitle>Crear Dirección</x-slot:textTitle>
  </x-sections.headerTitle>

  <form action="{{ route('addresses.store') }}" method="POST" enctype="multipart/form-data"
    class="max-w-md w-full mx-auto py-7 grid gap-4 place-items-center border border-purple-900/40 rounded-xl"
    autocomplete="off">
    @csrf
    <x-inputs.withLabel forLabel="name" title="Nombre" id="name" name="name"
      placeholder="Casa, Trabajo, Familia, etc" />
    <x-inputs.withLabel forLabel="street" title="Calle" id="street" name="street" />
    <x-inputs.withLabel forLabel="city" title="Ciudad" id="city" name="city" />
    <x-inputs.withLabel forLabel="postal_code" title="Código Postal" id="postal_code" name="postal_code" />
    <x-inputs.withLabel forLabel="province" title="Provincia" id="province" name="province" />
    <div class="mb-4 col-span-full flex flex-wrap gap-x-10 gap-y-5 items-center justify-center">
      <p class="w-max">¿Establecer dirección por defecto?</p>
      <x-inputs.checkSwitch class="bg-slate-200 checked:bg-green-700 cursor-pointer"
        classLabel="bg-white border-slate-300 peer-checked/switch:border-green-700 cursor-pointer" />
    </div>

    <div class="flex items-center gap-3 justify-end">
      <button type="submit"
        class="px-4 py-2 bg-emerald-700 rounded-md hover:bg-emerald-600 cursor-pointer">Crear</button>
      <x-buttons.linkFill href="{{ route('addresses.index') }}"
        class="bg-red-900 rounded-md hover:bg-red-800">Cancelar</x-buttons.linkFill>
    </div>
  </form>
@endsection
