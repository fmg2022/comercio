<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://cdn.tailwindcss.com"></script>

  <title>Comercio</title>

  <script>
    // It's best to inline this in `head` to avoid FOUC (flash of unstyled content) when changing pages or themes
    if (
      localStorage.getItem('color-theme') === 'dark' ||
      (!('color-theme' in localStorage) &&
        window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  </script>

  <style>
    .toggle-input:checked~.absolute {
      opacity: 1;
      top: 3.5rem;
    }

    #sidebar-toggle:checked~aside .check-visible {
      opacity: 1;
      visibility: visible;
    }

    #dialog-perf::backdrop {
      background-color: rgba(0, 0, 0, 0.5);
    }
  </style>
</head>

<body
  class="antialiased relative font-sans bg-teal-50 text-slate-900 dark:bg-slate-900 dark:text-teal-50 has-[.peer:checked]:overflow-hidden xl:flex">
  <input type="checkbox" id="sidebar-toggle" class="peer hidden" />
  <div
    class="fixed -left-full z-[15] w-full h-full bg-slate-950/60 overlay opacity-0 peer-checked:opacity-100 peer-checked:left-0 xl:hidden">
  </div>
  <aside
    class="group fixed -left-full inset-y-0 z-20 h-screen w-72 bg-teal-50 dark:bg-slate-800 border-b border-slate-100/60 dark:border-slate-700/60 transition-all duration-500 ease-in-out peer-checked:left-0 xl:sticky xl:left-0 xl:w-[90px] hover:w-72 xl:peer-checked:w-72">
    <div class="flex justify-between py-5 px-3 xl:min-w-full xl:w-72">
      <a href="catag.html" class="relative flex gap-2 items-center px-3 xl:pointer-events-none">
        <img src="logo.webp" alt="logo" width="40px">
        <span
          class="relative block font-semibold text-lg xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible transition duration-500 ease-in">Comercio</span>
      </a>
      <label for="sidebar-toggle"
        class="relative p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer dark:opacity-80 xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible transition-opacity duration-500">
        <span class="block xl:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
            <path fill="currentColor"
              d="m7.85 13l2.85 2.85q.3.3.288.7t-.288.7q-.3.3-.712.313t-.713-.288L4.7 12.7q-.3-.3-.3-.7t.3-.7l4.575-4.575q.3-.3.713-.287t.712.312q.275.3.288.7t-.288.7L7.85 11H19q.425 0 .713.288T20 12t-.288.713T19 13z" />
          </svg>
        </span>
        <span class="hidden xl:block">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </span>
      </label>
    </div>
    <div class="relative overflow-y-auto py-4 xl:overflow-x-hidden">
      <ul
        class="relative flex flex-col space-y-3 px-3 text-slate-800 dark:text-slate-200/75 [&>li.active]:bg-slate-200 dark:[&>li.active]:bg-slate-700 [&>li.active]:text-slate-900/75 dark:[&>li.active]:text-purple-500">
        <li
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-colors duration-300 ease-in-out">
          <a href="catag.html" class="relative px-3 py-2 flex items-center gap-3">
            <span class="relative block">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="m20 8l-6-5.26a3 3 0 0 0-4 0L4 8a3 3 0 0 0-1 2.26V19a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3v-8.75A3 3 0 0 0 20 8m-6 12h-4v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1Zm5-1a1 1 0 0 1-1 1h-2v-5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v5H6a1 1 0 0 1-1-1v-8.75a1 1 0 0 1 .34-.75l6-5.25a1 1 0 0 1 1.32 0l6 5.25a1 1 0 0 1 .34.75Z" />
              </svg>
            </span>
            <span
              class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Inicio</span>
          </a>
        </li>
        <li
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-colors duration-300 ease-in-out active">
          <a href="#" class="relative px-3 py-2 flex items-center gap-3">
            <span class="relative block">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="M9 3a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm0 12a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2zm10-4a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2zm0-8a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
              </svg>
            </span>
            <span
              class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Dashboard</span>
          </a>
        </li>
        <li
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-colors duration-300 ease-in-out">
          <a href="#" class="px-3 py-2 flex items-center gap-3">
            <span class="relative block">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="M6.505 2h11a1 1 0 0 1 .8.4l2.7 3.6v15a1 1 0 0 1-1 1h-16a1 1 0 0 1-1-1V6l2.7-3.6a1 1 0 0 1 .8-.4m12.5 6h-14v12h14zm-.5-2l-1.5-2h-10l-1.5 2zm-9.5 4v2a3 3 0 1 0 6 0v-2h2v2a5 5 0 0 1-10 0v-2z" />
              </svg>
            </span>
            <span
              class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Ordenes</span>
          </a>
        </li>
        <li
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-colors duration-300 ease-in-out">
          <a href="#" class="px-3 py-2 flex items-center gap-3">
            <span class="relative block">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5"
                  d="m7.687 9.687l2.66 1.426A3.5 3.5 0 0 0 12 11.53M7.687 9.687L3.884 7.65m3.803 2.038l8.496-4.555l.128-.07M3.884 7.648a3.5 3.5 0 0 0-.51 1.82v5.061a3.5 3.5 0 0 0 1.845 3.085l5.127 2.748A3.5 3.5 0 0 0 12 20.78M3.884 7.649a3.5 3.5 0 0 1 1.335-1.264l5.127-2.748a3.5 3.5 0 0 1 3.308 0L16.31 5.06M12 11.53a3.5 3.5 0 0 0 1.654-.416l6.462-3.464M12 11.529v9.25m0 0a3.5 3.5 0 0 0 1.654-.416l5.127-2.748a3.5 3.5 0 0 0 1.846-3.085V9.47a3.5 3.5 0 0 0-.511-1.821m0 0a3.5 3.5 0 0 0-1.335-1.264l-2.47-1.324" />
              </svg>
            </span>
            <span
              class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Productos</span>
          </a>
        </li>
        <li
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-colors duration-300 ease-in-out">
          <a href="#" class="relative px-3 py-2 flex items-center gap-3">
            <span class="relative block">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="1.5"
                  d="M17 19.5c0-1.657-2.239-3-5-3s-5 1.343-5 3m14-3c0-1.23-1.234-2.287-3-2.75M3 16.5c0-1.23 1.234-2.287 3-2.75m12-4.014a3 3 0 1 0-4-4.472M6 9.736a3 3 0 0 1 4-4.472m2 8.236a3 3 0 1 1 0-6a3 3 0 0 1 0 6" />
              </svg>
            </span>
            <span
              class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Usuarios</span>
          </a>
        </li>
        <li
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-colors duration-300 ease-in-out">
          <a href="#" class="px-3 py-2 flex items-center gap-3">
            <span class="relative block">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256">
                <path fill="currentColor"
                  d="M216 44H40a20 20 0 0 0-20 20v160a19.82 19.82 0 0 0 11.56 18.1a20.1 20.1 0 0 0 8.49 1.9a19.9 19.9 0 0 0 12.82-4.72l.12-.11L84.47 212H216a20 20 0 0 0 20-20V64a20 20 0 0 0-20-20m-4 144H80a11.93 11.93 0 0 0-7.84 2.92L44 215.23V68h168ZM84 108a12 12 0 0 1 12-12h64a12 12 0 1 1 0 24H96a12 12 0 0 1-12-12m0 40a12 12 0 0 1 12-12h64a12 12 0 0 1 0 24H96a12 12 0 0 1-12-12" />
              </svg>
            </span>
            <span
              class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Soporte</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="min-h-screen flex flex-col xl:grow">
    <header
      class="sticky top-0 left-0 z-10 w-full bg-teal-50 dark:bg-slate-800 border-b border-slate-100/60 dark:border-slate-700/60">
      <div class="flex justify-between items-center xl:justify-end px-1 sm:px-2 md:px-4">
        <div class="flex items-center gap-3 py-4 px-2 xl:hidden">
          <label for="sidebar-toggle"
            class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer xl:hidden">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </label>
          <a href="catag.html" class="flex flex-wrap gap-2 items-center">
            <img src="logo.webp" alt="logo" width="40px">
            <span class="font-semibold text-lg">Comercio</span>
          </a>
        </div>
        <nav class="px-3 py-4">
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
                    <span class="font-bold">AL</span>
                  </div>
                  <div class="flex flex-col gap-1 w-max">
                    <h5 class="">Nombre completo</h5>
                    <span class="text-xs">correo@mail.com</span>
                  </div>
                </div>
                <div class="px-7 py-2 flex flex-col">
                  <a href="#" class="py-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">
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
                      <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2">
                        <path
                          d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2" />
                        <circle cx="12" cy="12" r="3" />
                      </g>
                    </svg>
                    <span>Configuración</span>
                  </a>
                </div>
                <div class="px-7 py-2 flex flex-col">
                  <a href="#" class="py-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">
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
    <div class="relative px-2 py-5 w-full grow flex sm:px-5 sm:py-7 divide-x divide-slate-300 dark:divide-slate-700">
      <input type="checkbox" id="info-toggle" class="hidden peer/aside">
      <div
        class="overlay fixed -left-full top-[73px] z-[15] w-full h-full bg-slate-950/60 opacity-0 peer-checked/aside:opacity-100 peer-checked/aside:left-0 lg:hidden">
      </div>
      <aside
        class="fixed -left-full top-[73px] z-40 w-full h-full max-w-[300px] dark:bg-slate-800 divide-y divide-slate-300 dark:divide-slate-700 transition-all duration-500 ease-in-out [&>section]:px-5 [&>section]:p-6 lg:static lg:left-0 lg:h-auto lg:z-0 peer-checked/aside:left-0">
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
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="1.5" d="m9 5l6 7l-6 7" />
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
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="1.5" d="m9 5l6 7l-6 7" />
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
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="1.5" d="m9 5l6 7l-6 7" />
              </svg>
            </span>
          </div>
        </section>
      </aside>
      <div class="w-full px-8 py-7 bg-teal-50 dark:bg-slate-800">
        <section class="mb-8 flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold text-white">Información Personal</h2>
            <p class="text-sm text-slate-400">Información básica, como nombre y dirección, que usas en
              <span>Comercio</span>
            </p>
          </div>
          <label for="info-toggle" class="text-slate-400 lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="M7.75 5.25h13.5M2.75 12h18.5m-10 6.75h10" />
            </svg>
          </label>
        </section>
        <section>
          <h3 class="px-6 py-2 mb-1 text-sm font-semibold uppercase bg-slate-300/75 dark:bg-slate-700/75 rounded">Datos
          </h3>
          <ul
            class="divide-y divide-slate-300 dark:divide-slate-700 [&>li]:px-6 [&>li]:py-4 dark:[&>li:hover_p:nth-child(1)]:text-slate-300 dark:[&>li:hover>span]:text-white"
            data-listDialog="true">
            <li class="flex justify-between text-slate-400 cursor-pointer">
              <div class="flex flex-col md:flex-row md:w-full">
                <p class="md:w-1/2">Nombre completo</p>
                <p>su nombre</p>
              </div>
              <span class="flex items-center md:w-[200px] md:justify-end">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5" d="m9 5l6 7l-6 7" />
                </svg>
              </span>
            </li>
            <li class="flex justify-between text-slate-400">
              <div class="flex flex-col md:flex-row md:w-full">
                <p class="md:w-1/2">Email</p>
                <p>mail@mail.com</p>
              </div>
              <span class="flex items-center md:w-[200px] md:justify-end">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M12 13a1.49 1.49 0 0 0-1 2.61V17a1 1 0 0 0 2 0v-1.39A1.49 1.49 0 0 0 12 13m5-4V7A5 5 0 0 0 7 7v2a3 3 0 0 0-3 3v7a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-7a3 3 0 0 0-3-3M9 7a3 3 0 0 1 6 0v2H9Zm9 12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1Z" />
                </svg>
              </span>
            </li>
            <li class="flex justify-between text-slate-400 cursor-pointer">
              <div class="flex flex-col md:flex-row md:w-full">
                <p class="md:w-1/2">Fecha de nacimiento</p>
                <p>31 Mar, 1990</p>
              </div>
              <span class="flex items-center md:w-[200px] md:justify-end">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5" d="m9 5l6 7l-6 7" />
                </svg>
              </span>
            </li>
            <li class="flex justify-between text-slate-400 cursor-pointer">
              <div class="flex flex-col md:flex-row md:w-full">
                <p class="md:w-1/2">Dirección</p>
                <p>2337 Calle,</p>
                <p>Ciudad Provincia</p>
              </div>
              <span class="flex items-center md:w-[200px] md:justify-end">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.5" d="m9 5l6 7l-6 7" />
                </svg>
              </span>
            </li>
          </ul>
          <dialog id="dialog-perf" class="w-full rounded-lg sm:max-w-md md:max-w-2xl">
            <div class="relative p-12 bg-slate-800/90">
              <h3 class="mb-4 text-xl font-bold">Actualizar Perfil</h3>
              <button type="button" class="absolute top-4 right-4 outline-none">X</button>
              <nav
                class="mb-5 flex gap-3 border-b border-slate-600/75 text-slate-400 [&>button]:pb-4 [&>button.active]:border-b-4 [&>button]:px-2 [&>button.active]:border-purple-700 [&>button.active]:text-purple-700 [&>button:hover]:text-white"
                data-navbar="true">
                <button class="active">Personal</button>
                <button>Dirección</button>
              </nav>
              <form method="dialog" class="overflow-x-hidden">
                <div class="relative left-0 top-0 w-[200%] mb-4 grid grid-cols-2">
                  <fieldset class="grid md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-name" class="text-sm font-semibold">Nombre</label>
                      <input value="Admin" id="pf-name"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-surname" class="text-sm font-semibold">Apellido</label>
                      <input value="Master" id="pf-surname"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-phone" class="text-sm font-semibold">Telefono</label>
                      <input value="+548524611133" id="pf-phone"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                    </div>
                    <div class="flex flex-col gap-y-2">
                      <label for="pf-date" class="text-sm font-semibold">Fecha de nacimiento</label>
                      <input value="1990-03-31" id="pf-date" type="date"
                        class="py-2.5 px-4 outline-none border border-slate-700 rounded-lg focus:shadow focus:shadow-slate-700 dark:focus:shadow-slate-400/50">
                    </div>
                  </fieldset>
                  <fieldset class="grid md:grid-cols-2 gap-x-2 gap-y-4">
                    <label>
                      Dirección
                      <input value="2337 Calle">
                    </label>
                    <label>
                      Ciudad
                      <input value="Ciudad">
                    </label>
                    <label>
                      Provincia
                      <input value="Provincia">
                    </label>
                  </fieldset>
                </div>
                <div class="flex gap-8 justify-end">
                  <button type="submit"
                    class="px-4 py-3 bg-purple-700 rounded-md font-semibold hover:bg-purple-800 transition-colors">Guardar</button>
                  <button type="button" class="text-slate-400 hover:text-white/90 transition-colors">Cancelar</button>
                </div>
              </form>
            </div>
          </dialog>
        </section>
      </div>
    </div>
    <footer
      class="px-7 py-6 bg-slate-100 dark:bg-slate-800 border-t border-slate-100/60 dark:border-slate-600/70 text-slate-800 dark:text-slate-400">
      <p>&copy; 2024 Dashboard. Template por <a href="catag.html"
          class="hover:text-purple-600 transition-colors">Comercio</a>
      </p>
    </footer>
  </main>

  <script>
    const $$ = (el) => document.querySelectorAll(el)
    const $ = (el) => document.querySelector(el)

    const inputsChecks = $$('[name="toggle-btns"]')
    const overlays = $$('.overlay')

    // Solo un dropdown este abierto a la vez
    if (inputsChecks.length > 0) {
      inputsChecks.forEach($input => {
        $input.addEventListener("input", ev => {
          inputsChecks.forEach($input => {
            if ($input !== ev.target) $input.checked = false
          })
        })
      })
    }

    overlays.forEach(overlay => {
      overlay.addEventListener("click", ev => {
        overlay.previousElementSibling.checked = false
      })
    })


    // Dialog
    const listOpensBtns = $$('[data-listDialog]')
    const dialog = $("#dialog-perf")
    const closeBtn = dialog.querySelector('button[type="button"]')

    closeBtn.addEventListener("click", ev => {
      dialog.close()
    })

    listOpensBtns.forEach(btn => {
      btn.addEventListener("click", ev => {
        dialog.showModal()
      })
    })

    dialog.addEventListener("click", ev => {
      if (ev.target === dialog) dialog.close()
    })

    // Navbar
    const $nav = $("[data-navbar]")
    const buttonsNav = $nav.querySelectorAll("button")

    buttonsNav.forEach((btn, i, arr) => {
      btn.addEventListener("click", ev => {
        btn.classList.add("active")
        arr.forEach(btn => btn !== arr[i] ? btn.classList.remove("active") : null)
      })
    })


    // Toggle theme
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon')
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon')

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      themeToggleLightIcon.classList.remove('hidden')
    } else {
      themeToggleDarkIcon.classList.remove('hidden')
    }

    const themeToggleButton = document.getElementById('theme-toggle')
    themeToggleButton.addEventListener('click', () => {
      // toggle icons inside button
      themeToggleDarkIcon.classList.toggle('hidden')
      themeToggleLightIcon.classList.toggle('hidden')

      // if set via local storage previously
      if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
          document.documentElement.classList.add('dark')
          localStorage.setItem('color-theme', 'dark')
        } else {
          document.documentElement.classList.remove('dark')
          localStorage.setItem('color-theme', 'light')
        }

        // if NOT set via local storage previously
      } else {
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark')
          localStorage.setItem('color-theme', 'light')
        } else {
          document.documentElement.classList.add('dark')
          localStorage.setItem('color-theme', 'dark')
        }
      }

    })

  </script>
</body>

</html>