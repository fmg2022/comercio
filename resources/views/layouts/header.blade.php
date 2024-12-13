{{-- <header x-data="{ open: false }"
  class="relative py-6 px-3 w-full flex items-center justify-between gap-4 dark:bg-slate-800">
  <!-- Logos -->
  <a href="/" class="flex gap-2 items-center justify-start">
    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ config('app.name', 'Comercio')}}
</h2>
</a>
<!-- Links -->
<nav :class="{'flex flex-1 flex-col border-b border-gray-200 dark:border-gray-600': open, 'hidden': !open}"
  class="absolute top-16 left-0 right-0 z-10 bg-slate-800 md:static md:flex md:justify-between py-4">
  <form class="flex items-center max-w-sm w-full md:w-auto py-3 px-2 mx-auto md:mx-0">
    <label for="simple-search" class="sr-only">Buscar...</label>
    <div class="relative w-full">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M21.993 7.95a1 1 0 0 0-.029-.214c-.007-.025-.021-.049-.03-.074c-.021-.057-.04-.113-.07-.165c-.016-.027-.038-.049-.057-.075c-.032-.045-.063-.091-.102-.13c-.023-.022-.053-.04-.078-.061c-.039-.032-.075-.067-.12-.094l-.014-.006l-.008-.006l-8.979-4.99a1 1 0 0 0-.97-.001l-9.021 4.99q-.004.005-.011.01l-.01.004c-.035.02-.061.049-.094.073c-.036.027-.074.051-.106.082c-.03.031-.053.067-.079.102s-.057.066-.079.104c-.026.043-.04.092-.059.139c-.014.033-.032.064-.041.1a1 1 0 0 0-.029.21c-.001.017-.007.032-.007.05V16c0 .363.197.698.515.874l8.978 4.987l.001.001l.002.001l.02.011c.043.024.09.037.135.054c.032.013.063.03.097.039a1 1 0 0 0 .506 0c.033-.009.064-.026.097-.039c.045-.017.092-.029.135-.054l.02-.011l.002-.001l.001-.001l8.978-4.987c.316-.176.513-.511.513-.874V7.998c0-.017-.006-.031-.007-.048m-10.021 3.922L5.058 8.005L7.82 6.477l6.834 3.905zm.048-7.719L18.941 8l-2.244 1.247l-6.83-3.903zM13 19.301l.002-5.679L16 11.944V15l2-1v-3.175l2-1.119v5.705z" />
        </svg>
      </div>
      <input type="text" id="simple-search"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Buscar producto..." required />
    </div>
    <button type="submit"
      class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
      <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
      </svg>
      <span class="sr-only">Buscar</span>
    </button>
  </form>
  <x-responsive-nav-label forLabel="category-toggle">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
      <path
        d="M21.484 7.125l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5a1 1 0 0 0-.002-1.749z"
        fill="currentColor" />
      <path d="M12 15.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.971-1.748L12 15.856z"
        fill="currentColor" />
      <path d="M12 19.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.971-1.748L12 19.856z"
        fill="currentColor" />
    </svg>
    <span class="ms-3">Categorias</span>
  </x-responsive-nav-label>
  @if (Route::has('login'))
  @auth
  <x-responsive-nav-link :href="route('dashboard')">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
      <path
        d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"
        fill="currentColor" />
    </svg>
    <span class="ms-3">Dashboard</span>
  </x-responsive-nav-link>
  <x-responsive-nav-link :href="'#!'">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
      <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8m4-9l-4-4m4 4l-4 4m4-4H9" />
    </svg>
    <span class="ms-3">Desconectarse</span>
  </x-responsive-nav-link>
  @else
  <x-responsive-nav-link :href="route('login')">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
      <path
        d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2S7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z"
        fill="currentColor" />
    </svg>
    <span class="ms-3">Iniciar Sesión</span>
  </x-responsive-nav-link>

  @if (Route::has('register'))
  <x-responsive-nav-link :href="route('register')">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
      <path d="M442.8 99.6l-30.4-30.4c-7-6.9-18.2-6.9-25.1 0L355.5 101l55.5 55.5 31.8-31.7c6.9-7.1 6.9-18.3 0-25.2z"
        fill="currentColor" />
      <path d="M346.1 110.5L174.1 288 160 352l64-14.1 176.6-173z" fill="currentColor" />
      <path
        d="M384 256v150c0 5.1-3.9 10.1-9.2 10.1s-269-.1-269-.1c-5.6 0-9.8-5.4-9.8-10V138c0-5 4.7-10 10.6-10H256l32-32H87.4c-13 0-23.4 10.3-23.4 23.3v305.3c0 12.9 10.5 23.4 23.4 23.4h305.3c12.9 0 23.3-10.5 23.3-23.4V224l-32 32z"
        fill="currentColor" />
    </svg>
    <span class="ms-3">Registrarse</span>
  </x-responsive-nav-link>
  @endif
  @endauth
  @endif
  <x-responsive-nav-label forLabel="cart-toggle">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
      <path fill="currentColor"
        d="M21 4H2v2h2.3l3.28 9a3 3 0 0 0 2.82 2H19v-2h-8.6a1 1 0 0 1-.94-.66L9 13h9.28a2 2 0 0 0 1.92-1.45L22 5.27A1 1 0 0 0 21.27 4A.8.8 0 0 0 21 4m-2.75 7h-10L6.43 6h13.24z" />
      <circle cx="10.5" cy="19.5" r="1.5" fill="currentColor" />
      <circle cx="16.5" cy="19.5" r="1.5" fill="currentColor" />
    </svg>
    <span class="ms-3">Mi Carrito</span>
    </x-responsiv-nav-label>
