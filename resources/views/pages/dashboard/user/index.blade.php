@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
@endpush

@php
  $type1 = 'modal-delete-restore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Usuarios</x-slot:textTitle>

    <button type="button" data-type="create" data-modalID="userCSE"
      class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
      <x-icons.plus class="size-6" />
      Nuevo Usuario
    </button>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Foto</th>
        <th>Nombre completo</th>
        <th>Correo</th>
        <th>Telefono</th>
        <th class="hidden md:table-cell">Estado</th>
        <th class="hidden xl:table-cell">Rol</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($users as $index => $user)
      <tr>
        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
        <td>
          <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->fullName() }}" class="h-12 aspect-auto">
        </td>
        <td class="relative max-w-44">
          <button type="button" data-type="show" data-uid="{{ $user->id }}" data-path="{{ $user->id }}"
            data-modalID="userCSE"
            class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer transition-colors button-create-edit-show peer/popup">
            {{ $user->fullName() }}
          </button>
          <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 peer-hover/popup:inline-block">
            Ver Usuario
          </x-popups.text>
        </td>
        <td class="max-w-44 truncate">{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
        <td {{ $user->active ? 'data-active' : '' }}
          class="hidden text-red-700 md:table-cell font-semibold before:content-['●'] before:me-px data-active:text-green-700">
          {{ $user->active ? 'Activo' : 'Inactivo' }}
        </td>
        <td class="hidden xl:table-cell">
          <h4 class="w-fit px-3 py-2 text-sm font-semibold text-slate-200 bg-slate-900 rounded-lg">
            {{ $user->getRoleNames()->first() }}
          </h4>
        </td>
        <td class="relative flex justify-end">
          <x-popups.contentWcheck iid="chuser-{{ $user->id }}" labelClass="hover:bg-slate-900" class="right-14">
            <x-slot:label>
              <x-icons.threeDotsX class="size-6" />
            </x-slot:label>

            <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
              @can('show users')
                <li>
                  <button type="button" data-type="show" data-uid="{{ $user->id }}" data-path="{{ $user->id }}"
                    data-modalID="userCSE"
                    class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                    <span>
                      <x-icons.show class="size-5" />
                    </span>
                    Ver Usuario
                  </button>
                </li>
              @endcan
              @can('edit users')
                <li>
                  <button type="button" data-type="edit" data-uid="{{ $user->id }}" data-path="{{ $user->id }}"
                    data-modalID="userCSE"
                    class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                    <span>
                      <x-icons.edit class="size-5" />
                    </span>
                    Editar Usuario
                  </button>
                </li>
              @endcan
              @can('delete users')
                <li>
                  <button type="button" data-text="Usuario: '{{ $user->fullName() }}'" data-uid="{{ $user->id }}"
                    data-modalID="{{ $type1 }}" data-path="{{ $user->id }}" data-delete="true"
                    class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                    <span>
                      <x-icons.trash class="size-5" />
                    </span>
                    Eliminar Usuario
                  </button>
                </li>
              @endcan
            </ul>
          </x-popups.contentWcheck>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="8" class="text-center font-semibold text-slate-300">Sin usuarios registrados</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $users->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  <h2 class="mb-5 mt-10 px-4 text-2xl font-semibold text-gray-300">Usuarios Eliminados</h2>
  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Foto</th>
        <th>Nombre completo</th>
        <th>Correo</th>
        <th>Telefono</th>
        <th class="hidden md:table-cell">Estado</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($usersDeleted as $index => $user)
      <tr>
        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
        <td>
          <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->fullName() }}" class="h-12 aspect-auto">
        </td>
        <td class="relative">
          <button type="button" data-type="show" data-uid="{{ $user->id }}" data-path="{{ $user->id }}"
            data-modalID="userCSE"
            class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
            {{ $user->fullName() }}
          </button>
          <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 peer-hover/popup:inline-block">
            Ver Usuario
          </x-popups.text>
        </td>
        <td class="text-slate-600">{{ $user->email }}</td>
        <td class="text-slate-600">{{ $user->phone }}</td>
        <td class="hidden text-slate-600 md:table-cell font-semibold before:content-['●'] before:me-px">
          {{ $user->active ? 'Activo' : 'Inactivo' }}</td>
        <td class="relative flex justify-end">
          <x-popups.contentWcheck iid="chuser-{{ $user->id }}" labelClass="hover:bg-slate-900" class="right-14">
            <x-slot:label>
              <x-icons.threeDotsX class="size-6" />
            </x-slot:label>
            <ul class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
              <li>
                <button type="button" data-type="show" data-uid="{{ $user->id }}" data-path="{{ $user->id }}"
                  data-modalID="userCSE"
                  class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                  <span>
                    <x-icons.show class="size-5" />
                  </span>
                  Ver Usuario
                </button>
              </li>
              <li>
                <button type="button" data-text="Usuario: '{{ $user->fullName() }}'" data-uid="{{ $user->id }}"
                  data-modalID="{{ $type1 }}" data-path="{{ $user->id . '/restore' }}" data-delete="false"
                  class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                  <span>
                    <x-icons.restore class="size-5" />
                  </span>
                  Restaurar Usuario
                </button>
              </li>
            </ul>
          </x-popups.contentWcheck>
        </td>
      </tr>

    @empty
      <tr>
        <td colspan="8" class="text-center font-semibold text-slate-300">Sin usuarios eliminados</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $usersDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  {{-- MODAL SHOW, EDIT --}}
  <x-modals.simple id="userCSE"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto bg-slate-200 [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form enctype="multipart/form-data" method="POST"
      class="group p-4 w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')
      <x-images.borderFill src="{{ asset('images/users') }}/sin_foto.webp"
        alt="Foto de usuario {{ $user->name }}" />

      <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fill,minmax(225px,1fr))] gap-6 text-gray-700 md:px-3">
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="name">Nombre</label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="surname">Apellido</label>
          <input type="text" id="surname" name="surname" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="email">Email</label>
          <input type="email" id="email" name="email" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="phone">Teléfono</label>
          <input type="text" id="phone" name="phone" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <section class="col-span-full group-[.editable]:hidden">
          <h4 class="mb-2 font-semibold">Dirección</h4>
          <p class="px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md">
            {{ $user->getCurrentAddress()->fullAddress() }}</p>
        </section>
        <button type="submit"
          class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
      <button
        class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- MODAL DELETE, RESTORE --}}
  <x-modals.delete id="{{ $type1 }}" />
@endsection
