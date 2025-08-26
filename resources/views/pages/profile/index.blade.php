@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="{{ asset('js/dashboard/modal.js') }}" defer></script>
  <script src="{{ asset('js/dashboard/navDesplace.js') }}" defer></script>
@endpush

@section('content')
  <div class="relative flex divide-x divide-slate-700">
    <input type="checkbox" id="info-toggle" class="hidden peer/aside">
    <div
      class="overlay fixed -left-full top-[73px] z-15 w-full h-full bg-slate-950/60 opacity-0 peer-checked/aside:opacity-100 peer-checked/aside:left-0 lg:hidden">
    </div>
    <aside
      class="fixed -left-full top-[73px] z-40 w-full h-full max-w-[300px] dark:bg-slate-800 divide-y divide-slate-700 transition-all duration-500 ease-in-out [&>section]:px-5 [&>section]:p-6 lg:static lg:left-0 lg:h-auto lg:z-0 peer-checked/aside:left-0">
      <section class="relative flex gap-3 items-center">
        <div class="p-2 size-11 flex items-center justify-center bg-purple-600 rounded-full">
          <span class="font-bold">AL</span>
        </div>
        <div class="flex flex-col gap-1 w-max">
          <h5 class="">Nombre completo</h5>
          <span class="text-xs">correo@mail.com</span>
        </div>
        <div class="relative ms-auto w-max">
          <a href="#!" class="peer inline-block p-3 rounded-full cursor-pointer bg-black/20">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M19 6h-1.586l-1-1c-.579-.579-1.595-1-2.414-1h-4c-.819 0-1.835.421-2.414 1l-1 1H5C3.346 6 2 7.346 2 9v8c0 1.654 1.346 3 3 3h14c1.654 0 3-1.346 3-3V9c0-1.654-1.346-3-3-3m-7 10a3.5 3.5 0 1 1 .001-7.001A3.5 3.5 0 0 1 12 16m6-4.701a1.3 1.3 0 1 1 0-2.6a1.3 1.3 0 0 1 0 2.6" />
            </svg>
          </a>
          <span
            class="absolute -top-8 -left-7 -z-30 w-max px-3 py-1 bg-slate-300 dark:bg-slate-700 text-xs text-slate-700 dark:text-slate-400 rounded-lg opacity-0 peer-hover:z-20 peer-hover:opacity-100 transition-all duration-500">
            Cambiar Foto
          </span>
        </div>
      </section>
      <section>
        <h4 class="mb-2 font-semibold tracking-widest text-xs">ÚLTIMO INICIO</h4>
        <p class="text-slate-400 text-sm">2023-01-01 03:14am</p>
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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="m9 5l6 7l-6 7" />
            </svg>
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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="m9 5l6 7l-6 7" />
            </svg>
          </span>
        </div>
        <div class="flex justify-between">
          <div class="flex gap-3">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  color="currentColor">
                  <path
                    d="M4.318 19.682C3 18.364 3 16.242 3 12s0-6.364 1.318-7.682S7.758 3 12 3s6.364 0 7.682 1.318S21 7.758 21 12s0 6.364-1.318 7.682S16.242 21 12 21s-6.364 0-7.682-1.318" />
                  <path d="M6 12h2.5l2-4l3 8l2-4H18" />
                </g>
              </svg>
            </span>
            <p>Actividad de la cuenta</p>
          </div>
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="m9 5l6 7l-6 7" />
            </svg>
          </span>
        </div>
      </section>
    </aside>
    <div class="w-full px-8 py-7 bg-teal-50 dark:bg-slate-800">
      <section class="mb-8 flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-white">Información Personal</h2>
          <p class="text-sm text-slate-400">Información básica, como nombre y dirección</p>
        </div>
        <label for="info-toggle" class="text-slate-400 cursor-pointer hover:text-slate-300 lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
              d="M7.75 5.25h13.5M2.75 12h18.5m-10 6.75h10" />
          </svg>
        </label>
      </section>
      <section>
        <h3 class="px-6 py-2 mb-1 text-sm font-semibold uppercase bg-slate-700/75 rounded">Datos
        </h3>
        <ul
          class="divide-y divide-slate-700 [&>li]:px-6 [&>li]:py-4 [&>li:hover_p:nth-child(1)]:text-slate-300 [&>li:hover>span]:text-white"
          data-listDialog="true">
          <li class="flex justify-between text-slate-400 cursor-pointer">
            <div class="flex flex-col md:flex-row md:w-full">
              <p class="md:w-1/2">Nombre completo</p>
              <p class="text-wrap">{{ $user->fullName() }}</p>
            </div>
            <div class="flex items-center md:w-[200px] md:justify-end">
              <button type="button" onclick="openModal('dialog-perf')" class="p-1 cursor-pointer hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5" d="m9 5l6 7l-6 7" />
                </svg>
              </button>
            </div>
          </li>
          <li class="flex justify-between text-slate-400">
            <div class="flex flex-col md:flex-row md:w-full">
              <p class="md:w-1/2">Email</p>
              <p>{{ $user->email }}</p>
            </div>
            <div class="flex items-center md:w-[200px] md:justify-end">
              <button type="button" onclick="openModal('dialog-perf')" class="p-1 cursor-pointer hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5" d="m9 5l6 7l-6 7" />
                </svg>
              </button>
            </div>
          </li>
          <li class="flex justify-between text-slate-400 cursor-pointer">
            <div class="flex flex-col md:flex-row md:w-full">
              <p class="md:w-1/2">Teléfono</p>
              <p>{{ $user->phone }}</p>
            </div>
            <div class="flex items-center md:w-[200px] md:justify-end">
              <button type="button" onclick="openModal('dialog-perf')" class="p-1 cursor-pointer hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5" d="m9 5l6 7l-6 7" />
                </svg>
              </button>
            </div>
          </li>
          <li class="flex justify-between text-slate-400">
            <div class="flex flex-col items-center md:flex-row md:w-full">
              <p class="md:w-1/2">Dirección</p>
              <p class="text-wrap">2337 Calle, Ciudad Provincia</p>
            </div>
            <div class="flex items-center md:w-[200px] md:justify-end">
              <button type="button" onclick="openModal('dialog-perf')" class="p-1 cursor-pointer hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5" d="m9 5l6 7l-6 7" />
                </svg>
              </button>
            </div>
          </li>
        </ul>

        <x-modals.simple title="Actualizar Perfil" titleClass="text-white" id="dialog-perf"
          class="max-w-2xl w-full bg-slate-800">
          <div class="w-full px-9 pb-8 pt-6 text-white">
            <nav
              class="mb-5 flex gap-3 border-b border-slate-600/75 text-slate-400 [&>button.active]:border-b-4 [&>button.active]:border-purple-700 [&>button.active]:text-purple-700"
              data-navbar="true">
              <button data-pos="0" class="active pb-3 px-2 hover:text-white">Personal</button>
              <button data-pos="1.1" class="pb-3 px-2 hover:text-white">Dirección</button>
            </nav>
            <form class="overflow-x-hidden">
              <div class="relative top-0 w-[210%] mb-10 grid grid-cols-2 gap-x-[5%] transition-[left] duration-500"
                data-navcontent="true" style="left: 0%">
                <fieldset class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-6">
                  <div class="flex flex-col gap-y-2">
                    <label for="pf-name" class="text-sm font-semibold">Nombre</label>
                    <input value="{{ $user->name }}" id="pf-name"
                      class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                  </div>
                  <div class="flex flex-col gap-y-2">
                    <label for="pf-surname" class="text-sm font-semibold">Apellido</label>
                    <input value="{{ $user->surname }}" id="pf-surname"
                      class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                  </div>
                  <div class="flex flex-col gap-y-2">
                    <label for="pf-phone" class="text-sm font-semibold">Telefono</label>
                    <input value="{{ $user->phone }}" id="pf-phone"
                      class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                  </div>
                  <div class="flex flex-col gap-y-2">
                    <label for="pf-email" class="text-sm font-semibold">Email</label>
                    <input value="{{ $user->email }}" id="pf-email" type="email"
                      class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                  </div>
                </fieldset>
                <fieldset class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-6">
                  <div class="flex flex-col gap-y-2">
                    <label for="pf-dir" class="text-sm font-semibold">Dirección</label>
                    <input value="2337 Calle" id="pf-dir"
                      class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                  </div>
                  <div class="flex flex-col gap-y-2">
                    <label for="pf-city" class="text-sm font-semibold">Ciudad</label>
                    <input value="Ciudad" id="pf-city"
                      class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                  </div>
                  <div class="flex flex-col gap-y-2">
                    <label for="pf-prov" class="text-sm font-semibold">Provincia</label>
                    <input value="Provincia" id="pf-prov"
                      class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                  </div>
                </fieldset>
              </div>
              <div class="me-24 flex justify-end">
                <button type="submit"
                  class="px-3 py-2 bg-purple-700 rounded-md text-lg hover:bg-purple-800 cursor-pointer">Guardar</button>
              </div>
            </form>
            <form method="dialog" class="absolute bottom-12 right-[3.25rem]">
              <button class="px-3 py-2 bg-red-700 rounded-md text-lg hover:bg-red-800 cursor-pointer">Cancelar</button>
            </form>
          </div>
        </x-modals.simple>
      </section>
    </div>
  </div>
@endsection
