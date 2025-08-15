@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="grow text-center"
    class="mb-8 flex flex-row-reverse items-center justify-end gap-4 sm:relative sm:justify-center">
    <x-slot:textTitle>Nuevo Usuario</x-slot:textTitle>

    <x-buttons.linkFill href="{{ route('users.index') }}"
      class="bg-slate-700 active:bg-slate-600 sm:absolute sm:left-4 sm:top-1/2 sm:-translate-y-1/2">
      Volver
    </x-buttons.linkFill>
  </x-sections.headerTitle>

  <x-forms.grid2 action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <x-inputs.withLabel forLabel="name1" title="Nombre" id="name1" name="name" required />
    <x-inputs.withLabel forLabel="surname" title="Apellido" id="surname" name="surname" required />
    <x-inputs.withLabel forLabel="email" title="Correo" id="email" name="email" type="email" required />
    <x-inputs.withLabel forLabel="phone" title="Telefono" id="phone" name="phone" type="tel" required />
    <x-inputs.withLabel forLabel="image" title="Imagen" id="image" name="image" />

    <div class="my-4 flex items-center gap-3 justify-around sm:col-span-2">
      <button type="submit"
        class="px-3 py-2 bg-emerald-700 rounded-md hover:bg-emerald-600 cursor-pointer">Agregar</button>
      <button type="reset" class="px-4 py-2 bg-red-900 rounded-md hover:bg-red-800 cursor-pointer">Limpiar</button>
    </div>
  </x-forms.grid2>
@endsection