</nav>
<!-- Hamburger -->
<button @click="open = ! open"
  class="inline-flex items-center justify-end md:hidden p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400">
  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round"
      stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
      stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
  </svg>
</button>

<button id="theme-toggle" type="button"
  class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
  <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
    xmlns="http://www.w3.org/2000/svg">
    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
  </svg>
  <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
    xmlns="http://www.w3.org/2000/svg">
    <path
      d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
      fill-rule="evenodd" clip-rule="evenodd"></path>
  </svg>
</button>
</header>

<!-- Inputs para mostrar/ocultar contenido -->
<input type="checkbox" id="cart-toggle" class="hidden" />
<input type="checkbox" id="category-toggle" class="hidden" />

<!-- Categories -->
<x-aside.list-items :label="'category-toggle'" :title="'Categorías'" class="-top-full left-0">
  <!-- Listar las categorías -->
  @forelse($categories as $category)
  <li class="my-auto text-center text-xl">
    @if ($category->children !== null)
    <input type="checkbox" id="input_category_{{ $category->id }}" class="hidden peer">
    <label for="input_category_{{ $category->id }}"
      class="block text-xl text-gray-400 cursor-pointer hover:text-gray-300 peer-checked:text-gray-300 peer-checked:mb-2">{{ $category->name }}</label>
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
</x-aside.list-items>

<!-- Cart -->
<x-aside.list-items :label="'cart-toggle'" :title="'Carrito'" class="top-0 -right-full">
  <li class="my-auto text-center text-xl">Carrito vacío</li>
</x-aside.list-items> --}}

<input type="checkbox" id="toggle-category" class="hidden peer/category" />
<input type="checkbox" id="toggle-cart" class="hidden peer/cart" />

