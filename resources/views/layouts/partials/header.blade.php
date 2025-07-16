<input type="checkbox" id="toggle-category" class="hidden peer/category" />
<input type="checkbox" id="toggle-cart" class="hidden peer/cart" />

<header
  class="sticky top-0 left-0 z-20 w-full px-3 py-2 flex items-center justify-between bg-slate-400 dark:bg-sky-800 border-b border-slate-300/50 dark:border-sky-700/50 lg:px-6">
  <div class="flex items-center flex-wrap gap-3 py-3 px-2">
    <a href="{{ route('home') }}" class="flex flex-wrap gap-2 items-center">
      <img src="{{ asset('images/logo/logo.jpg') }}" alt="logo" width="40px">
      <span class="font-semibold text-lg">{{ config('app.name', 'Comercio') }}</span>
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
              class="p-[9px] bg-black/10 rounded-l-lg outline-none">
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
        class="relative w-full flex items-center justify-center rounded-lg hover:bg-black/10 dark:hover:bg-white/10 has-[:checked]:bg-black/10 has-[:checked]:dark:bg-white/10 sm:w-max">
        <input type="checkbox" id="toggle-perf" class="peer/perfil hidden" />
        <label for="toggle-perf" class="p-3 flex gap-3 cursor-pointer sm:py-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <g fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="12" cy="6" r="4" />
              <path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" />
            </g>
          </svg>
          @auth
            @php
              $user = auth()->user();
            @endphp
            {{ $user->surname[0] . $user->name[0] }}
          @else
            <span>Perfil</span>
          @endauth
        </label>
        <div
          class="absolute -top-16 inset-x-0 invisible w-max h-max px-3 py-4 flex flex-col opacity-0 divide-y divide-sky-700/50 rounded-lg bg-blue-100 dark:bg-cyan-800 peer-checked/perfil:visible peer-checked/perfil:opacity-100 peer-checked/perfil:top-16 transition-all duration-300">

          @if (Route::has('login'))
            @auth
              <a href="{{ route('dashboard') }}" class="p-2 hover:text-sky-700 dark:hover:text-violet-400">Panel de
                usuario</a>
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="p-2 cursor-pointer hover:text-sky-700 dark:hover:text-violet-400">Cerrar
                  sesión</button>
              </form>
            @else
              <a href="{{ route('login') }}" class="p-2 hover:text-sky-700 dark:hover:text-violet-400">Iniciar Sesión</a>
              <a href="{{ route('register') }}" class="p-2 hover:text-sky-700 dark:hover:text-violet-400">Registrarse</a>
            @endauth
          @endif
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
  class="fixed top-0 -left-full z-20 w-full h-screen p-6 bg-blue-100 dark:bg-sky-950 overflow-y-auto peer-checked/category:left-0 sm:w-96 transition-all duration-300">
  <div class="flex justify-between mb-4">
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
  <ul class="h-[95%] py-5 grid content-start gap-2 overflow-y-auto" id="list-categories">
    <li class="border-b border-white/20 sm:border-none">
      <a href="#!"
        class="px-5 py-2 flex items-center justify-between text-lg rounded-lg hover:bg-slate-100/70 dark:hover:bg-sky-800/50">
        Ofertas
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
      </a>
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
              $<input type="text" name="price" value="10.000" maxlength="10"
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
              $<input type="text" name="price" value="10.000" maxlength="10"
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
              $<input type="text" name="price" value="10.000" maxlength="10"
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
    <x-buttons.linkFill href="#" class="bg-emerald-800/80 hover:bg-emerald-900">
      Proceder a pagar</x-buttons.linkFill>
  </div>
</aside>
