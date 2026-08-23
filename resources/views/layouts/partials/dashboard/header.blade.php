<header class="sticky top-0 left-0 z-24 w-full bg-slate-800 border-b border-slate-700/60">
  <div class="px-4 flex justify-between items-center md:px-8 xl:justify-end">
    <div class="flex items-center gap-3 py-4 px-2 xl:hidden">
      <a href="{{ route('home') }}" class="flex flex-wrap gap-2 items-center">
        <img src="{{ asset('images/logo/logo.jpg') }}" alt="logo" width="40px">
        <span class="font-semibold text-lg">{{ config('app.name', 'Comercio') }}</span>
      </a>
      <label for="sidebar-toggle" class="p-2 hover:bg-slate-900 rounded-full cursor-pointer xl:hidden">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
          <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </label>
    </div>
    <nav class="px-5 py-4">
      <ul class="flex items-center gap-2">
        <li class="hidden sm:list-item">
          <h4 class="px-3 py-2 text-sm font-semibold text-slate-200 bg-slate-900 rounded-lg">
            {{ auth()->user()->roles->first()->display_name }}
          </h4>
        </li>
        <!-- notificaciones -->
        <li class="relative flex items-center justify-center">
          <label class="inline-block p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer"
            for="toggle-notf">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="currentColor" fill-rule="evenodd"
                d="M12 1.25A7.75 7.75 0 0 0 4.25 9v.704a3.53 3.53 0 0 1-.593 1.958L2.51 13.385c-1.334 2-.316 4.718 2.003 5.35q1.133.309 2.284.523l.002.005C7.567 21.315 9.622 22.75 12 22.75s4.433-1.435 5.202-3.487l.002-.005a29 29 0 0 0 2.284-.523c2.319-.632 3.337-3.35 2.003-5.35l-1.148-1.723a3.53 3.53 0 0 1-.593-1.958V9A7.75 7.75 0 0 0 12 1.25m3.376 18.287a28.5 28.5 0 0 1-6.753 0c.711 1.021 1.948 1.713 3.377 1.713s2.665-.692 3.376-1.713M5.75 9a6.25 6.25 0 1 1 12.5 0v.704c0 .993.294 1.964.845 2.79l1.148 1.723a2.02 2.02 0 0 1-1.15 3.071a26.96 26.96 0 0 1-14.187 0a2.02 2.02 0 0 1-1.15-3.07l1.15-1.724a5.03 5.03 0 0 0 .844-2.79z"
                clip-rule="evenodd" />
            </svg>
          </label>
          <input type="checkbox" name="toggle-btns" id="toggle-notf" class="hidden toggle-input" />
          <div
            class="absolute -right-4 -top-84 z-10 divide-y divide-slate-100 dark:divide-slate-600 border border-slate-100 dark:border-slate-700 rounded-md opacity-0 bg-slate-100 dark:bg-gray-800 transition-all duration-500 ease-in-out">
            <div class="py-3 px-5">
              <span class="opacity-80">Notificaciones</span>
            </div>
            <ul
              class="max-h-57.75 overflow-y-auto text-xs text-slate-800 dark:text-slate-200/75 divide-y divide-slate-100 dark:divide-slate-600">
              <li class="flex items-center gap-3 px-6 py-5">
                <div class="bg-teal-500/40 text-sky-400 rounded-full p-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                    <path fill="currentColor"
                      d="M230 200a6 6 0 0 1-12 0a90.1 90.1 0 0 0-90-90H46.49l37.75 37.76a6 6 0 1 1-8.48 8.48l-48-48a6 6 0 0 1 0-8.48l48-48a6 6 0 0 1 8.48 8.48L46.49 98H128a102.12 102.12 0 0 1 102 102" />
                  </svg>
                </div>
                <div class="flex flex-col gap-1 w-max">
                  <h5 class="text-pretty font-bold">
                    Mensaje generado por una <strong>acción</strong>
                  </h5>
                  <span class="font-thin">Hace 2 horas</span>
                </div>
              </li>
              <li class="flex items-center gap-3 px-6 py-5">
                <div class="bg-teal-500/40 text-sky-400 rounded-full p-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                    <path fill="currentColor"
                      d="M230 200a6 6 0 0 1-12 0a90.1 90.1 0 0 0-90-90H46.49l37.75 37.76a6 6 0 1 1-8.48 8.48l-48-48a6 6 0 0 1 0-8.48l48-48a6 6 0 0 1 8.48 8.48L46.49 98H128a102.12 102.12 0 0 1 102 102" />
                  </svg>
                </div>
                <div class="flex flex-col gap-1 w-max">
                  <h5 class="text-pretty font-bold">
                    Mensaje generado por una <strong>acción</strong>
                  </h5>
                  <span class="font-thin">Hace 2 horas</span>
                </div>
              </li>
              <li class="flex items-center gap-3 px-6 py-5">
                <div class="bg-yellow-500/40 text-amber-400 rounded-full p-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                    <path fill="currentColor"
                      d="m228.24 108.24l-48 48a6 6 0 0 1-8.48-8.48L209.51 110H128a90.1 90.1 0 0 0-90 90a6 6 0 0 1-12 0A102.12 102.12 0 0 1 128 98h81.51l-37.75-37.76a6 6 0 0 1 8.48-8.48l48 48a6 6 0 0 1 0 8.48" />
                  </svg>
                </div>
                <div class="flex flex-col gap-1 w-max">
                  <h5 class="text-pretty font-bold">
                    Mensaje generado por una <strong>acción</strong>
                  </h5>
                  <span class="font-thin">Hace 2 horas</span>
                </div>
              </li>
              <li class="flex items-center gap-3 px-6 py-5">
                <div class="bg-yellow-500/40 text-amber-400 rounded-full p-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                    <path fill="currentColor"
                      d="m228.24 108.24l-48 48a6 6 0 0 1-8.48-8.48L209.51 110H128a90.1 90.1 0 0 0-90 90a6 6 0 0 1-12 0A102.12 102.12 0 0 1 128 98h81.51l-37.75-37.76a6 6 0 0 1 8.48-8.48l48 48a6 6 0 0 1 0 8.48" />
                  </svg>
                </div>
                <div class="flex flex-col gap-1 w-max">
                  <h5 class="text-pretty font-bold">
                    Mensaje generado por una <strong>acción</strong>
                  </h5>
                  <span class="font-thin">Hace 2 horas</span>
                </div>
              </li>
            </ul>
            <div class="flex justify-center py-3 px-5">
              <x-buttons.link href="#" class="text-purple-500 hover:text-purple-600">
                Ver todo
              </x-buttons.link>
            </div>
          </div>
        </li>
        <!-- perfil -->
        <li class="relative flex items-center justify-center">
          <label class="inline-block bg-purple-600 hover:bg-purple-700 rounded-full p-3 cursor-pointer"
            for="toggle-perf">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
              <g fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="6" r="4" />
                <path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" />
              </g>
            </svg>
          </label>
          <input type="checkbox" name="toggle-btns" id="toggle-perf" class="hidden toggle-input" />
          <div
            class="absolute right-2 -top-72 z-10 divide-y divide-slate-100 dark:divide-slate-600 border border-slate-100 dark:border-slate-700 rounded-md opacity-0 bg-slate-100 dark:bg-gray-800 text-slate-800 dark:text-slate-200/75 transition-all duration-500 ease-in-out">
            <div class="px-7 py-5 hidden bg-teal-100 dark:bg-slate-900/50 sm:flex sm:gap-3 sm:items-center">
              @php
                $user = auth()->user();
              @endphp
              <div class="p-3 size-11 flex items-center justify-center bg-purple-600 rounded-full">
                <span class="font-bold">{{ $user->surname[0] . $user->name[0] }}</span>
              </div>
              <div class="flex flex-col gap-1 w-max">
                <h5>{{ $user->surname . ' ' . $user->name }}</h5>
                <span class="text-xs">{{ $user->email }}</span>
              </div>
            </div>
            <div class="px-7 py-2 flex flex-col">
              <a href="{{ route('profile.index') }}"
                class="py-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                  <g fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="6" r="4" />
                    <path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" />
                  </g>
                </svg>
                <span>Ver Perfil</span>
              </a>
              <a href="#" class="py-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">
                <x-icons.config class="size-4" />
                <span>Configuración</span>
              </a>
            </div>
            <div class="px-7 py-2 flex flex-col">
              <a href="{{ route('logout') }}">

              </a>
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="py-2 flex items-center gap-3 hover:text-violet-400 cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M13.496 21H6.5c-1.105 0-2-1.151-2-2.571V5.57c0-1.419.895-2.57 2-2.57h7M16 15.5l3.5-3.5L16 8.5m-6.5 3.496h10" />
                  </svg>
                  <span>Desconectar</span>
                </button>
              </form>
            </div>
          </div>
        </li>
      </ul>
    </nav>
  </div>
</header>
