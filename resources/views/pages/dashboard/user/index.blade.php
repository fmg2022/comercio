@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalSimple.js') }}" defer></script>
@endpush

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
              {{--               Solo para administrador principal
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
              </li> --}}
              <li>
                <button type="button" data-uid="{{ $user->id }}"
                  data-title="{{ 'Borrar al usuario ' . $user->fullName() }}"
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

  <x-modals.simple id="IDmodalSimple" class="max-w-md">
    <div class="flex flex-col items-center justify-center">
      <span class="my-6 text-slate-500">
        <svg xmlns="http://www.w3.org/2000/svg" width="112" height="112" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
        </svg>
      </span>
      <h2 class="mt-3 text-2xl text-center text-purple-900 font-semibold"></h2>
      <p class="px-2 py-4 mb-3 text-lg">¿Está seguro de que desea eliminarlo?</p>
    </div>
    <div class="flex justify-center gap-3 text-white">
      <form id="form-modalSimple" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-3 py-2 bg-red-900 rounded-md hover:bg-red-800 cursor-pointer">Eliminar</button>
      </form>
      <form method="dialog">
        <button class="px-3 py-2 bg-slate-700 rounded-md hover:bg-slate-600 cursor-pointer">Cancelar</button>
      </form>
    </div>
  </x-modals.simple>
@endsection
