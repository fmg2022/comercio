<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" type="image/x-icon">

  <title>{{ config('app.name', 'Comercio') }}</title>

  <!-- Scripts -->
  @vite(['resources/css/app.css'])

  @stack('scripts-app')
  <script src="{{ asset('js/modal.js') }}" defer></script>
  <script src="{{ asset('js/alert.js') }}" defer></script>

  <!-- Styles -->
  @stack('styles-app')
  <style>
    @keyframes fadeOut {
      to {
        opacity: 0;
      }
    }
  </style>
  @livewireStyles
</head>

<body class="relative antialiased min-h-screen flex flex-col bg-neutral-100">
  <!-- Page Header -->
  @include('layouts.partials.header')
  <!-- Page Content -->
  <main class="grow sm:px-3 md:px-6 lg:px-10">
    @isset($slot)
      {{ $slot }}
    @else
      @yield('content')
    @endisset
  </main>
  <!-- Page Footer -->
  @include('layouts.partials.footer')
  <!-- Scripts -->
  @stack('scripts-page')
  @livewireScripts
</body>

</html>
