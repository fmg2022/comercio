@extends('layouts.dashboard')

@section('content')
  <div class="relative flex divide-x divide-slate-700" x-data="{ openProfile: false, section: 'personal' }">
    <input type="checkbox" id="info-toggle" name="toggle-btns" class="hidden peer/aside">
    <aside
      class="fixed -left-full top-18.25 z-40 w-full h-full max-w-90 bg-slate-800 divide-y divide-slate-700 transition-all duration-500 ease-in-out [&>section]:px-5 [&>section]:py-6 lg:static lg:left-0 lg:h-auto lg:z-0 peer-checked/aside:left-0">
      <section class="relative flex gap-3 items-center">
        <div class="p-1 size-11 flex items-center justify-center bg-purple-600 rounded-full">
          AL
        </div>
        <div class="flex flex-col gap-1 w-max">
          <h5>{{ $user->fullName() }}</h5>
          <span class="text-xs">{{ $user->email }}</span>
        </div>
        <div class="relative ms-auto w-max">
          <a href="#!" class="peer inline-block p-3 rounded-full cursor-pointer bg-black/20">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M19 6h-1.586l-1-1c-.579-.579-1.595-1-2.414-1h-4c-.819 0-1.835.421-2.414 1l-1 1H5C3.346 6 2 7.346 2 9v8c0 1.654 1.346 3 3 3h14c1.654 0 3-1.346 3-3V9c0-1.654-1.346-3-3-3m-7 10a3.5 3.5 0 1 1 .001-7.001A3.5 3.5 0 0 1 12 16m6-4.701a1.3 1.3 0 1 1 0-2.6a1.3 1.3 0 0 1 0 2.6" />
            </svg>
          </a>
          <span
            class="absolute -top-8 -left-7 -z-30 w-max px-3 py-1 bg-slate-700 text-xs text-slate-400 rounded-lg opacity-0 peer-hover:z-20 peer-hover:opacity-100 transition-all duration-500">
            Cambiar Foto
          </span>
        </div>
      </section>
      <section>
        <h4 class="mb-2 font-semibold tracking-widest text-xs">ÚLTIMO INICIO</h4>
        <p class="text-slate-400 text-sm">{{ $user->getLastLogin() }}</p>
      </section>
      <section class="text-sm text-slate-300 [&>div]:py-5 [&>div.active]:text-purple-500">
        <div class="flex justify-between active">
          <div class="flex gap-3">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                  <path d="M12 13a3 3 0 1 0 0-6a3 3 0 0 0 0 6" />
                  <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9-9 9s-9-1.8-9-9s1.8-9 9-9" />
                  <path d="M6 20.05V20a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v.05" />
                </g>
              </svg>
            </span>
            <p>Información personal</p>
          </div>
          <span>
            <x-icons.arrow-right class="size-4" />
          </span>
        </div>
        <div class="flex justify-between">
          <div class="flex gap-3">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32">
                <path fill="currentColor"
                  d="M24.875 15.334v-4.876c0-4.894-3.98-8.875-8.875-8.875s-8.875 3.98-8.875 8.875v4.876H5.042v15.083h21.916V15.334zm-14.25-4.876c0-2.964 2.41-5.375 5.375-5.375s5.375 2.41 5.375 5.375v4.876h-10.75zm7.647 16.498h-4.545l1.222-3.667a2.37 2.37 0 0 1-1.325-2.12a2.375 2.375 0 1 1 4.75 0c0 .932-.542 1.73-1.324 2.12z" />
              </svg>
            </span>
            <p>Configuración de seguridad</p>
          </div>
          <span>
            <x-icons.arrow-right class="size-4" />
          </span>
        </div>
        <div class="flex justify-between">
          <div class="flex gap-3">
            <span>
              <x-icons.statistics class="size-4" />
            </span>
            <p>Actividad de la cuenta</p>
          </div>
          <span>
            <x-icons.arrow-right class="size-4" />
          </span>
        </div>
      </section>
    </aside>
    <div class="w-full px-8 py-7 bg-slate-800">
      <section class="mb-8 flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-white">Información Personal</h2>
          <p class="text-sm text-slate-400">Información básica, como nombre y dirección</p>
        </div>
        <div class="flex flex-wrap gap-x-5 gap-y-2 items-center justify-end">
          <button type="button" class="px-3 py-2 bg-green-800 rounded-md hover:bg-green-700 cursor-pointer"
            @click="openProfile = true; section = 'addresses'">
            Nueva dirección
          </button>
          <label for="info-toggle" class="text-slate-400 hover:text-slate-300 lg:hidden cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="M7.75 5.25h13.5M2.75 12h18.5m-10 6.75h10" />
            </svg>
          </label>
        </div>
      </section>
      <section>
        <h3 class="px-6 py-2 mb-1 text-sm font-semibold uppercase bg-slate-700/75 rounded">Datos
        </h3>
        <ul
          class="divide-y divide-slate-700 [&>li]:px-6 [&>li]:py-4 [&>li:hover_p:nth-child(1)]:text-slate-300 [&>li:hover>span]:text-white">
          <li class="flex justify-between text-slate-400">
            <div class="flex flex-col md:flex-row md:w-full">
              <p class="md:w-1/2">Nombre completo</p>
              <p class="text-wrap">{{ $user->fullName() }}</p>
            </div>
            <div class="flex items-center md:w-50 md:justify-end">
              <button type="button" @click="openProfile = true; section = 'personal'"
                class="px-3 py-1 cursor-pointer hover:text-white">
                <x-icons.arrow-right class="size-6" />
              </button>
            </div>
          </li>
          <li class="flex justify-between text-slate-400">
            <div class="flex flex-col md:flex-row md:w-full">
              <p class="md:w-1/2">Email</p>
              <p>{{ $user->email }}</p>
            </div>
            <div class="flex items-center md:w-50 md:justify-end">
              <button type="button" @click="openProfile = true; section = 'personal'"
                class="px-3 py-1 cursor-pointer hover:text-white">
                <x-icons.arrow-right class="size-6" />
              </button>
            </div>
          </li>
          <li class="flex justify-between text-slate-400">
            <div class="flex flex-col md:flex-row md:w-full">
              <p class="md:w-1/2">Teléfono</p>
              <p>{{ $user->phone }}</p>
            </div>
            <div class="flex items-center md:w-50 md:justify-end">
              <button type="button" @click="openProfile = true; section = 'personal'"
                class="px-3 py-1 cursor-pointer hover:text-white">
                <x-icons.arrow-right class="size-6" />
              </button>
            </div>
          </li>
          <li class="flex justify-between text-slate-400">
            <div class="flex flex-col items-center md:flex-row md:w-full">
              <p class="md:w-1/2">Dirección</p>
              <p class="text-wrap">{{ $address ? $address->street_1 : 'Sin dirección' }}</p>
            </div>
            <div class="flex items-center md:w-50 md:justify-end">
              <button type="button" class="px-3 py-1 cursor-pointer hover:text-white"
                @click="openProfile = true; section = 'address'">
                <x-icons.arrow-right class="size-6" />
              </button>
            </div>
          </li>
          <li class="flex justify-between text-slate-400">
            <div class="flex flex-col items-center md:flex-row md:w-full">
              <p class="md:w-1/2">Mis direcciones</p>
              <p class="text-wrap">Total de direcciones: <b class="me-2">{{ $user->addresses->count() }}</b></p>
            </div>
            <div class="flex items-center md:w-50 md:justify-end">
              <button type="button" class="px-3 py-1 cursor-pointer hover:text-white"
                @click="openProfile = true; section = 'addresses'">
                <x-icons.arrow-right class="size-6" />
              </button>
            </div>
          </li>
        </ul>

      </section>
    </div>

    <dialog
      class="top-1/2 left-1/2 -translate-1/2 max-w-2xl w-full backdrop:bg-purple-900/35 backdrop:backdrop-blur-sm rounded-lg overflow-hidden text-white bg-slate-800"
      x-ref="dialogPerf" x-effect="openProfile ? $refs.dialogPerf.showModal() : $refs.dialogPerf.close() "
      @click.self="openProfile = false" @keydown.escape.window="openProfile = false" closedby="any">
      <div class="relative flex flex-col items-center gap-4 p-4">
        <h2 class="text-2xl text-center text-purple-900 font-semibold">Actualizar Perfil</h2>
        <button type="button" @click="openProfile = false"
          class="absolute top-3 right-3 p-1 text-slate-500 cursor-pointer">
          <x-icons.x />
        </button>
        <div class="w-full px-7 py-2 text-white">
          <nav class="mb-6 flex border-b border-slate-700" aria-label="Secciones del perfil">
            <button type="button" @click="section = 'personal'"
              :class="section === 'personal' ?
                  'border-purple-500 text-purple-400' :
                  'border-transparent text-slate-400'"
              class="px-4 py-3 text-sm font-semibold border-b-2 cursor-pointer">
              Personal
            </button>
            <button type="button" @click="section = 'address'"
              :class="section === 'address' ?
                  'border-purple-500 text-purple-400' :
                  'border-transparent text-slate-400'"
              class="px-4 py-3 text-sm font-semibold border-b-2 cursor-pointer">
              Dirección
            </button>
            <button type="button" @click="section = 'addresses'"
              :class="section === 'addresses' ?
                  'border-purple-500 text-purple-400' :
                  'border-transparent text-slate-400'"
              class="px-4 py-3 text-sm font-semibold border-b-2 cursor-pointer">
              Mis direcciones
            </button>
          </nav>
          <div
            class="relative top-0 w-[210%] min-h-0 max-h-full grid grid-cols-2 gap-x-[5%] transition-[left] duration-500"
            :class="section === 'addresses' ? 'left-[-110%]' : 'left-0'">
            <form class="max-h-[64vh] flex flex-col" action="{{ route('profile.update', $user->id) }}" method="POST">
              @csrf
              @method('PATCH')
              <section
                class="mb-4 grow overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-thumb-slate-300/50 scrollbar-track-transparent">
                <div
                  class="relative top-0 w-[210%] min-h-0 max-h-full mb-10 me-2 grid grid-cols-2 gap-x-[5%] transition-[left] duration-500"
                  :class="section === 'address' ?
                      'left-[-110%]' :
                      'left-0'">
                  <fieldset class="px-2 mb-auto grid grid-cols-[repeat(auto-fit,minmax(190px,1fr))] gap-6">
                    <legend class="w-full px-6 py-2 mb-3 text-sm font-semibold uppercase bg-slate-700/75 rounded">
                      Información personal</legend>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-name" class="text-sm font-semibold">Nombre</label>
                      <input value="{{ $user->name }}" id="pf-name" name="name" autocomplete="name"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-surname" class="text-sm font-semibold">Apellido</label>
                      <input value="{{ $user->surname }}" id="pf-surname" name="surname" autocomplete="family-name"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50">
                    </div>
                    <div class="col-span-full flex flex-col gap-y-2">
                      <label for="pf-email" class="text-sm font-semibold">Email</label>
                      <input value="{{ $user->email }}" id="pf-email" type="email" name="email"
                        autocomplete="email"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-dni" class="text-sm font-semibold">DNI</label>
                      <input value="{{ $user->dni }}" id="pf-dni" name="dni" autocomplete="off"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-phone" class="text-sm font-semibold">Telefono</label>
                      <input value="{{ $user->phone }}" id="pf-phone" name="phone" autocomplete="tel"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50">
                    </div>
                  </fieldset>
                  <fieldset class="px-2 grid grid-cols-[repeat(auto-fit,minmax(190px,1fr))] gap-6">
                    <legend class="w-full px-6 py-2 mb-3 text-sm font-semibold uppercase bg-slate-700/75 rounded">
                      Dirección establecida por defecto</legend>
                    <div class="col-span-full flex flex-col gap-y-2">
                      <label for="pf-dir_name" class="text-sm font-semibold">Nombre</label>
                      <input value="{{ $address?->name }}" id="pf-dir_name" name="dir_name"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="col-span-full flex flex-col gap-y-2">
                      <label for="pf-dir1" class="text-sm font-semibold">Calle 1</label>
                      <input value="{{ $address?->street_1 }}" id="pf-dir1" name="street_1"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="col-span-full flex flex-col gap-y-2">
                      <label for="pf-dir2" class="text-sm font-semibold">Calle 2</label>
                      <input value="{{ $address?->street_2 }}" id="pf-dir2" name="street_2"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-prov" class="text-sm font-semibold">Provincia</label>
                      <input value="{{ $address?->province }}" id="pf-prov" name="province"
                        autocomplete="address-level1"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-locality" class="text-sm font-semibold">Ciudad</label>
                      <input value="{{ $address?->locality }}" id="pf-locality" name="locality"
                        autocomplete="address-level2"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-postalCode" class="text-sm font-semibold">Código Postal</label>
                      <input value="{{ $address?->postal_code }}" id="pf-postalCode" name="postal_code"
                        autocomplete="postal-code"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                  </fieldset>
                </div>
              </section>
              <div class="flex justify-center gap-4 text-white md:gap-6">
                <button type="submit"
                  class="px-3 py-2 bg-green-800 rounded-md text-lg hover:bg-green-700 cursor-pointer">Guardar</button>
                <button type="button" @click="openProfile = false"
                  class="px-3 py-2 bg-red-800 rounded-md text-lg hover:bg-red-700 cursor-pointer">Cancelar</button>
              </div>
            </form>
            <section>
              <h3 class="px-6 py-2 mb-3 text-sm font-semibold uppercase bg-slate-700/75 rounded">
                {{ $user->addresses->isEmpty() ? 'Sin' : '' }} Direcciones Disponibles</h3>
              @if (!$user->addresses->isEmpty())
                <form action="{{ route('profile.addresses.updateDefault') }}" method="POST"
                  class="mb-7 flex flex-wrap items-center justify-center gap-y-2 gap-x-5">
                  @csrf
                  @method('PATCH')
                  <select name="address[]"
                    class="grow px-4 py-2.5 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50 [&>option]:text-black"
                    @disabled($user->addresses->isEmpty()) required>
                    @forelse ($user->addresses as $addressItem)
                      <option value="{{ $addressItem->id }}" @selected($addressItem->id === $address->id)>
                        {{ $addressItem->street_1 }}</option>
                    @empty
                      <option value="" disabled selected>Sin direcciones</option>
                    @endforelse
                  </select>
                  <button type="submit"
                    class="px-3 py-2 bg-green-800 rounded-md text-lg hover:bg-green-700 cursor-pointer">Seleccionar</button>
                </form>
              @endif
              <h3 class="px-6 py-2 mb-3 text-sm font-semibold uppercase bg-slate-700/75 rounded">
                Nueva dirección</h3>
              <form action="{{ route('profile.addresses.store') }}" method="POST"
                class="flex flex-wrap items-center gap-y-2 gap-x-5">
                @csrf
                <fieldset class="grow max-h-[44dvh] flex">
                  <section
                    class="grow flex flex-col gap-6 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300/50 scrollbar-track-transparent">
                    <div class="flex flex-col gap-y-2">
                      <label for="dir_name" class="text-sm font-semibold">Nombre</label>
                      <input id="dir_name" name="name" autocomplete="off"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="dir_street1" class="text-sm font-semibold">Calle 1</label>
                      <input id="dir_street1" name="street_1"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="dir_street2" class="text-sm font-semibold">Calle 2</label>
                      <input id="dir_street2" name="street_2"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="dir_prov" class="text-sm font-semibold">Provincia</label>
                      <input id="dir_prov" name="province" autocomplete="address-level1"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="dir_locality" class="text-sm font-semibold">Ciudad</label>
                      <input id="dir_locality" name="locality" autocomplete="address-level2"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="dir_postalCode" class="text-sm font-semibold">Código Postal</label>
                      <input id="dir_postalCode" name="postal_code" autocomplete="postal-code"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-400/50"
                        required>
                    </div>
                  </section>
                </fieldset>
                <button type="submit"
                  class="px-3 py-2 bg-green-800 rounded-md text-lg hover:bg-green-700 cursor-pointer">Crear</button>
              </form>
            </section>
          </div>
        </div>
      </div>
    </dialog>
  </div>
@endsection
