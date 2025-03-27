<header
  class="sticky top-0 left-0 z-10 w-full bg-teal-50 dark:bg-slate-800 border-b border-slate-100/60 dark:border-slate-700/60">
  <div class="px-4 flex justify-between items-center md:px-8 xl:justify-end">
    <div class="flex items-center gap-3 py-4 px-2 xl:hidden">
      <label for="sidebar-toggle"
        class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer xl:hidden">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
          <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </label>
      <a href="{{ route('home') }}" class="flex flex-wrap gap-2 items-center">
        <img src="{{ asset('images/logo/logo.jpg') }}" alt="logo" width="40px">
        <span class="font-semibold text-lg">{{ config('app.name', 'Comercio') }}</span>
      </a>
    </div>
    <nav class="px-5 py-4">
      <ul class="flex items-center gap-2">
        <li class="relative flex items-center justofy-center">
          <button id="theme-toggle" type="button"
            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
          </button>
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
            class="absolute -right-4 -top-[21rem] z-10 divide-y divide-slate-100 dark:divide-slate-600 border border-slate-100 dark:border-slate-700 rounded-md opacity-0 bg-slate-100 dark:bg-gray-800 transition-all duration-500 ease-in-out">
            <div class="py-3 px-5">
              <span class="opacity-80">Notificaciones</span>
            </div>
            <ul
              class="max-h-[231px] overflow-y-auto text-xs text-slate-800 dark:text-slate-200/75 divide-y divide-slate-100 dark:divide-slate-600">
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
              <a href="#"
                class="text-purple-950 hover:text-purple-900 dark:text-purple-500 dark:hover:text-purple-600 hover:underline hover:underline-offset-4 transition-all duration-300 ease-in-out">
                Ver todo
              </a>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                  <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path
                      d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2" />
                    <circle cx="12" cy="12" r="3" />
                  </g>
                </svg>
                <span>Configuración</span>
              </a>
            </div>
            <div class="px-7 py-2 flex flex-col">
              <a href="{{ route('logout') }}"
                class="py-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M13.496 21H6.5c-1.105 0-2-1.151-2-2.571V5.57c0-1.419.895-2.57 2-2.57h7M16 15.5l3.5-3.5L16 8.5m-6.5 3.496h10" />
                </svg><span>Desconectar</span>
              </a>
            </div>
          </div>
        </li>
      </ul>
    </nav>
  </div>
</header>