<!--
    Links de interes:
    * CHARTS
        - https://www.material-tailwind.com/docs/html/plugins/charts
        - https://apexcharts.com/javascript-chart-demos/area-charts/
    * JS
        - https://alpinejs.dev/
    * TEMPLATES
        - https://dashlite.net/demo2/ecommerce/index.html
        - https://dashlite.net/demo2/ecommerce/user-profile.html
        - https://dashlite.net/demo2/ecommerce/products.html
            *| usar el "privacity confg" del template para el link de 'configuracion'
        - https://dev.to/codewithsadee/responsive-shopping-cart-for-ecommerce-website-html-css-js-383g

        | Diseño del lateral izquierdo
        -https://delimart.com.ar/Aperitivos-2-3

        | Diseño de la card del producto y sub cabecera
        -https://www.vea.com.ar/bebidas/aguas/aguas-saborizadas
        -https://supermercado.laanonimaonline.com/bebidas/aguas/aguas-con-gas/n3_155/
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

        .table-auto {

            th,
            td {
                padding: 1rem 0.75rem;
            }
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
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </span>
            </label>
        </div>
        <div class="relative overflow-y-auto py-4 xl:overflow-x-hidden">
            <!-- Aplicar lógica para los link activos según la dirección url -->
            <ul
                class="relative flex flex-col space-y-3 px-3 text-slate-800 dark:text-slate-200/75 [&>li.active]:bg-slate-200 dark:[&>li.active]:bg-slate-700 [&>li.active]:text-slate-900/75 dark:[&>li.active]:text-purple-500">
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
        <header
            class="sticky top-0 left-0 z-10 w-full bg-teal-50 dark:bg-slate-800 border-b border-slate-100/60 dark:border-slate-700/60">
            <div class="container mx-auto flex justify-between items-center xl:justify-end">
                <div class="flex items-center gap-3 py-4 px-2 xl:hidden">
                    <label for="sidebar-toggle"
                        class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer xl:hidden">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </label>
                    <a href="{{ route('home') }}" class="flex flex-wrap gap-2 items-center">
                        <img src="{{ asset('images/logo/logo.jpg') }}" alt="logo" width="40px">
                        <span class="font-semibold text-lg">{{ config('app.name', 'Comercio') }}</span>
                    </a>
                </div>
                <nav class="px-3 py-4">
                    <ul class="flex items-center gap-2">
                        <li class="relative flex items-center justofy-center">
                            <button id="theme-toggle" type="button"
                                class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                                <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                                <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        fill-rule="evenodd" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </li>
                        <!-- notificaciones -->
                        <li class="relative flex items-center justify-center">
                            <label
                                class="inline-block p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-full cursor-pointer"
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 256 256">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 256 256">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 256 256">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 256 256">
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
                            <label
                                class="inline-block bg-purple-600 hover:bg-purple-700 rounded-full p-3 cursor-pointer"
                                for="toggle-perf">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="12" cy="6" r="4" />
                                        <path
                                            d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" />
                                    </g>
                                </svg>
                            </label>
                            <input type="checkbox" name="toggle-btns" id="toggle-perf" class="hidden toggle-input" />
                            <div
                                class="absolute right-2 -top-72 z-10 divide-y divide-slate-100 dark:divide-slate-600 border border-slate-100 dark:border-slate-700 rounded-md opacity-0 bg-slate-100 dark:bg-gray-800 text-slate-800 dark:text-slate-200/75 transition-all duration-500 ease-in-out">
                                <div
                                    class="px-7 py-5 hidden bg-teal-100 dark:bg-slate-900/50 sm:flex sm:gap-3 sm:items-center">
                                    <div
                                        class="p-3 size-11 flex items-center justify-center bg-purple-600 rounded-full">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                                <circle cx="12" cy="6" r="4" />
                                                <path
                                                    d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" />
                                            </g>
                                        </svg>
                                        <span>Ver Perfil</span>
                                    </a>
                                    <a href="#"
                                        class="py-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="1.5"
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
        <div class="px-2 py-5 sm:px-5 sm:py-7">
            <h1 class="pb-3 sm:pb-5 text-2xl font-bold">Dashboard</h1>
            <!-- section: totales de ventas y promedio de ordenes -->
            <section class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-8">
                <article
                    class="flex flex-col gap-4 bg-teal-50 dark:bg-slate-800/70 rounded-md shadow shadow-slate-400 dark:shadow-purple-900">
                    <section class="p-6 flex flex-col gap-3 md:p-5">
                        <div class="flex justify-between">
                            <h4 class="font-bold">Total de ventas</h4>
                            <!-- link al reporte de ventas [MISMO ESTILO QUE LINK: Ver todo] -->
                            <a href="#!"
                                class="text-purple-950 hover:text-purple-900 dark:text-purple-500 dark:hover:text-purple-600 hover:underline hover:underline-offset-4 transition-all duration-300 ease-in-out">
                                Ver reporte
                            </a>
                        </div>
                        <div class="flex flex-col gap-1 mb-3">
                            <h2 class="text-2xl font-bold">$100.000</h2> <!-- total de ventas anuales hasta la fecha -->
                            <span class="text-sm dark:text-slate-400"><strong>$8.300</strong> en el último mes</span>
                            <!-- total de ventas del mes anterior -->
                        </div>
                        <div class="flex flex-col">
                            <h5 class="text-sm font-semibold">Últimos 7 dias</h5>
                            <div class="flex justify-between">
                                <h2 class="text-2xl font-bold">$800</h2>
                                <!-- total de ventas de los últimos 7 dias hasta la fecha -->
                                <div class="flex flex-col justify-center items-end">
                                    <span class="flex items-center justify-center text-emerald-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v13m0-13l4 4m-4-4l-4 4" />
                                        </svg>
                                        8.35%
                                    </span>
                                    <!-- tasa de crecimiento: (semana pasada - utimos 7 dias) * 100 / (semana pasada + ultimos 7 dias) -->
                                    <span class="text-xs dark:text-slate-400">vs. semana pasada</span>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div id="chart-sales" class="dark:text-slate-900 mt-auto"></div>
                </article>
                <article
                    class="flex flex-col gap-4 bg-teal-50 dark:bg-slate-800/70 rounded-md shadow shadow-slate-400 dark:shadow-purple-900">
                    <section class="p-6 flex flex-col gap-2">
                        <div class="flex justify-between">
                            <h4 class="font-bold">Promedio de ordenes</h4>
                            <span class="text-sm opacity-75">Últimos 15 días</span>
                            <!-- seleccionar el periodo de tiempo: 15 dias, 30 dias, 3 meses -->
                        </div>
                        <div class="flex justify-between">
                            <h2 class="text-2xl"><strong>45</strong> / dia</h2>
                            <!-- promedio de ordenes realizadas por los últimos 15 días -->
                            <div class="flex flex-col justify-center items-end">
                                <span class="flex items-center justify-center text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13l4 4m-4-4l-4 4" />
                                    </svg>
                                    5.3%
                                </span>
                                <!-- tasa de crecimiento: (semana pasada - utimos 7 dias) * 100 / (semana pasada + ultimos 7 dias) -->
                                <span class="text-xs dark:text-slate-400">vs. semana pasada</span>
                            </div>
                        </div>
                    </section>
                    <div id="chart-ordes" class="dark:text-slate-900 mt-auto"></div>
                </article>
            </section>
            <section class="mt-7 bg-slate-100 rounded-md dark:bg-slate-800">
                <!-- section: tabla de ultimas ordenes computadas -->
                <h4 class="text-xl font-semibold px-5 py-3">Ordenes recientes</h4> <!-- Últimos 3 dias -->
                <table class="table-auto w-full divide-y divide-slate-500">
                    <thead class="text-sm dark:text-slate-400">
                        <!-- Obtener datos de DB -->
                        <tr class="text-left">
                            <th>Orden N°</th>
                            <th class=" hidden sm:table-cell">Cliente</th>
                            <th class=" hidden md:table-cell">Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <!-- Obtener datos de DB -->
                        <tr class="hover:bg-slate-200 dark:hover:bg-slate-950/30">
                            <td>
                                <!-- link idem a "Ver todo" -->
                                <a href="#"
                                    class="text-purple-950 font-semibold hover:text-purple-900 dark:text-purple-500 dark:hover:text-purple-600 hover:underline hover:underline-offset-4 transition-all duration-300 ease-in-out">#125</a>
                            </td>
                            <td class="hidden sm:table-cell">
                                <!-- Si no tiene imagen el usuario -->
                                <div class="flex items-center gap-2">
                                    <div
                                        class="p-3 size-8 flex items-center justify-center bg-purple-600/40 rounded-full">
                                        <span class="font-semibold text-fuchsia-500">JD</span>
                                    </div>
                                    <span>John Doe</span>
                                </div>
                            </td>
                            <td class="hidden md:table-cell text-slate-300/80">2023-01-01</td>
                            <td>
                                <span class="me-px text-slate-300/80">$</span>
                                100.000
                            </td>
                            <td>
                                <span
                                    class="relative text-yellow-400 ps-3 before:size-2 before:absolute before:top-1/2 before:left-0 before:-translate-y-1/2 before:bg-current before:rounded-full">Pendiente</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-200 dark:hover:bg-slate-950/30">
                            <td>
                                <!-- link idem a "Ver todo" -->
                                <a href="#"
                                    class="text-purple-950 font-semibold hover:text-purple-900 dark:text-purple-500 dark:hover:text-purple-600 hover:underline hover:underline-offset-4 transition-all duration-300 ease-in-out">#160</a>
                            </td>
                            <td class="hidden sm:table-cell">
                                <!-- Si no tiene imagen el usuario -->
                                <div class="flex items-center gap-2">
                                    <div
                                        class="p-3 size-8 flex items-center justify-center bg-green-600/40 rounded-full">
                                        <span class="font-semibold text-emerald-500">TS</span>
                                    </div>
                                    <span>Tom Smith</span>
                                </div>
                            </td>
                            <td class="hidden md:table-cell text-slate-300/80">2023-02-01</td>
                            <td>
                                <span class="me-px text-slate-300/80">$</span>
                                52.360
                            </td>
                            <td>
                                <span
                                    class="relative text-teal-400 ps-3 before:size-2 before:absolute before:top-1/2 before:left-0 before:-translate-y-1/2 before:bg-current before:rounded-full">Pagado</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-200 dark:hover:bg-slate-950/30">
                            <td>
                                <!-- link idem a "Ver todo" -->
                                <a href="#"
                                    class="text-purple-950 font-semibold hover:text-purple-900 dark:text-purple-500 dark:hover:text-purple-600 hover:underline hover:underline-offset-4 transition-all duration-300 ease-in-out">#253</a>
                            </td>
                            <td class="hidden sm:table-cell">
                                <!-- Si no tiene imagen el usuario -->
                                <div class="flex items-center gap-2">
                                    <div
                                        class="p-3 size-8 flex items-center justify-center bg-purple-600/40 rounded-full">
                                        <span class="font-semibold text-fuchsia-500">AS</span>
                                    </div>
                                    <span>Anna Serin</span>
                                </div>
                            </td>
                            <td class="hidden md:table-cell text-slate-300/80">2023-02-24</td>
                            <td>
                                <span class="me-px text-slate-300/80">$</span>
                                18.265
                            </td>
                            <td>
                                <span
                                    class="relative text-teal-400 ps-3 before:size-2 before:absolute before:top-1/2 before:left-0 before:-translate-y-1/2 before:bg-current before:rounded-full">Pagado</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-200 dark:hover:bg-slate-950/30">
                            <td>
                                <!-- link idem a "Ver todo" -->
                                <a href="#"
                                    class="text-purple-950 font-semibold hover:text-purple-900 dark:text-purple-500 dark:hover:text-purple-600 hover:underline hover:underline-offset-4 transition-all duration-300 ease-in-out">#325</a>
                            </td>
                            <td class="hidden sm:table-cell">
                                <!-- Si no tiene imagen el usuario -->
                                <div class="flex items-center gap-2">
                                    <div
                                        class="p-3 size-8 flex items-center justify-center bg-purple-600/40 rounded-full">
                                        <span class="font-semibold text-fuchsia-500">BG</span>
                                    </div>
                                    <span>Bill Goesa</span>
                                </div>
                            </td>
                            <td class="hidden md:table-cell text-slate-300/80">2023-11-21</td>
                            <td>
                                <span class="me-px text-slate-300/80">$</span>
                                130.000
                            </td>
                            <td>
                                <span
                                    class="relative text-red-400 ps-3 before:size-2 before:absolute before:top-1/2 before:left-0 before:-translate-y-1/2 before:bg-current before:rounded-full">Cancelado</span>
                            </td>
                        </tr>
                        <!-- ... -->
                    </tbody>
                </table>
            </section>
            <section class="mt-7 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- section: Ranking de productos vendidos -->
                <article class="p-6 bg-slate-100 dark:bg-slate-800">
                    <div class="mb-2 flex justify-between">
                        <h3 class="text-lg font-semibold">Top productos</h3>
                        <span class="text-sm opacity-75">Semanal</span>
                        <!-- Seleccionar el periodo de tiempo: Diario, Semanal, Mensual -->
                    </div>
                    <ul class="flex flex-col gap-3">
                        <li class="flex items-center gap-4">
                            <img src="product.webp" alt="foto" class="size-10 aspect-square">
                            <div class="grow">
                                <h4>Nombre del producto</h4>
                                <span class="font-thin dark:text-slate-400">$100</span>
                            </div>
                            <div class="text-right">
                                <h4>$1100</h4>
                                <span class="font-thin dark:text-slate-400">11 vendidos</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-4">
                            <img src="product.webp" alt="foto" class="size-10 aspect-square">
                            <div class="grow">
                                <h4>Nombre del producto</h4>
                                <span class="font-thin dark:text-slate-400">$130</span>
                            </div>
                            <div class="text-right">
                                <h4>$1040</h4>
                                <span class="font-thin dark:text-slate-400">8 vendidos</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-4">
                            <img src="product.webp" alt="foto" class="size-10 aspect-square">
                            <div class="grow">
                                <h4>Nombre del producto</h4>
                                <span class="font-thin dark:text-slate-400">$210</span>
                            </div>
                            <div class="text-right">
                                <h4>$2730</h4>
                                <span class="font-thin dark:text-slate-400">13 vendidos</span>
                            </div>
                        </li>
                    </ul>
                </article>
                <article class="p-6 bg-slate-100 dark:bg-slate-800">
                    <h3 class="mb-2 text-lg font-semibold">Estadísticas generales</h3>
                    <ul class="flex flex-col gap-3">
                        <li class="flex justify-between items-center">
                            <div>
                                <h4 class="font-thin dark:text-slate-400">Ordenes</h4>
                                <span class="text-xl font-semibold">1754</span> <!-- total de ordenes -->
                            </div>
                            <span class="p-2 bg-cyan-700/50 text-sky-400 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M6.505 2h11a1 1 0 0 1 .8.4l2.7 3.6v15a1 1 0 0 1-1 1h-16a1 1 0 0 1-1-1V6l2.7-3.6a1 1 0 0 1 .8-.4m12.5 6h-14v12h14zm-.5-2l-1.5-2h-10l-1.5 2zm-9.5 4v2a3 3 0 1 0 6 0v-2h2v2a5 5 0 0 1-10 0v-2z" />
                                </svg>
                            </span>
                        </li>
                        <li class="flex justify-between items-center">
                            <div>
                                <h4 class="font-thin dark:text-slate-400">Usuarios</h4>
                                <span class="text-xl font-semibold">813</span> <!-- total de usuarios -->
                            </div>
                            <span class="p-2 bg-indigo-700/50 text-violet-400 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5"
                                        d="M17 19.5c0-1.657-2.239-3-5-3s-5 1.343-5 3m14-3c0-1.23-1.234-2.287-3-2.75M3 16.5c0-1.23 1.234-2.287 3-2.75m12-4.014a3 3 0 1 0-4-4.472M6 9.736a3 3 0 0 1 4-4.472m2 8.236a3 3 0 1 1 0-6a3 3 0 0 1 0 6" />
                                </svg>
                            </span>
                        </li>
                        <li class="flex justify-between items-center">
                            <div>
                                <h4 class="font-thin dark:text-slate-400">Productos</h4>
                                <span class="text-xl font-semibold">420</span> <!-- total de productos -->
                            </div>
                            <span class="p-2 bg-fuchsia-700/50 text-pink-400 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5"
                                        d="m7.687 9.687l2.66 1.426A3.5 3.5 0 0 0 12 11.53M7.687 9.687L3.884 7.65m3.803 2.038l8.496-4.555l.128-.07M3.884 7.648a3.5 3.5 0 0 0-.51 1.82v5.061a3.5 3.5 0 0 0 1.845 3.085l5.127 2.748A3.5 3.5 0 0 0 12 20.78M3.884 7.649a3.5 3.5 0 0 1 1.335-1.264l5.127-2.748a3.5 3.5 0 0 1 3.308 0L16.31 5.06M12 11.53a3.5 3.5 0 0 0 1.654-.416l6.462-3.464M12 11.529v9.25m0 0a3.5 3.5 0 0 0 1.654-.416l5.127-2.748a3.5 3.5 0 0 0 1.846-3.085V9.47a3.5 3.5 0 0 0-.511-1.821m0 0a3.5 3.5 0 0 0-1.335-1.264l-2.47-1.324" />
                                </svg>
                            </span>
                        </li>
                        <li class="flex justify-between items-center">
                            <div>
                                <h4 class="font-thin dark:text-slate-400">Categorias</h4>
                                <span class="text-xl font-semibold">50</span> <!-- total de categorias -->
                            </div>
                            <span class="p-2 bg-orange-700/50 text-amber-400 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 64 64">
                                    <path fill="currentColor"
                                        d="M16.1 14.5h44.5c1.2 0 2.3-1 2.3-2.3s-1-2.3-2.3-2.3H16.1c-1.2 0-2.3 1-2.3 2.3s1 2.3 2.3 2.3m44.4 15.3H16.1c-1.2 0-2.3 1-2.3 2.3c0 1.2 1 2.3 2.3 2.3h44.5c1.2 0 2.3-1 2.3-2.3c-.1-1.3-1.1-2.3-2.4-2.3m0 19.7H16.1c-1.2 0-2.3 1-2.3 2.3s1 2.3 2.3 2.3h44.5c1.2 0 2.3-1 2.3-2.3s-1.1-2.3-2.4-2.3" />
                                    <circle cx="6.2" cy="12.2" r="2.7" fill="currentColor" />
                                    <circle cx="6.2" cy="32" r="2.7" fill="currentColor" />
                                    <circle cx="6.2" cy="51.8" r="2.7" fill="currentColor" />
                                </svg>
                            </span>
                        </li>
                    </ul>
                </article>
            </section>
        </div>
        <footer
            class="px-7 py-6 text-center bg-slate-100 dark:bg-slate-800 border-t border-slate-100/60 dark:border-slate-600/70 text-slate-800 dark:text-slate-400">
            <p>&copy; 2024 Dashboard. Template por <a href="{{ route('home') }}"
                    class="hover:text-purple-600 transition-colors">{{ config('app.name', 'Comercio') }}</a>
            </p>
        </footer>
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