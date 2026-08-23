@extends('layouts.dashboard')

@push('scripts-dashboard')
  @can('view_any_users')
    <script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
  @endcan
  @can('delete_users')
    <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  @endcan
@endPush
@php
  $type1 = 'modal-delete-restore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Usuarios</x-slot:textTitle>

    @can('create_users')
      <button type="button" data-type="create" data-modalID="userCSE"
        class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
        <x-icons.plus class="size-6" />
        Nuevo Usuario
      </button>
    @endcan
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
    </x-slot:thead>
    <x-slot:tbody>
      @forelse ($users as $index => $user)
        <tr>
          <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
          <td>
            <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->fullName() }}" class="h-12 aspect-auto">
          </td>
          <td class="relative max-w-44">
            <button type="button" data-type="show" data-uid="{{ $user->id }}" data-path="{{ $user->id }}"
              data-modalID="userCSE" class="w-full text-left cursor-pointer button-create-edit-show peer/popup">
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
            <p class="w-fit px-3 py-2 text-sm font-semibold text-slate-200 bg-slate-900 rounded-lg">
              {{ $user->roles->first()->display_name }}
            </p>
          </td>
          <td>
            <div class="relative flex justify-end items-center">
              <x-popups.contentWcheck iid="chuser-{{ $user->id }}" labelClass="hover:bg-slate-900" class="right-14">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul
                  class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                  @can('update_roles')
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $user->id }}"
                        data-path="{{ $user->id }}/roles" data-modalID="roleCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.role class="size-5" />
                        </span>
                        {{ $user->roles->isNotEmpty() ? 'Editar Rol' : 'Asignar Rol' }}
                      </button>
                    </li>
                  @endcan
                  @can('view_users')
                    <li>
                      <button type="button" data-type="show" data-uid="{{ $user->id }}"
                        data-path="{{ $user->id }}" data-modalID="userCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.show class="size-5" />
                        </span>
                        Ver Usuario
                      </button>
                    </li>
                  @endcan
                  @can('update_users')
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $user->id }}"
                        data-path="{{ $user->id }}" data-modalID="userCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar Usuario
                      </button>
                    </li>
                  @endcan
                  @can('delete_users')
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
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center font-semibold text-slate-300">Sin usuarios registrados</td>
        </tr>
      @endforelse
    </x-slot:tbody>
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
    </x-slot:thead>
    <x-slot:tbody>
      @forelse ($usersDeleted as $index => $user)
        <tr class="text-slate-400">
          <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
          <td>
            <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->fullName() }}"
              class="h-12 aspect-auto">
          </td>
          <td class="relative">
            <button type="button" data-type="show" data-uid="{{ $user->id }}" data-path="{{ $user->id }}"
              data-modalID="userCSE" class="w-full text-left cursor-pointer button-create-edit-show peer/popup">
              {{ $user->fullName() }}
            </button>
            <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 text-white peer-hover/popup:inline-block">
              Ver Usuario
            </x-popups.text>
          </td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->phone }}</td>
          <td class="hidden md:table-cell font-semibold before:content-['●'] before:me-px">
            {{ $user->active ? 'Activo' : 'Inactivo' }}</td>
          <td class="relative flex justify-end text-slate-300">
            <x-popups.contentWcheck iid="chuser-{{ $user->id }}" labelClass="hover:bg-slate-900" class="right-14">
              <x-slot:label>
                <x-icons.threeDotsX class="size-6" />
              </x-slot:label>
              <ul class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs font-semibold">
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
                @can('delete_users')
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
                @endcan
              </ul>
            </x-popups.contentWcheck>
          </td>
        </tr>

      @empty
        <tr>
          <td colspan="8" class="text-center font-semibold text-slate-300">Sin usuarios eliminados</td>
        </tr>
      @endforelse
    </x-slot:tbody>
  </x-tables.table>

  {{ $usersDeleted->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  @can('view_any_users')
    {{-- MODAL SHOW, EDIT --}}
    <x-modals.simple id="userCSE"
      class="max-w-xl w-full max-h-[90%] overflow-y-auto bg-slate-200 [scrollbar-color:#62748e_transparent] scrollbat-thin">
      <form enctype="multipart/form-data" method="POST"
        class="group p-4 w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
        @csrf
        @method('PUT')
        <x-images.borderFill src="{{ asset('images/users') }}/sin_foto.webp" alt="Foto de usuario" />

        <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fill,minmax(225px,1fr))] gap-6 text-gray-700 md:px-3">
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="name">Nombre</label>
            <input type="text" id="name" name="name" autocomplete="name"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="surname">Apellido</label>
            <input type="text" id="surname" name="surname" autocomplete="name"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="col-span-2 pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="email">Email</label>
            <input type="email" id="email" name="email" autocomplete="email"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="phone">Teléfono</label>
            <input type="text" id="phone" name="phone" autocomplete="tel"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="dni">DNI</label>
            <input type="text" id="dni" name="dni" autocomplete="off"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div class="col-span-2 pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="address">Dirección</label>
            <input type="address" id="address" name="address" autocomplete="street-address"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <section
            class="col-span-full py-3 hidden flex-wrap gap-x-10 gap-y-5 items-center justify-center pointer-events-none group-[.editable]:pointer-events-auto group-[.editable]:flex">
            <p class="w-max">¿Activar usuario?</p>
            <x-inputs.checkSwitch name="active"
              class="bg-slate-200 checked:bg-green-700 group-[.editable]:cursor-pointer"
              classLabel="z-10 bg-white border-slate-300 peer-checked/switch:border-green-700 group-[.editable]:cursor-pointer" />
          </section>
          <button type="submit"
            class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-green-800 text-lg text-white rounded-md hover:bg-green-700 cursor-pointer sm:right-3/5">Actualizar</button>
        </fieldset>
      </form>
      <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
        <button
          class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
      </form>
    </x-modals.simple>

    {{-- MODAL DELETE, RESTORE --}}
    <x-modals.delete id="{{ $type1 }}" />
  @endcan
  @can('update_roles')
    <x-modals.simple id="roleCSE"
      class="max-w-md w-full max-h-[90%] overflow-y-auto bg-slate-200 [scrollbar-color:#62748e_transparent] scrollbat-thin">
      <form enctype="multipart/form-data" method="POST"
        class="group p-4 w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
        @csrf
        @method('PUT')
        <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fill,minmax(225px,1fr))] gap-6 text-gray-700 md:px-3">
          <div class="col-span-full">
            <h4 class="mb-2 text-xl font-semibold">Roles disponibles</h4>
            <div class="max-h-60 flex flex-col overflow-x-auto">
              @foreach ($roles as $role)
                <label
                  class="px-3 py-1 mx-4 flex items-center gap-2 group-[&:not(.editable)]:has-[input:not(:checked)]:hidden pointer-events-none group-[.editable]:pointer-events-auto">
                  <input type="checkbox" name="roles[]" class="size-4 accent-purple-600" value="{{ $role->id }}">
                  <span class="ms-1">{{ $role->name }}</span>
                </label>
              @endforeach
            </div>
          </div>
          <button type="submit"
            class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
        </fieldset>
      </form>
      <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
        <button
          class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
      </form>
    </x-modals.simple>
  @endcan
@endsection
