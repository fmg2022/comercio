@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/modalStateType.js') }}" defer></script>
@endPush

@section('content')
  <x-sections.headerTitle class="flex justify-between items-center">
    <x-slot:textTitle>Estados y Tipos de Secciones</x-slot:textTitle>

    <button type="button" data-type="create" data-modalID="stateTypeCSE" data-path=""
      class="px-4 py-2 flex items-center gap-2 rounded-md cursor-pointer bg-purple-600 active:bg-purple-700 button-create-edit-show">
      <x-icons.plus class="size-6" />
      Nuevo Estado
    </button>
  </x-sections.headerTitle>

  <div
    class="relative max-h-[calc(100vh-16rem)] overflow-y-auto lg:max-w-7xl lg:mx-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left [&>th]:sticky [&>th]:top-0 [&>th]:bg-slate-800">
          <th>#</th>
          <th>Código</th>
          <th>Descripción</th>
          <th class="z-10 text-end">Opciones</th>
        </tr>
      </x-slot:head>
      <x-slot:tbody>
        <tr>
          <td colspan="4" class="bg-slate-700 text-center text-2xl font-bold">Ofertas: ESTADOS</td>
        </tr>
        @forelse ($offerStates as $index => $state)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td class="font-bold">{{ $state->code }}</td>
            <td class="text-slate-300">{{ $state->description }}</td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chofferState-{{ $state->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $state->id }}"
                        data-path="offer-states/{{ $state->id }}" data-modalID="stateTypeCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar Estado
                      </button>
                    </li>
                    <li>
                      <button type="button" data-text="Estado: '{{ $state->code }}'" data-uid="{{ $state->id }}"
                        data-modalID="stateTypeDelete" data-path="offer-states/{{ $state->id }}" data-delete="true"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          <x-icons.trash class="size-5" />
                        </span>
                        Eliminar Estado
                      </button>
                    </li>
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="text-center font-semibold text-slate-300 col-span-full">No hay estados de ofertas registradas</td>
          </tr>
        @endforelse

        <tr>
          <td colspan="4" class="bg-slate-700 text-center text-2xl font-bold">Ofertas: TIPOS</td>
        </tr>
        @forelse ($offerTypes as $index => $type)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td class="font-bold">{{ $type->code }}</td>
            <td class="text-slate-300">{{ $type->description }}</td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chofferType-{{ $type->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $type->id }}"
                        data-path="offer-types/{{ $type->id }}" data-modalID="stateTypeCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar Tipo
                      </button>
                    </li>
                    <li>
                      <button type="button" data-text="Tipo: '{{ $type->code }}'" data-uid="{{ $type->id }}"
                        data-modalID="stateTypeDelete" data-path="offer-types/{{ $type->id }}" data-delete="true"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          <x-icons.trash class="size-5" />
                        </span>
                        Eliminar Tipo
                      </button>
                    </li>
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="text-center font-semibold text-slate-300 col-span-full">No hay tipos de ofertas registradas</td>
          </tr>
        @endforelse

        <tr>
          <td colspan="4" class="bg-slate-700 text-center text-2xl font-bold">Ordenes: ESTADOS</td>
        </tr>
        @forelse ($orderStates as $index => $state)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td class="font-bold">{{ $state->code }}</td>
            <td class="text-slate-300">{{ $state->description }}</td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chorderState-{{ $state->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $state->id }}"
                        data-path="order-states/{{ $state->id }}" data-modalID="stateTypeCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar Estado
                      </button>
                    </li>
                    <li>
                      <button type="button" data-text="Estado: '{{ $state->code }}'" data-uid="{{ $state->id }}"
                        data-modalID="stateTypeDelete" data-path="order-states/{{ $state->id }}" data-delete="true"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          <x-icons.trash class="size-5" />
                        </span>
                        Eliminar Estado
                      </button>
                    </li>
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="text-center font-semibold text-slate-300 col-span-full">No hay estados de ordenes registradas</td>
          </tr>
        @endforelse

        <tr>
          <td colspan="4" class="bg-slate-700 text-center text-2xl font-bold">Pagos: ESTADOS</td>
        </tr>
        @forelse ($paymentStates as $index => $state)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td class="font-bold">{{ $state->code }}</td>
            <td class="text-slate-300">{{ $state->description }}</td>
            <td>
              <div class="relative flex justify-end">
                <x-popups.contentWcheck iid="chpaymentState-{{ $state->id }}" labelClass="hover:bg-slate-900"
                  class="right-12 -top-1/4">
                  <x-slot:label>
                    <x-icons.threeDotsX class="size-6" />
                  </x-slot:label>

                  <ul
                    class="w-48 py-2 bg-slate-800 border border-slate-700 rounded-md text-xs text-slate-300 font-semibold">
                    <li>
                      <button type="button" data-type="edit" data-uid="{{ $state->id }}"
                        data-path="payment-states/{{ $state->id }}" data-modalID="stateTypeCSE"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-create-edit-show">
                        <span>
                          <x-icons.edit class="size-5" />
                        </span>
                        Editar Estado
                      </button>
                    </li>
                    <li>
                      <button type="button" data-text="Estado: '{{ $state->code }}'" data-uid="{{ $state->id }}"
                        data-modalID="stateTypeDelete" data-path="payment-states/{{ $state->id }}"
                        data-delete="true"
                        class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
                        <span>
                          <x-icons.trash class="size-5" />
                        </span>
                        Eliminar Estado
                      </button>
                    </li>
                  </ul>
                </x-popups.contentWcheck>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="text-center font-semibold text-slate-300 col-span-full">No hay estados de pagos registradas</td>
          </tr>
        @endforelse
      </x-slot:tbody>
    </x-tables.table>
  </div>

  {{-- MODAL CREATE, EDIT --}}
  <x-modals.simple id="stateTypeCSE"
    class="max-w-xl w-full max-h-[90%] overflow-y-auto [scrollbar-color:#62748e_transparent] scrollbar-thin">
    <div class="mt-8">
      <label class="block mb-2 font-semibold text-gray-700" for="modelTypeid">Seleccione uno de los modelos</label>
      <select name="modelType" id="modelTypeid"
        class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
        required>
        <option value="order-states" selected>Estados de Ordenes</option>
        <option value="offer-states">Estados de Ofertas</option>
        <option value="offer-types">Tipos de Ofertas</option>
        <option value="payment-states">Estados de Pagos</option>
      </select>
    </div>
    <form enctype="multipart/form-data" method="POST"
      class="group w-full flex flex-col gap-4 items-center justify-center editable [&.editable]:mb-12 peer/form">
      @csrf
      @method('PUT')
      <fieldset class="py-3 grid grid-cols-[repeat(auto-fill,minmax(250px,1fr))] gap-6 text-gray-700 md:px-3">
        <div class="pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="code">Código</label>
          <input type="text" id="code" name="code" autocomplete="off"
            class="w-full px-3 py-2 text-gray-900 text-base bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
            required>
        </div>
        <div class="col-span-full pointer-events-none group-[.editable]:pointer-events-auto">
          <label class="block mb-2 font-semibold" for="description">Descripción</label>
          <textarea id="description" name="description"
            class="w-full max-w-lg min-h-lh px-3 py-2 text-gray-900 text-base resize-none overflow-hidden bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 field-sizing-content"></textarea>
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

  <x-modals.delete id="stateTypeDelete" />
@endsection
