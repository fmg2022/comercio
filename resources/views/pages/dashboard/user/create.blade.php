@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="grow text-center"
    class="flex flex-row-reverse items-center justify-end gap-4 sm:relative sm:justify-center">
    <x-slot:textTitle>Nuevo Usuario</x-slot:textTitle>

    <x-buttons.linkFill href="{{ route('users.index') }}"
      class="bg-slate-700 active:bg-slate-600 sm:absolute sm:left-4 sm:top-1/2 sm:-translate-y-1/2">
      Volver
    </x-buttons.linkFill>
  </x-sections.headerTitle>

  <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off"
    class="max-w-md mx-auto grid gap-4 grid-cols-1 place-items-center sm:grid-cols-2 sm:w-full">
    @csrf
    <x-inputs.withLabel containerClass="w-full" forLabel="name1" title="Nombre" id="name1" name="name" required />
    <x-inputs.withLabel containerClass="w-full" forLabel="surname" title="Apellido" id="surname" name="surname"
      required />
    <x-inputs.withLabel containerClass="w-full" forLabel="email" title="Correo" id="email" name="email"
      type="email" required />
    <x-inputs.withLabel containerClass="w-full" forLabel="phone" title="Telefono" id="phone" name="phone"
      type="tel" required />
    <x-inputs.withLabel containerClass="w-full" forLabel="image" title="Imagen" id="image" name="image" />
  </form>
@endsection
