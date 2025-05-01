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

  <script defer src="{{ asset('js/dashboard/asideMenu.js') }}"></script>

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

<body class="antialiased relative font-sans bg-teal-50 text-slate-900 dark:bg-slate-900 dark:text-teal-50 xl:flex">
  @include('layouts.partials.dashboard.asideMenu')

  <main class="min-h-screen flex flex-col grow">
    @include('layouts.partials.dashboard.header')

    <section class="px-2 py-5 grow sm:px-5 sm:py-7">
      <div class="px-5 flex justify-between items-center">
        <h1 class="pb-5 text-2xl font-bold">
          @yield('titleH1', 'Dashboard')
        </h1>
        @yield('header-actions')
      </div>
      @yield('content')
    </section>

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