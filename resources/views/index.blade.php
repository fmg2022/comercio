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
        <header x-data="{ open: false }" class="relative py-6 px-3 w-full flex items-center justify-between gap-4 dark:bg-slate-800">
            <!-- Logos -->
            <a href="/" class="flex gap-2 items-center justify-start">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ config('app.name', 'Comercio')}}
                </h2>
            </a>
            <!-- Links -->
            <nav :class="{'flex flex-1 flex-col border-b border-gray-200 dark:border-gray-600': open, 'hidden': !open}" class="absolute top-16 left-0 right-0 bg-slate-800 sm:static sm:flex sm:justify-between pb-4">
                <form class="flex items-center max-w-sm py-3 mx-2 sm:mx-0">
                    <label for="simple-search" class="sr-only">Buscar...</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M21.993 7.95a1 1 0 0 0-.029-.214c-.007-.025-.021-.049-.03-.074c-.021-.057-.04-.113-.07-.165c-.016-.027-.038-.049-.057-.075c-.032-.045-.063-.091-.102-.13c-.023-.022-.053-.04-.078-.061c-.039-.032-.075-.067-.12-.094l-.014-.006l-.008-.006l-8.979-4.99a1 1 0 0 0-.97-.001l-9.021 4.99q-.004.005-.011.01l-.01.004c-.035.02-.061.049-.094.073c-.036.027-.074.051-.106.082c-.03.031-.053.067-.079.102s-.057.066-.079.104c-.026.043-.04.092-.059.139c-.014.033-.032.064-.041.1a1 1 0 0 0-.029.21c-.001.017-.007.032-.007.05V16c0 .363.197.698.515.874l8.978 4.987l.001.001l.002.001l.02.011c.043.024.09.037.135.054c.032.013.063.03.097.039a1 1 0 0 0 .506 0c.033-.009.064-.026.097-.039c.045-.017.092-.029.135-.054l.02-.011l.002-.001l.001-.001l8.978-4.987c.316-.176.513-.511.513-.874V7.998c0-.017-.006-.031-.007-.048m-10.021 3.922L5.058 8.005L7.82 6.477l6.834 3.905zm.048-7.719L18.941 8l-2.244 1.247l-6.83-3.903zM13 19.301l.002-5.679L16 11.944V15l2-1v-3.175l2-1.119v5.705z"/>
                            </svg>
                        </div>
                        <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar producto..." required />
                    </div>
                    <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                        <span class="sr-only">Buscar</span>
                    </button>
                </form>
                <x-responsive-nav-label forLabel="category-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M21.484 7.125l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5a1 1 0 0 0-.002-1.749z" fill="currentColor"/><path d="M12 15.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.971-1.748L12 15.856z" fill="currentColor"/><path d="M12 19.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.971-1.748L12 19.856z" fill="currentColor"/></svg>
                    <span class="ms-3">Categorias</span>
                </x-responsive-nav-label>
                @if (Route::has('login'))
                    @auth
                        <x-responsive-nav-link :href="route('dashboard')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z" fill="currentColor"/></svg>
                            <span class="ms-3">Dashboard</span>
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="'#!'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8m4-9l-4-4m4 4l-4 4m4-4H9"/></svg>
                            <span class="ms-3">Desconectarse</span>
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link :href="route('login')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2S7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z" fill="currentColor"/></svg>
                            <span class="ms-3">Iniciar Sesión</span>
                        </x-responsive-nav-link>

                        @if (Route::has('register'))
                            <x-responsive-nav-link :href="route('register')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512"><path d="M442.8 99.6l-30.4-30.4c-7-6.9-18.2-6.9-25.1 0L355.5 101l55.5 55.5 31.8-31.7c6.9-7.1 6.9-18.3 0-25.2z" fill="currentColor"/><path d="M346.1 110.5L174.1 288 160 352l64-14.1 176.6-173z" fill="currentColor"/><path d="M384 256v150c0 5.1-3.9 10.1-9.2 10.1s-269-.1-269-.1c-5.6 0-9.8-5.4-9.8-10V138c0-5 4.7-10 10.6-10H256l32-32H87.4c-13 0-23.4 10.3-23.4 23.3v305.3c0 12.9 10.5 23.4 23.4 23.4h305.3c12.9 0 23.3-10.5 23.3-23.4V224l-32 32z" fill="currentColor"/></svg>
                                <span class="ms-3">Registrarse</span>
                            </x-responsive-nav-link>
                        @endif
                    @endauth
                @endif
                <x-responsive-nav-label forLabel="cart-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M21 4H2v2h2.3l3.28 9a3 3 0 0 0 2.82 2H19v-2h-8.6a1 1 0 0 1-.94-.66L9 13h9.28a2 2 0 0 0 1.92-1.45L22 5.27A1 1 0 0 0 21.27 4A.8.8 0 0 0 21 4m-2.75 7h-10L6.43 6h13.24z"/><circle cx="10.5" cy="19.5" r="1.5" fill="currentColor"/><circle cx="16.5" cy="19.5" r="1.5" fill="currentColor"/></svg>
                    <span class="ms-3">Mi Carrito</span>
                </x-responsiv-nav-label>
            </nav>
            <!-- Hamburger -->
            <button @click="open = ! open" class="inline-flex items-center justify-end sm:hidden p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <!-- Inputs para mostrar/ocultar contenido -->
        <input type="checkbox" id="cart-toggle" class="hidden" />
        <input type="checkbox" id="category-toggle" class="hidden" />

        <!-- Categories -->
        <aside class="fixed -top-full left-0 z-50 w-full h-screen overflow-auto p-8 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transition-all duration-300 ease-in-out">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight text-center mb-4">Categorías</h2>
            <x-label-check-X forLabel="category-toggle" />
            <div class="p-2">
                <ul class="grid content-center gap-3">
                    <!-- Listar las categorías -->
                    @forelse($categories as $category)
                        <li class="my-auto text-center text-xl">
                            @if ($category->children !== null)
                                <input type="checkbox" id="input_category_{{ $category->id }}" class="hidden peer">
                                <label for="input_category_{{ $category->id }}" class="block text-xl text-gray-400 cursor-pointer hover:text-gray-300 peer-checked:text-gray-300 peer-checked:mb-2">{{ $category->name }}</label>
                                <ul class="hidden peer-checked:block peer-checked:bg-slate-800 px-4 py-2 rounded">
                                    @foreach ($category->children as $child)
                                        <li class="my-auto text-center text-xl py-1">
                                            <span class="text-xl text-gray-300">{{ $child->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @empty
                        <li class="my-auto text-center text-xl">Sin categorías</li>
                    @endforelse
                </ul>
            </div>
        </aside>

        <!-- Cart -->
        <aside class="fixed top-0 -right-full z-50 w-full h-screen overflow-auto p-8 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transition-all duration-300 ease-in-out">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight text-center mb-4">Carrito</h2>
            <x-label-check-X forLabel="cart-toggle" />
            <div class="flex flex-col gap-2 p-2 min-h-[90%]">
                <p class="my-auto text-center text-xl">Sin productos</p>
            </div>
        </aside>
        <main class="container mx-auto">
            <!-- SECCION: Carrusel de ofertas -->
            <x-sections.carrousel-img :offers="$offers" />

            <!-- SECCION: Listado de categorias -->
            
            <!-- SECCION: Slider de productos recomendados -->
            <ul class="grid grid-cols-[repeat(auto-fill,minmax(300px,1fr))] gap-5 px-2 py-8">
                @foreach ($products as $product)
                    <li class="flex justify-center" >
                        <x-card :product="$product" />
                    </li>
                @endforeach
            </ul>

            <!-- SECCION: Listado de marcas -->
        </main>
    </body>
</html>
{{-- https://unpkg.com/flowbite@1.4.0/dist/flowbite.js --}}
{{-- carousel / slider --}}
