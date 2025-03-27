<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Comercio') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('scripts')

    <script type="module" src="{{ asset('js/index.js') }}" defer></script>
    <script>
        // It's best to inline this in `head` to avoid FOUC (flash of unstyled content) when changing pages or themes
        document.documentElement.classList.toggle(
            "dark",
            localStorage.theme === "dark" ||
            (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches),
        );
    </script>

</head>

<body
    class="relative antialiased min-h-screen flex flex-col bg-blue-50 dark:bg-cyan-950 text-slate-700 dark:text-white/90">
    <!-- Page Header -->
    @include('layouts.partials.header')
    <!-- Page Content -->
    <main class="grow sm:px-3 lg:px-6">
        @yield('content')
    </main>
    <!-- Page Footer -->
    @include('layouts.partials.footer')

    <template id="li-categoria">
        <li class="group border-b border-white/20 sm:border-none">
            <button
                class="w-full px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
                <span></span>
                <span class="p-2">
                    <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2.5" d="m7 10l5 5m0 0l5-5" />
                    </svg>
                    <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2.5" d="m10 17l5-5m0 0l-5-5" />
                    </svg>
                </span>
            </button>
            <div
                class="px-8 py-2 mx-6 my-2 hidden flex-col gap-3 divide-y divide-white/10 bg-black/10 rounded-lg group-hover:flex">
            </div>
        </li>
    </template>
</body>

</html>