<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Dashboard | {{ config('app.name', 'Comercio') }}</title>
  <link rel="icon" href="{{ asset('favicons/favicon.ico') }}" type="image/x-icon">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    .toggle-input:checked~.absolute {
      opacity: 1;
      top: 3.5rem;
    }

    #sidebar-toggle:checked~aside .check-visible {
      opacity: 1;
      visibility: visible;
    }

    #sidebar-toggle:checked~aside label[for='sidebar-toggle'] {
      background-color: oklch(20.8% 0.042 265.755);
    }
  </style>
  @stack('styles-dashboard')

  <script src="{{ asset('js/indexDash.js') }}" type="module"></script>
  @stack('scripts-dashboard')
</head>

<body class="antialiased relative font-sans bg-slate-900 text-teal-50 xl:flex">
  <x-asideMenu />

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
  </script>
</body>

</html>
