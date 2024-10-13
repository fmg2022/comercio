<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Comercio')}}</title>

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased dark:bg-slate-900 dark:text-white/50">
        <header x-data="{ open: false }" class="py-6 px-3">
            <nav class="flex flex-1 justify-between pb-4">
                <a href="/" class="flex gap-2 items-center justify-center">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ config('app.name', 'Comercio')}}
                    </h2>
                </a>
                <div class="flex gap-3 px-2">
                    @if (Route::has('login'))
                        @auth
                            <x-nav-ancor link="dashboard">Dashboard</x-nav-ancor>
                        @else
                            <x-nav-ancor link="login">Iniciar Sesión</x-nav-ancor>
    
                            @if (Route::has('register'))
                                <x-nav-ancor link="register">Registrarse</x-nav-ancor>
                            @endif
                        @endauth
                    @endif
                    <!-- Hamburger -->
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                </div>
            </nav>
            <!-- Responsive Settings Options -->
            <div :class="{'block': open, 'hidden': ! open}" class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600 hidden sm:hidden">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">texto</div>
                    <div class="font-medium text-sm text-gray-500">otro texto</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="'#'">
                        Nav algo
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="'#'">
                        Nav algo2
                    </x-responsive-nav-link>
                </div>
            </div>
        </header>
    </body>
</html>
