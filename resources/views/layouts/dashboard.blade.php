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
  <link rel="icon" href="{{ asset('favicons/favicon.ico') }}" type="image/x-icon">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
  <script src="{{ asset('js/indexDash.js') }}" type="module"></script>
  @stack('scripts-dashboard')
</head>

<body class="antialiased relative font-sans bg-teal-50 text-slate-900 dark:bg-slate-900 dark:text-teal-50 xl:flex">
  @include('layouts.partials.dashboard.asideMenu')

  <main class="min-h-screen flex flex-col grow">
    @include('layouts.partials.dashboard.header')

    <section class="px-2 py-5 grow sm:px-5 sm:py-7">
      @yield('content')
    </section>

    @include('layouts.partials.dashboard.footer')
  </main>

  <script>
    const $$ = (el) => document.querySelectorAll(el)
    const $ = (el) => document.querySelector(el)

    const $toggleAside = $('#sidebar-toggle')
    const overlay = $('.overlay')

    overlay.addEventListener("click", ev => {
      $toggleAside.checked = false
    })

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
