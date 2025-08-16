@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="text-center text-3xl md:grow"
    class="flex flex-col-reverse items-center md:flex-row-reverse md:justify-around">
    <x-slot:textTitle>Usuario #{{ $user->id }}</x-slot:textTitle>

    <div class="w-full px-3 mb-4 flex justify-around md:mb-0 md:w-max md:gap-4 md:justify-normal">
      <x-buttons.linkFill href="{{ route('users.index') }}" class="bg-slate-500 active:bg-slate-600">
        Volver
      </x-buttons.linkFill>
      <x-buttons.linkFill href="{{ route('users.edit', $user->id) }}" class="bg-purple-600 active:bg-purple-700">
        Editar
      </x-buttons.linkFill>
    </div>
  </x-sections.headerTitle>

  <article class="px-3 my-4 flex flex-col items-center gap-5 md:items-start md:flex-row">
    <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->name }}"
      class="max-h-96 object-cover rounded-md">
    <div class="w-full px-6 py-3 flex flex-col gap-3">
      <h3 class="text-xl">Nombre: {{ $user->fullName() }}</h3>
      <h3 class="text-xl">Correo: {{ $user->email }}</h3>
      <h3 class="text-xl">Teléfono: {{ $user->phone }}</h3>
    </div>
  </article>
@endsection
