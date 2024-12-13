<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Comercio') }}</title>

	<!-- Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@yield('scripts')
</head>

<body
	class="relative antialiased min-h-screen flex flex-col bg-blue-50 dark:bg-cyan-950 text-slate-900 dark:text-white/90">
	<!-- Page Header -->
	@include('layouts.header')
	<!-- Page Content -->
	<main class="container mx-auto">
		@yield('content')
	</main>
	<!-- Page Footer -->
	@include('layouts.footer')
</body>

</html>