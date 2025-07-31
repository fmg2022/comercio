@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/userModalMix.js') }}" defer></script>
@endpush

@php
  $type1 = 'modal-delete-restore';
@endphp

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Usuarios</x-slot:textTitle>

    <x-buttons.linkFill href="{{ route('users.create') }}"
      class="flex items-center gap-2 bg-purple-600 active:bg-purple-700">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M18 10h-4V6a2 2 0 0 0-4 0l.071 4H6a2 2 0 0 0 0 4l4.071-.071L10 18a2 2 0 0 0 4 0v-4.071L18 14a2 2 0 0 0 0-4" />
      </svg>
      Nuevo
    </x-buttons.linkFill>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Foto</th>
        <th>Nombre completo</th>
        <th>Correo</th>
        <th>Telefono</th>
        <th class="text-end">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($users as $index => $user)
      <tr>
        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
        <td>
          <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->fullName() }}" class="h-12 aspect-auto">
        </td>
        <td class="relative">
          <x-buttons.linkSimple href="{{ route('users.show', $user->id) }}"
            class="text-slate-100 hover:text-purple-500 peer/popup">
            {{ $user->fullName() }}
          </x-buttons.linkSimple>
          <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 peer-hover/popup:inline-block">
            Ver Usuario
          </x-popups.text>
        </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
        <td class="relative flex justify-end">
          <input type="checkbox" id="chuser-{{ $user->id }}" class="hidden peer/checkOption" name="toggle-btns">
          <label for="chuser-{{ $user->id }}"
            class="inline-block p-1.5 hover:bg-slate-900 rounded-full cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
            </svg>
          </label>
          <div class="absolute right-14 z-30 hidden peer-checked/checkOption:block">
            <ul
              class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:cursor-pointer [&>li]:transition-colors">
              <li>
                {{-- Crear el modal y .js --}}
                <button type="button" class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700"
                  data-show="true" data-id="{{ $user->id }}">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                      <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2">
                        <path
                          d="M3.587 13.779c1.78 1.769 4.883 4.22 8.413 4.22s6.634-2.451 8.413-4.22c.47-.467.705-.7.854-1.159c.107-.327.107-.913 0-1.24c-.15-.458-.385-.692-.854-1.159C18.633 8.452 15.531 6 12 6c-3.53 0-6.634 2.452-8.413 4.221c-.47.467-.705.7-.854 1.159c-.107.327-.107.913 0 1.24c.15.458.384.692.854 1.159" />
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                      </g>
                    </svg>
                  </span>
                  Ver Usuario
                </button>
              </li>
              <li>
                <button type="button" class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700"
                  data-show="false" data-id="{{ $user->id }}">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                      <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2">
                        <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                      </g>
                    </svg>
                  </span>
                  Editar Usuario
                </button>
              </li>
              <li>
                <button type="button" data-uid="{{ $user->id }}" data-modal="{{ $type1 }}"
                  data-title="{{ $user->fullName() }}" data-button="Eliminar"
                  data-text="¿Está seguro de que desea eliminar al usuario?"
                  class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 ">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M20 8.7H4a.75.75 0 1 1 0-1.5h16a.75.75 0 0 1 0 1.5" />
                      <path fill="currentColor"
                        d="M16.44 20.75H7.56A2.4 2.4 0 0 1 5 18.49V8a.75.75 0 0 1 1.5 0v10.49c0 .41.47.76 1 .76h8.88c.56 0 1-.35 1-.76V8A.75.75 0 1 1 19 8v10.49a2.4 2.4 0 0 1-2.56 2.26m.12-13a.74.74 0 0 1-.75-.75V5.51c0-.41-.48-.76-1-.76H9.22c-.55 0-1 .35-1 .76V7a.75.75 0 1 1-1.5 0V5.51a2.41 2.41 0 0 1 2.5-2.26h5.56a2.41 2.41 0 0 1 2.53 2.26V7a.75.75 0 0 1-.75.76Z" />
                      <path fill="currentColor"
                        d="M10.22 17a.76.76 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.75.75 0 0 1-.75.76m3.56 0a.75.75 0 0 1-.75-.75v-4.53a.75.75 0 0 1 1.5 0v4.52a.76.76 0 0 1-.75.76" />
                    </svg>
                  </span>
                  Eliminar Usuario
                </button>
              </li>
            </ul>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="6" class="text-center font-semibold text-slate-300">Sin usuarios registrados</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $users->links('pages.dashboard.partials.pagination') }}

  @if ($usersDeleted->count() > 0)
    <section class="mt-10">
      <h2 class="mb-5 px-4 text-2xl font-semibold text-gray-300">Usuarios Eliminados</h2>
      <x-tables.table>
        <x-slot:thead>
          <tr class="text-left">
            <th>#</th>
            <th>Foto</th>
            <th>Nombre completo</th>
            <th>Correo</th>
            <th>Telefono</th>
            <th class="text-end">Opciones</th>
          </tr>
        </x-slot>

        @foreach ($usersDeleted as $index => $user)
          <tr>
            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
            <td>
              <img src="{{ asset('images/users/' . $user->image) }}" alt="{{ $user->fullName() }}"
                class="h-12 aspect-auto">
            </td>
            <td class="relative">
              <x-buttons.linkSimple href="{{ route('users.show', $user->id) }}"
                class="text-slate-100 hover:text-purple-500 peer/popup">
                {{ $user->fullName() }}
              </x-buttons.linkSimple>
              <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 peer-hover/popup:inline-block">
                Ver Usuario
              </x-popups.text>
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td class="relative flex justify-end">
              <x-popups.contentWcheck iid="chuser-{{ $user->id }}" labelClass="hover:bg-slate-900" class="right-12">
                <x-slot:label>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 12a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m7 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                  </svg>
                </x-slot:label>
                <ul
                  class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold [&>li]:bg-slate-800 [&>li]:cursor-pointer [&>li]:transition-colors">
                  <li>
                    {{-- Crear el modal y .js --}}
                    <button type="button" class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700"
                      data-id="{{ $user->id }}" data-show="true">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                          <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path
                              d="M3.587 13.779c1.78 1.769 4.883 4.22 8.413 4.22s6.634-2.451 8.413-4.22c.47-.467.705-.7.854-1.159c.107-.327.107-.913 0-1.24c-.15-.458-.385-.692-.854-1.159C18.633 8.452 15.531 6 12 6c-3.53 0-6.634 2.452-8.413 4.221c-.47.467-.705.7-.854 1.159c-.107.327-.107.913 0 1.24c.15.458.384.692.854 1.159" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                          </g>
                        </svg>
                      </span>
                      Ver Usuario
                    </button>
                  </li>
                  <li>
                    <button type="button" data-uid="{{ $user->id }}" data-modal="{{ $type1 }}"
                      data-title="{{ $user->fullName() }}" data-button="Restaurar"
                      data-text="¿Está seguro de que desea restaurar al usuario?"
                      class="w-full px-4 py-2.5 flex gap-3 cursor-pointer hover:bg-slate-700 ">
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                          <path fill="currentColor" fill-rule="evenodd"
                            d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                            clip-rule="evenodd" />
                        </svg>
                      </span>
                      Restaurar Usuario
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </td>
          </tr>
        @endforeach
      </x-tables.table>

      {{ $usersDeleted->onEachSide(5)->links('pages.dashboard.partials.pagination') }}
    </section>
  @else
    <h3 class="mb-3 mt-7 text-center text-xl font-semibold">Sin usuarios eliminados</h3>
  @endif

  {{-- MODAL SHOW, EDIT --}}
  <x-modals.simple id="modal-user-mix"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] [scrollbar-width:thin]">
    <form id="form-user-mix" enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')
      <x-images.borderFill src="{{ asset('images/users') }}/sin_foto.webp"
        alt="Foto de usuario {{ $user->name }}" />

      <fieldset class="w-full py-3 grid grid-cols-[repeat(auto-fill,minmax(250px,1fr))] gap-2 text-gray-700 md:px-3">
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="name"></label>
          <input type="text" id="name" name="name" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="surname"></label>
          <input type="text" id="surname" name="surname" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="email"></label>
          <input type="email" id="email" name="email" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-semibold" for="phone"></label>
          <input type="text" id="phone" name="phone" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <button type="submit"
          class="absolute bottom-4 right-1/12 px-3 py-2 hidden group-[.editable]:block bg-purple-900 text-lg text-white rounded-md hover:bg-purple-800 cursor-pointer sm:right-1/5">Actualizar</button>
      </fieldset>
    </form>
    <form method="dialog" class="peer-[.editable]/form:block hidden absolute bottom-4 left-1/12 sm:left-1/5">
      <button
        class="px-3 py-2 bg-red-700 text-lg text-white rounded-md hover:bg-red-600 cursor-pointer">Cancelar</button>
    </form>
  </x-modals.simple>

  {{-- MODAL DELETE, RESTORE --}}
  <x-modals.delete id="{{ $type1 }}" class="max-w-md" iconClass="text-slate-500">
    <x-slot:icon>
      <svg xmlns="http://www.w3.org/2000/svg" width="112" height="112" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
      </svg>
    </x-slot:icon>
  </x-modals.delete>
@endsection
