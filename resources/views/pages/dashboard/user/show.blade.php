@extends('layouts.dashboard')

@section('content')
  <x-sections.headerTitle classTitle="text-center text-3xl md:grow"
    class="flex flex-col-reverse items-center md:flex-row-reverse md:justify-around">
    <x-slot:textTitle>Usuario #{{ $user->id }}</x-slot:textTitle>

    <div class="w-full px-3 flex justify-around md:mb-0 md:w-max md:gap-4 md:justify-normal">
      <x-buttons.linkFill href="{{ route('users.index') }}" class="bg-slate-500 active:bg-slate-600">
        Volver al listado
      </x-buttons.linkFill>
      <x-buttons.linkFill href="{{ route('users.edit', $user->id) }}" class="bg-purple-600 active:bg-purple-700">
        Editar Usuario
      </x-buttons.linkFill>
    </div>
  </x-sections.headerTitle>

  <article class="px-3 pt-6 flex flex-col items-center justify-center gap-8 lg:flex-row">
    <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->name }}"
      class="max-h-96 object-cover rounded-md">
    <div class="w-full">
      <div class="px-6 py-3 mb-4 flex gap-3 border border-slate-500 rounded-md divide-x-2 divide-dashed divide-slate-500">
        <ul class="w-1/3 pr-3 text-lg space-y-3">
          <li>Nombre completo</li>
          <li>Correo</li>
          <li>Teléfono</li>
        </ul>
        <div class="w-2/3 text-xl text-center space-y-3 md:ps-5 md:text-start">
          <h3>{{ $user->fullName() }}</h3>
          <h3>{{ $user->email }}</h3>
          <h3>{{ $user->phone }}</h3>
        </div>
      </div>
      <div class="py-3">
        <h2 class="mb-4 text-xl text-center">Mis direcciones</h2>
        <ul class="px-6 py-1 border border-slate-500 divide-y rounded-md">
          @foreach ($user->addresses as $address)
            <li class="py-3 flex gap-4 items-center">
              <h3 class="text-lg font-semibold">{{ $address->name }}</h3>
              <p class="grow">{{ $address->fullAddress() }}</p>
              <span>{{ $address->is_default ? 'Establecida como dirección por defecto' : '--' }}</span>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </article>
@endsection
