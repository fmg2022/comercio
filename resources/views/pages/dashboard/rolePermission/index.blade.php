@extends('layouts.dashboard')

@pushIf(auth()->user()?->can('view_any_roles'), 'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalSEC.js') }}" defer></script>
@can('delete_roles')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
@endcan
@endPushIf

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Roles y Permisos</x-slot:textTitle>

    @can('create_roles')
      <button type="button" data-type="create" data-modalID="roleCES"
        class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
        <x-icons.plus class="size-6" />
        Nuevo Rol
      </button>
    @endcan
  </x-sections.headerTitle>

  <div class="flex justify-around items-start">
    <div class="w-full max-w-lg" data-objetive="permissionContainer">
      <h2 class="py-2 px-4 text-xl font-semibold text-gray-300 bg-slate-800 rounded-t-xl">Roles</h2>
      <x-tables.table>
        <x-slot:thead>
          <tr class="text-left">
            <th>#</th>
            <th>Nombre</th>
            <th class="text-right">Opciones</th>
          </tr>
        </x-slot:head>
        <x-slot:tbody>
          @forelse ($roles as $index => $role)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td class="font-bold">{{ $role->display_name }}</td>
              <td>
                @if ($role->name !== 'super_admin')
                  <div class="relative flex justify-end">
                    <x-popups.contentWcheck iid="chrole-{{ $role->id }}" labelClass="hover:bg-slate-900"
                      class="right-12 -top-1/4">
                      <x-slot:label>
                        <x-icons.threeDotsX class="size-6" />
                      </x-slot:label>

                      <ul
                        class="w-max py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                        <li>
                          <button type="button" data-type="show" data-uid="{{ $role->id }}"
                            data-path="{{ $role->id }}" data-modalID="roleCES"
                            class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                            <span>
                              <x-icons.edit class="size-5" />
                            </span>
                            Ver Rol
                          </button>
                        </li>
                        @can('update_roles')
                          <li>
                            <button type="button" data-type="edit" data-uid="{{ $role->id }}"
                              data-path="{{ $role->id }}" data-modalID="roleCES"
                              class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                              <span>
                                <x-icons.edit class="size-5" />
                              </span>
                              Editar Rol
                            </button>
                          </li>
                          {{-- <li>
                          <button type="button" data-type="edit" data-uid="{{ $role->id }}"
                            data-path="{{ $role->id }}/assign" data-modalID="roleuserCES"
                            class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                            <span>
                              <x-icons.users class="size-5" />
                            </span>
                            Aignar Rol
                          </button>
                        </li> --}}
                          <li>
                            <button type="button" data-text="Rol: '{{ $role->name }}'" data-uid="{{ $role->id }}"
                              data-modalID="rolDelete" data-path="{{ $role->id }}" data-delete="true"
                              class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                              <span>
                                <x-icons.trash class="size-5" />
                              </span>
                              Eliminar Rol
                            </button>
                          </li>
                        @endcan
                      </ul>
                    </x-popups.contentWcheck>
                  </div>
                @else
                  <p class="px-2 text-end">---</p>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td class="text-center font-semibold text-slate-300 col-span-full">No hay roles registrados</td>
            </tr>
          @endforelse
        </x-slot:tbody>
      </x-tables.table>
    </div>
    <div data-target="permissionContainer"
      class="relative min-h-80 overflow-y-auto rounded-xl lg:max-w-7xl [scrollbar-color:#62748e_transparent] scrollbar-thin">
      <h2 class="sticky top-0 py-2 px-4 text-xl font-semibold text-gray-300 bg-slate-800 border-b-4 border-slate-900">
        Permisos</h2>
      <ul class="px-1 py-2 bg-slate-700 divide-y-4 divide-slate-800 text-gray-200">
        @forelse ($permissions as $permission)
          <li class="px-4 py-3 font-semibold capitalize">{{ $permission->display_name }}</li>
        @empty
          <li>
            <h3 class="font-semibold">Sin Permisos registrados</h3>
          </li>
        @endforelse
      </ul>
    </div>
  </div>

  @can('view_any_roles')
    {{-- MODAL SHOW, EDIT --}}
    <x-modals.simple id="roleCES"
      class="max-w-lg w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbat-thin">
      <form enctype="multipart/form-data" method="POST"
        class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
        @can('update_roles')
          @csrf
          @method('PUT')
        @endcan
        <fieldset class="py-3 grid grid-cols-1 gap-6 text-gray-700 md:px-3">
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label class="block mb-2 font-semibold" for="name">Nombre</label>
            <input type="text" id="name" name="display_name" autocomplete="off"
              class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
          </div>
          <div>
            <h4 class="mb-2 text-xl font-semibold">Permisos</h4>
            <div class="max-h-60 flex flex-col overflow-x-auto">
              @foreach ($permissions as $permission)
                <label
                  class="px-3 py-1 mx-4 flex items-center gap-2 group-[&:not(.editable)]:has-[input:not(:checked)]:hidden pointer-events-none group-[.editable]:pointer-events-auto">
                  <input type="checkbox" name="permissions_ids[]" class="size-4 accent-purple-600"
                    value="{{ $permission->id }}">
                  <span class="ms-1">{{ $permission->display_name }}</span>
                </label>
              @endforeach
            </div>
          </div>
          <button type="submit"
            class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
        </fieldset>
      </form>
      <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
        <button class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
      </form>
    </x-modals.simple>

    <x-modals.simple id="roleuserCES"
      class="max-w-lg w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbat-thin">
      <form enctype="multipart/form-data" method="POST"
        class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
        @csrf
        @method('PUT')
        <fieldset class="py-3 grid grid-cols-1 gap-6 text-gray-700 md:px-3">
          <div class="pointer-events-none group-[.editable]:pointer-events-auto">
            <label for="user_id" class="block ps-4 mb-2 font-semibold">Usuarios a asignar</label>
            <select name="user_id" id="user_id"
              class="w-full px-3 py-2 text-gray-900 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
              required>
              <option value="" class="bg-slate-200 disabled:text-black" disabled selected>Selecciona un usuario
              </option>
              @foreach ($users as $user)
                <option value="{{ $user->id }}" @class(['text-slate-800', 'bg-gray-100' => $user->trashed()])>
                  {{ $user->surname . ', ' . $user->name }}
                </option>
              @endforeach
            </select>
          </div>
          <button type="submit"
            class="absolute bottom-4 right-2/3 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-3/5">Actualizar</button>
        </fieldset>
      </form>
      <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-2/3 sm:left-3/5">
        <button class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
      </form>
    </x-modals.simple>
  @endcan
  @can('delete_roles')
    {{-- MODAL DELETE, RESTORE --}}
    <x-modals.delete id="rolDelete" />
  @endcan
@endsection