<header
  class="relative z-20 w-full px-3 py-2 flex items-center justify-between bg-slate-400/25 dark:bg-sky-800/20 border-b border-slate-300/50 dark:border-sky-700/50 lg:px-6">
  <div class="flex items-center flex-wrap gap-3 py-3 px-2">
    <a href="catag.html" class="flex flex-wrap gap-2 items-center">
      <img src="logo.webp" alt="logo" width="40px">
      <span class="font-semibold text-lg">Comercio</span>
    </a>
  </div>
  <input type="checkbox" id="toggle-nav" class="hidden peer/nav" />
  <nav
    class="fixed z-10 -top-full left-0 hidden p-3 bg-blue-100 dark:bg-sky-950 peer-checked/nav:block peer-checked/nav:h-screen peer-checked/nav:w-full peer-checked/nav:top-0 peer-checked/nav:sm:h-auto peer-checked/nav:sm:w-auto sm:p-0 sm:sticky sm:top-0 sm:z-0 sm:block sm:grow sm:bg-transparent sm:dark:bg-transparent">
    <label for="toggle-nav"
      class="absolute top-2 right-2 p-2 hover:text-slate-900 dark:hover:text-blue-200 hover:bg-black/10 dark:hover:bg-white/10 rounded-md cursor-pointer sm:hidden">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
        <path
          d="M437.5 386.6L306.9 256l130.6-130.6c14.1-14.1 14.1-36.8 0-50.9-14.1-14.1-36.8-14.1-50.9 0L256 205.1 125.4 74.5c-14.1-14.1-36.8-14.1-50.9 0-14.1 14.1-14.1 36.8 0 50.9L205.1 256 74.5 386.6c-14.1 14.1-14.1 36.8 0 50.9 14.1 14.1 36.8 14.1 50.9 0L256 306.9l130.6 130.6c14.1 14.1 36.8 14.1 50.9 0 14-14.1 14-36.9 0-50.9z"
          fill="currentColor" />
      </svg>
    </label>
    <ul
      class="h-full flex flex-col items-center justify-center gap-4 text-lg sm:flex-row sm:justify-end sm:gap-3 sm:text-base">
      <li class="w-full rounded-lg dark:hover:bg-cyan-800/50 sm:w-max sm:ms-6 sm:me-auto md:me-0 lg:ms-12">
        <label for="toggle-category" class="p-3 flex items-center justify-center gap-3 cursor-pointer sm:py-2">
          <span class="font-semibold">Categorias</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M18 9s-4.419 6-6 6s-6-6-6-6" color="currentColor" />
          </svg>
        </label>
      </li>
      <li class="w-full sm:hidden md:block">
        <form class="p-3 flex items-center justify-center sm:p-0">
          <label
            class="shadow-inner hover:shadow-emerald-500/25 focus-within:shadow-emerald-500/25 transition-shadow duration-300 ease-in-out">
            <input type="search" name="q" placeholder="Buscar producto..."
              class="p-[10px] bg-black/10 rounded-l-lg outline-none">
          </label>
          <button type="submit" class="p-3 rounded-r-lg bg-emerald-700/25 hover:bg-emerald-600/50">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16">
              <g fill="currentColor">
                <path
                  d="M13 6.5a6.47 6.47 0 0 1-1.258 3.844q.06.044.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5M6.5 12a5.5 5.5 0 1 0 0-11a5.5 5.5 0 0 0 0 11" />
              </g>
            </svg>
          </button>
        </form>
      </li>
      <li class="w-full rounded-lg dark:hover:bg-cyan-800/50 sm:w-max">
        <button id="theme-toggle" type="button"
          class="absolute top-3 left-3 text-gray-500 dark:text-gray-400 hover:bg-black/10 dark:hover:bg-white/10 outline-none rounded-lg text-sm p-2.5 sm:static">
          <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
          </svg>
          <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
              fill-rule="evenodd" clip-rule="evenodd"></path>
          </svg>
        </button>
      </li>
      <li
        class="relative w-full flex items-center justify-center rounded-lg dark:hover:bg-cyan-800/50 has-[:checked]:bg-cyan-800/50 sm:w-max">
        <input type="checkbox" id="toggle-perf" class="peer/perfil hidden" />
        <label for="toggle-perf" class="p-3 flex gap-3 cursor-pointer sm:py-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <g fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="12" cy="6" r="4" />
              <path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" />
            </g>
          </svg>
          <span>Perfil</span>
        </label>
        <div
          class="absolute -top-16 inset-x-0 invisible w-max h-max px-3 py-4 flex flex-col opacity-0 divide-y divide-sky-700/50 rounded-lg bg-cyan-800 peer-checked/perfil:visible peer-checked/perfil:opacity-100 peer-checked/perfil:top-16 transition-all duration-300">
          <a href="#" class="p-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">Iniciar
            Sesión</a>
          <a href="#"
            class="p-2 flex items-center gap-3 hover:text-slate-900 dark:hover:text-violet-400">Registrarse</a>
        </div>
      </li>
      <li class="w-full rounded-lg dark:hover:bg-cyan-800/50 sm:w-max">
        <label for="toggle-cart" class="p-3 flex items-center justify-center gap-3 cursor-pointer sm:py-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="currentColor"
              d="M21 4H2v2h2.3l3.28 9a3 3 0 0 0 2.82 2H19v-2h-8.6a1 1 0 0 1-.94-.66L9 13h9.28a2 2 0 0 0 1.92-1.45L22 5.27A1 1 0 0 0 21.27 4A.8.8 0 0 0 21 4m-2.75 7h-10L6.43 6h13.24z" />
            <circle cx="10.5" cy="19.5" r="1.5" fill="currentColor" />
            <circle cx="16.5" cy="19.5" r="1.5" fill="currentColor" />
          </svg>
          <span class="sm:hidden">Mi carrito</span>
        </label>
      </li>
    </ul>
  </nav>
  <label for="toggle-nav" class="p-2 cursor-pointer sm:hidden">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 15 15">
      <path fill="currentColor" fill-rule="evenodd"
        d="M1.5 3a.5.5 0 0 0 0 1h12a.5.5 0 0 0 0-1zM1 7.5a.5.5 0 0 1 .5-.5h12a.5.5 0 0 1 0 1h-12a.5.5 0 0 1-.5-.5m0 4a.5.5 0 0 1 .5-.5h12a.5.5 0 0 1 0 1h-12a.5.5 0 0 1-.5-.5"
        clip-rule="evenodd" />
    </svg>
  </label>
