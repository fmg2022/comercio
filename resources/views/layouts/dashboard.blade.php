<!--
    Links de interes:
    * CHARTS
        - https://www.material-tailwind.com/docs/html/plugins/charts
        - https://apexcharts.com/javascript-chart-demos/area-charts/
    * JS
        - https://alpinejs.dev/
-->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Dashboard | {{ config('app.name', 'Comercio') }}</title>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @yield('scripts')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  {{-- <script>
    // It's best to inline this in `head` to avoid FOUC (flash of unstyled content) when changing pages or themes
        if (
          localStorage.theme === 'dark' ||
          (!('theme' in localStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
          document.documentElement.classList.add('dark')
        } else {
          document.documentElement.classList.remove('dark')
        }
  </script> --}}

  <style>
    .toggle-input:checked~.absolute {
      opacity: 1;
      top: 3.5rem;
    }

    #sidebar-toggle:checked~aside .check-visible {
      opacity: 1;
      visibility: visible;
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
      <a href="{{ route('home') }}" class="relative flex gap-2 items-center px-3">
        <img src="{{ asset('images/logo/logo.jpg') }}" alt="logo" width="40px">
        <span
          class="relative block font-semibold text-lg xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible transition duration-500 ease-in">{{ config('app.name', 'Comercio') }}</span>
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
            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </span>
      </label>
    </div>
    <div class="relative overflow-y-auto py-4 xl:overflow-x-hidden">
      <!-- Aplicar lógica para los link activos según la dirección url -->
      <ul
        class="relative flex flex-col space-y-3 px-3 text-slate-800 dark:text-slate-200/75 [&>li.active]:bg-slate-200 dark:[&>li.active]:bg-slate-700 [&>li.active]:text-purple-500">
        <li
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-color duration-300 ease-in-out">
          <a href="{{ route('home') }}" class="relative px-3 py-2 flex items-center gap-3">
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
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-color duration-300 ease-in-out active">
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
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-color duration-300 ease-in-out">
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
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-color duration-300 ease-in-out">
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
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-color duration-300 ease-in-out">
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
          class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-color duration-300 ease-in-out">
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
  <main class="grow">
    @include('layouts.partials.dashboard.header')

    <div class="px-2 py-5 sm:px-5 sm:py-7">
      <h1 class="pb-3 sm:pb-5 text-2xl font-bold">
        @yield('title', 'Dashboard')
      </h1>

      @yield('content')
    </div>

    @include('layouts.partials.dashboard.footer')
  </main>

  <script>
    const $$ = (el) => document.querySelectorAll(el)
    const $ = (el) => document.querySelector(el)

    const inputsChecks = $$('[name="toggle-btns"]')
    const $toggleAside = $('#sidebar-toggle')
    const overlay = $('.overlay')
    
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

    overlay.addEventListener("click", ev => {
      $toggleAside.checked = false
      console.log(ev.target)

    })

    // Gráficas
    const optionsArea = {
      chart: {
        type: 'area',
        sparkline: {
          enabled: true
        },
      },
      stroke: {
        curve: 'smooth'
      },
      fill: {
        opacity: 1,
      },
      series: [{
        name: 'sales',
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
      }],
      colors: ['#9C27B0'],
    }

    const optionsBar = {
      chart: {
        type: 'bar',
        sparkline: {
          enabled: true
        },
      },
      colors: ['#EA1E8C'],
      series: [{
        name: 'orders',
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
      }],
      yaxis: {
        opposite: true,
      }
    }

    // const chartA = new ApexCharts(document.querySelector("#chart-sales"), optionsArea)
    // const chartB = new ApexCharts(document.querySelector("#chart-ordes"), optionsBar)

    // chartA.render()
    // chartB.render()

    // Toggle theme
    // const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon')
    // const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon')

    // Change the icons inside the button based on previous settings
    // if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    //   themeToggleLightIcon.classList.remove('hidden')
    // } else {
    //   themeToggleDarkIcon.classList.remove('hidden')
    // }

    // const themeToggleButton = document.getElementById('theme-toggle')
    // themeToggleButton.addEventListener('click', () => {
    //   // toggle icons inside button
    //   themeToggleDarkIcon.classList.toggle('hidden')
    //   themeToggleLightIcon.classList.toggle('hidden')

    //   // if set via local storage previously
    //   if (localStorage.getItem('color-theme')) {
    //     if (localStorage.getItem('color-theme') === 'light') {
    //       document.documentElement.classList.add('dark')
    //       localStorage.setItem('color-theme', 'dark')
    //     } else {
    //       document.documentElement.classList.remove('dark')
    //       localStorage.setItem('color-theme', 'light')
    //     }

    //     // if NOT set via local storage previously
    //   } else {
    //     if (document.documentElement.classList.contains('dark')) {
    //       document.documentElement.classList.remove('dark')
    //       localStorage.setItem('color-theme', 'light')
    //     } else {
    //       document.documentElement.classList.add('dark')
    //       localStorage.setItem('color-theme', 'dark')
    //     }
    //   }
    // })

  </script>
</body>

</html>