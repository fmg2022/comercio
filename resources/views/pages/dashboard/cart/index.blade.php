@extends('layouts.dashboard')

@pushIf(auth()->check() && auth()->user()?->can('manage carts-details'), 'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
@endpushIf

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Lista de Carritos</x-slot:textTitle>
  </x-sections.headerTitle>

  <x-tables.table>
    <x-slot:thead>
      <tr class="text-left">
        <th>#</th>
        <th>Usuario</th>
        <th>Cantidad Productos</th>
        <th>Última Modificación</th>
        <th class="text-right">Opciones</th>
      </tr>
    </x-slot>

    @forelse ($carts as $index => $cart)
      <tr>
        <td>{{ ($carts->currentPage() - 1) * $carts->perPage() + $index + 1 }}</td>
        <td class="relative">
          <x-buttons.link href="{{ route('users.show', $cart->user_id) }}"
            class="text-slate-100 hover:text-purple-500 peer/popup">
            {{ $cart->fullName }}
          </x-buttons.link>
          <x-popups.text class="top-3/4 left-12 hidden bg-purple-800/80 peer-hover/popup:inline-block">
            Ver Usuario
          </x-popups.text>
        </td>
        <td>{{ $cart->products_count }}</td>
        <td>{{ $cart->updated_at }}</td>
        <td>
          @can('manage carts-details')
            <div class="relative flex justify-end">
              <x-popups.contentWcheck iid="chcart-{{ $cart->id }}" labelClass="hover:bg-slate-900"
                class="right-12 -top-1/4">
                <x-slot:label>
                  <x-icons.threeDotsX class="size-6" />
                </x-slot:label>

                <ul class="w-max py-2 bg-slate-800 text-slate-300 border border-slate-700 rounded-md font-semibold">
                  <li>
                    <a href="{{ route('carts.show', $cart->id) }}"
                      class="px-4 py-2.5 flex items-center gap-3 hover:bg-slate-700">
                      <span>
                        <x-icons.show class="size-5" />
                      </span>
                      Detalles
                    </a>
                  </li>
                  <li>
                    <button type="button" data-text="Carrito de '{{ $cart->fullName }}'" data-uid="{{ $cart->id }}"
                      data-modalID="cartDelete" data-path="{{ $cart->id }}/clear" data-delete="true"
                      class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                      <span>
                        <x-icons.trash class="size-5" />
                      </span>
                      Vaciar Carrito
                    </button>
                  </li>
                </ul>
              </x-popups.contentWcheck>
            </div>
          @else
            <p class="px-2 text-end">---</p>
          @endcan
        </td>
      </tr>
    @empty
      <tr>
        <td class="text-center font-semibold text-slate-300 col-span-full">No hay carritos registrados</td>
      </tr>
    @endforelse
  </x-tables.table>

  @can('manage carts-details')
    {{-- MODAL DELETE, RESTORE --}}
    <x-modals.delete id="cartDelete" />
  @endcan
@endsection