</header>

<!-- categorias -->
<aside
  class="fixed top-0 -left-full z-20 w-full h-screen p-6 bg-blue-100 dark:bg-sky-950 peer-checked/category:left-0 sm:w-96 transition-all duration-300">
  <div class="flex justify-between mb-6">
    <h2 class="grow text-center text-xl font-semibold">Categorias</h2>
    <label for="toggle-category"
      class="p-2 hover:text-slate-900 dark:hover:text-blue-200 hover:bg-black/10 dark:hover:bg-white/10 rounded-lg cursor-pointer">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
        <path
          d="M437.5 386.6L306.9 256l130.6-130.6c14.1-14.1 14.1-36.8 0-50.9-14.1-14.1-36.8-14.1-50.9 0L256 205.1 125.4 74.5c-14.1-14.1-36.8-14.1-50.9 0-14.1 14.1-14.1 36.8 0 50.9L205.1 256 74.5 386.6c-14.1 14.1-14.1 36.8 0 50.9 14.1 14.1 36.8 14.1 50.9 0L256 306.9l130.6 130.6c14.1 14.1 36.8 14.1 50.9 0 14-14.1 14-36.9 0-50.9z"
          fill="currentColor" />
      </svg>
    </label>
  </div>
  <ul class="py-5 grid content-center gap-2">
    <li
      class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
      Ofertas
      <span class="p-2">
        <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m7 10l5 5m0 0l5-5" />
        </svg>
        <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m10 17l5-5m0 0l-5-5" />
        </svg>
      </span>
    </li>
    <li
      class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
      Almacén
      <span class="p-2">
        <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m7 10l5 5m0 0l5-5" />
        </svg>
        <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m10 17l5-5m0 0l-5-5" />
        </svg>
      </span>
    </li>
    <li
      class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
      Bebidas
      <span class="p-2">
        <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m7 10l5 5m0 0l5-5" />
        </svg>
        <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m10 17l5-5m0 0l-5-5" />
        </svg>
      </span>
    </li>
    <li
      class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
      Lácteos y productos frescos
      <span class="p-2">
        <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m7 10l5 5m0 0l5-5" />
        </svg>
        <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m10 17l5-5m0 0l-5-5" />
        </svg>
      </span>
    </li>
    <li
      class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
      Limpieza
      <span class="p-2">
        <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m7 10l5 5m0 0l5-5" />
        </svg>
        <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m10 17l5-5m0 0l-5-5" />
        </svg>
      </span>
    </li>
    <li
      class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
      Perfumería
      <span class="p-2">
        <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m7 10l5 5m0 0l5-5" />
        </svg>
        <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m10 17l5-5m0 0l-5-5" />
        </svg>
      </span>
    </li>
    <li
      class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
      Mascotas
      <span class="p-2">
        <svg class="sm:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m7 10l5 5m0 0l5-5" />
        </svg>
        <svg class="hidden sm:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="m10 17l5-5m0 0l-5-5" />
        </svg>
      </span>
    </li>
  </ul>
</aside>
<!-- carrito -->
<aside
  class="fixed top-0 -right-full z-20 w-full h-screen py-5 px-4 flex flex-col overflow-y-hidden bg-blue-100 dark:bg-sky-950 peer-checked/cart:right-0 sm:w-[30rem] transition-all duration-300">
  <div class="flex justify-between mb-6">
    <h2 class="grow text-center text-xl font-semibold">Carrito</h2>
    <label for="toggle-cart"
      class="p-2 hover:text-slate-900 dark:hover:text-blue-200 hover:bg-black/10 dark:hover:bg-white/10 rounded-lg cursor-pointer">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
        <path
          d="M437.5 386.6L306.9 256l130.6-130.6c14.1-14.1 14.1-36.8 0-50.9-14.1-14.1-36.8-14.1-50.9 0L256 205.1 125.4 74.5c-14.1-14.1-36.8-14.1-50.9 0-14.1 14.1-14.1 36.8 0 50.9L205.1 256 74.5 386.6c-14.1 14.1-14.1 36.8 0 50.9 14.1 14.1 36.8 14.1 50.9 0L256 306.9l130.6 130.6c14.1 14.1 36.8 14.1 50.9 0 14-14.1 14-36.9 0-50.9z"
          fill="currentColor" />
      </svg>
    </label>
  </div>
  <ul class="grow grid content-start gap-3 overflow-y-auto"
    style="scrollbar-width: thin; scrollbar-color: rgb(165 180 252) transparent;">
    <li>
      <article class="flex items-center gap-3 rounded-md dark:bg-blue-200/20">
        <a href="#" class="p-2 text-red-400 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
            <path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z" />
            <path fill="currentColor"
              d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z" />
          </svg>
        </a>
        <img src="product.webp" alt="producto" class="size-20 aspect-square">
        <div class="w-full ps-2 pe-4 flex flex-col gap-1 font-semibold">
          <h4>Nombre del producto 1</h4>
          <div class="flex items-center justify-between gap-2">
            <label class="px-2">
              <input type="number" name="qty" value="2" step="1" min="1" max="200"
                class="w-14 py-2 bg-transparent outline-none">
            </label>
            <label class="flex gap-1 pointer-events-none">
              $<input type="text" name="price" value="10.000" maxlength="10" class="w-24 bg-transparent outline-none"
                readonly>
            </label>
          </div>
        </div>
      </article>
    </li>
    <li>
      <article class="flex items-center gap-3 rounded-md dark:bg-blue-200/20">
        <a href="#" class="p-2 text-red-400 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
            <path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z" />
            <path fill="currentColor"
              d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z" />
          </svg>
        </a>
        <img src="product.webp" alt="producto" class="size-20 aspect-square">
        <div class="w-full ps-2 pe-4 flex flex-col gap-1 font-semibold">
          <h4>Nombre del producto</h4>
          <div class="flex items-center justify-between gap-2">
            <label class="px-2">
              <input type="number" name="qty" value="1" step="1" min="1" max="200"
                class="w-14 py-2 bg-transparent outline-none">
            </label>
            <label class="flex gap-1 pointer-events-none">
              $<input type="text" name="price" value="150.000,90" maxlength="10"
                class="w-24 bg-transparent outline-none" readonly>
            </label>
          </div>
        </div>
      </article>
    </li>
    <li>
      <article class="flex items-center gap-3 rounded-md dark:bg-blue-200/20">
        <a href="#" class="p-2 text-red-400 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
            <path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z" />
            <path fill="currentColor"
              d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z" />
          </svg>
        </a>
        <img src="product.webp" alt="producto" class="size-20 aspect-square">
        <div class="w-full ps-2 pe-4 flex flex-col gap-1 font-semibold">
          <h4>Nombre del producto 1</h4>
          <div class="flex items-center justify-between gap-2">
            <label class="px-2">
              <input type="number" name="qty" value="2" step="1" min="1" max="200"
                class="w-14 py-2 bg-transparent outline-none">
            </label>
            <label class="flex gap-1 pointer-events-none">
              $<input type="text" name="price" value="10.000" maxlength="10" class="w-24 bg-transparent outline-none"
                readonly>
            </label>
          </div>
        </div>
      </article>
    </li>
    <li>
      <article class="flex items-center gap-3 rounded-md dark:bg-blue-200/20">
        <a href="#" class="p-2 text-red-400 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
            <path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z" />
            <path fill="currentColor"
              d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z" />
          </svg>
        </a>
        <img src="product.webp" alt="producto" class="size-20 aspect-square">
        <div class="w-full ps-2 pe-4 flex flex-col gap-1 font-semibold">
          <h4>Nombre del producto</h4>
          <div class="flex items-center justify-between gap-2">
            <label class="px-2">
              <input type="number" name="qty" value="1" step="1" min="1" max="200"
                class="w-14 py-2 bg-transparent outline-none">
            </label>
            <label class="flex gap-1 pointer-events-none">
              $<input type="text" name="price" value="150.000,90" maxlength="10"
                class="w-24 bg-transparent outline-none" readonly>
            </label>
          </div>
        </div>
      </article>
    </li>
    <li>
      <article class="flex items-center gap-3 rounded-md dark:bg-blue-200/20">
        <a href="#" class="p-2 text-red-400 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
            <path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z" />
            <path fill="currentColor"
              d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z" />
          </svg>
        </a>
        <img src="product.webp" alt="producto" class="size-20 aspect-square">
        <div class="w-full ps-2 pe-4 flex flex-col gap-1 font-semibold">
          <h4>Nombre del producto 1</h4>
          <div class="flex items-center justify-between gap-2">
            <label class="px-2">
              <input type="number" name="qty" value="2" step="1" min="1" max="200"
                class="w-14 py-2 bg-transparent outline-none">
            </label>
            <label class="flex gap-1 pointer-events-none">
              $<input type="text" name="price" value="10.000" maxlength="10" class="w-24 bg-transparent outline-none"
                readonly>
            </label>
          </div>
        </div>
      </article>
    </li>
    <li>
      <article class="flex items-center gap-3 rounded-md dark:bg-blue-200/20">
        <a href="#" class="p-2 text-red-400 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
            <path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z" />
            <path fill="currentColor"
              d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z" />
          </svg>
        </a>
        <img src="product.webp" alt="producto" class="size-20 aspect-square">
        <div class="w-full ps-2 pe-4 flex flex-col gap-1 font-semibold">
          <h4>Nombre del producto</h4>
          <div class="flex items-center justify-between gap-2">
            <label class="px-2">
              <input type="number" name="qty" value="1" step="1" min="1" max="200"
                class="w-14 py-2 bg-transparent outline-none">
            </label>
            <label class="flex gap-1 pointer-events-none">
              $<input type="text" name="price" value="150.000,90" maxlength="10"
                class="w-24 bg-transparent outline-none" readonly>
            </label>
          </div>
        </div>
      </article>
    </li>
    <li>
      <h4>Sin productos en el carrito</h4>
    </li>
  </ul>
  <div
    class="flex flex-col items-center justify-center gap-3 mt-3 border-t border-blue-700/60 dark:border-slate-100/60">
    <p class="w-11/12 py-4 flex justify-between items-center">
      Subtotal:
      <span class="px-3 text-xl font-semibold">$ 170.000,90</span>
    </p>
    <a href="#" class="px-3 py-2 bg-emerald-800/80 hover:bg-emerald-900 rounded-lg">Proceder a pagar</a>
  </div>
</aside>