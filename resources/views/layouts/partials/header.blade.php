<input type="checkbox" id="toggle-category" class="hidden peer/category" />
<input type="checkbox" id="toggle-cart" class="hidden peer/cart" />

@php $cart = Cart::getContent(); @endphp

<header
  class="sticky top-0 left-0 z-20 w-full px-3 py-2 flex items-center justify-between text-slate-200 bg-[oklch(0.33_0.09_253.09)] border-b-4 border-cyan-700/30 lg:px-6">
  <div class="flex items-center flex-wrap gap-3 py-3 px-2">
    <a href="{{ route('home') }}" class="flex flex-wrap gap-2 items-center">
      <img src="{{ asset('images/logo/logo.jpg') }}" alt="logo" width="40px" class="rounded-md">
      <span class="font-semibold text-lg">{{ config('app.name', 'Comercio') }}</span>
    </a>
  </div>
  <input type="checkbox" id="toggle-nav" class="hidden peer/nav" />
  <nav
    class="fixed z-10 -top-full left-0 hidden p-3 bg-sky-950 peer-checked/nav:block peer-checked/nav:h-screen peer-checked/nav:w-full peer-checked/nav:top-0 peer-checked/nav:sm:h-auto peer-checked/nav:sm:w-auto sm:p-0 sm:sticky sm:top-0 sm:z-0 sm:block sm:grow sm:bg-transparent sm:dark:bg-transparent">
    <label for="toggle-nav"
      class="absolute top-2 right-2 p-2 hover:text-blue-200 hover:bg-white/10 rounded-md cursor-pointer sm:hidden">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
        <path
          d="M437.5 386.6L306.9 256l130.6-130.6c14.1-14.1 14.1-36.8 0-50.9-14.1-14.1-36.8-14.1-50.9 0L256 205.1 125.4 74.5c-14.1-14.1-36.8-14.1-50.9 0-14.1 14.1-14.1 36.8 0 50.9L205.1 256 74.5 386.6c-14.1 14.1-14.1 36.8 0 50.9 14.1 14.1 36.8 14.1 50.9 0L256 306.9l130.6 130.6c14.1 14.1 36.8 14.1 50.9 0 14-14.1 14-36.9 0-50.9z"
          fill="currentColor" />
      </svg>
    </label>
    <ul
      class="h-full flex flex-col items-center justify-center gap-4 text-lg sm:flex-row sm:justify-end sm:gap-3 sm:text-base">
      <li class="w-full rounded-lg hover:bg-cyan-800/50 sm:w-max sm:ms-6 sm:me-auto md:me-0 lg:ms-12">
        <div>
          <label for="toggle-category" class="p-3 flex items-center justify-center gap-3 cursor-pointer sm:py-2">
            <span class="font-semibold">Categorias</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="1.5" d="M18 9s-4.419 6-6 6s-6-6-6-6" color="currentColor" />
            </svg>
          </label>
        </div>
      </li>
      <li class="flex rounded-lg md:list-item md:grow hover:bg-white/10 has-checked:bg-white/10">
        <input type="checkbox" id="search-toggle" class="hidden peer/search">
        <label for="search-toggle" class="hidden p-3 cursor-pointer sm:inline-block md:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16">
            <g fill="currentColor">
              <path
                d="M13 6.5a6.47 6.47 0 0 1-1.258 3.844q.06.044.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5M6.5 12a5.5 5.5 0 1 0 0-11a5.5 5.5 0 0 0 0 11" />
            </g>
          </svg>
        </label>
        <div
          class="-top-20 right-[10dvw] bg-sky-950 sm:absolute sm:w-[73dvw] sm:py-3 sm:bg-[oklch(0.33_0.09_253.09)] sm:rounded-b-lg md:static md:w-auto md:p-0 peer-checked/search:sm:top-14 transition-all duration-300">
          <form method="GET" action="{{ route('product.search') }}"
            class="p-3 flex items-center justify-center sm:p-0">
            <label
              class="sm:w-sm md:w-auto lg:w-sm shadow-inner hover:shadow-emerald-500/25 focus-within:shadow-emerald-500/25 transition-shadow duration-300 ease-in-out">
              <input type="search" name="query" placeholder="Buscar producto..."
                class="p-[9px] w-full bg-black/10 rounded-l-lg outline-none placeholder:text-slate-400 placeholder:italic">
            </label>
            <button type="submit" class="p-3 rounded-r-lg bg-emerald-700/25 hover:bg-emerald-600/50 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16">
                <g fill="currentColor">
                  <path
                    d="M13 6.5a6.47 6.47 0 0 1-1.258 3.844q.06.044.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5M6.5 12a5.5 5.5 0 1 0 0-11a5.5 5.5 0 0 0 0 11" />
                </g>
              </svg>
            </button>
          </form>
        </div>
      </li>
      @if (!Route::is('cart.index'))
        <li class="w-full rounded-lg hover:bg-white/10 sm:w-max">
          <button onclick="openModal('aside-cart')"
            class="w-full p-3 flex items-center justify-center gap-3 sm:py-2 sm:gap-1 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M21 4H2v2h2.3l3.28 9a3 3 0 0 0 2.82 2H19v-2h-8.6a1 1 0 0 1-.94-.66L9 13h9.28a2 2 0 0 0 1.92-1.45L22 5.27A1 1 0 0 0 21.27 4A.8.8 0 0 0 21 4m-2.75 7h-10L6.43 6h13.24z" />
              <circle cx="10.5" cy="19.5" r="1.5" fill="currentColor" />
              <circle cx="16.5" cy="19.5" r="1.5" fill="currentColor" />
            </svg>
            <span class="sm:hidden">Mi carrito</span>
            @if (!empty($cart))
              ({{ count($cart) }})
            @endif
          </button>
        </li>
      @endif
      <li class="relative w-full rounded-lg hover:bg-white/10 has-checked:bg-white/10 sm:w-max">
        <input type="checkbox" id="toggle-perf" class="peer/perfil hidden" />
        <label for="toggle-perf" class="inline-block w-full p-3 cursor-pointer sm:py-2">
          <div class="flex items-center justify-center gap-3">
            <x-icons.user class="size-6" />
            <span>
              @auth
                @php
                  $user = auth()->user();
                @endphp
                {{ $user->surname[0] . $user->name[0] }}
              @else
                Mi Cuenta
              @endauth
            </span>
          </div>
        </label>
        <div
          class="absolute -top-16 left-1/2 -translate-x-1/2 invisible w-1/2 h-max px-3 py-4 flex flex-col opacity-0 rounded-lg text-center bg-cyan-800 peer-checked/perfil:visible peer-checked/perfil:opacity-100 peer-checked/perfil:top-16 sm:-left-full sm:translate-0 sm:w-max transition-all duration-300">
          @if (Route::has('login'))
            @auth
              <a href="{{ route('dashboard') }}" class="p-2 hover:text-sky-700 dark:hover:text-violet-400">Panel de
                usuario</a>
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit"
                  class="p-2 cursor-pointer hover:text-sky-700 dark:hover:text-violet-400">Desconectarse</button>
              </form>
            @else
              <a href="{{ route('login') }}" class="p-2 hover:text-sky-700 dark:hover:text-violet-400">Iniciar Sesión</a>
              <a href="{{ route('register') }}"
                class="p-2 hover:text-sky-700 dark:hover:text-violet-400">Registrarse</a>
            @endauth
          @endif
        </div>
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
  class="fixed top-0 -left-full z-20 w-full h-screen p-6 bg-sky-950 text-white overflow-y-auto sm:w-96 peer-checked/category:left-0 transition-all duration-300">
  <div class="flex justify-between mb-4">
    <h2 class="grow text-center text-xl font-semibold">Categorias</h2>
    <label for="toggle-category" class="hover:text-blue-200 hover:bg-white/10 rounded-lg cursor-pointer">
      <x-icons.x />
    </label>
  </div>
  <ul class="h-[95%] grid content-start gap-2 ">
    @if ($offers)
      <li class="border-b border-white/20">
        <input type="checkbox" id="ofertas-check" class="hidden peer/ofertas">
        <label for="ofertas-check"
          class="px-5 py-2 mb-2 flex items-center justify-between text-lg rounded-lg cursor-pointer hover:bg-sky-800/50">
          Ofertas
          <span class="p-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2.5" d="m7 10l5 5m0 0l5-5" />
            </svg>
          </span>
        </label>
        <div class="px-8 mb-2 hidden bg-sky-900 rounded-lg sm:w-full peer-checked/ofertas:block">
          <ul class="py-5 grid content-start gap-3">
            @foreach ($offers as $offer)
              <li>
                <x-buttons.link href="{{ route('product.findForOffer', $offer['id']) }}"
                  class="block text-lg hover:text-sky-400">
                  {{ $offer['offer_template']['name'] }}
                </x-buttons.link>
              </li>
            @endforeach
          </ul>
        </div>
      </li>
    @endif

    <x-sections.category-tree :categories="$categories" />
  </ul>
</aside>
@if (!Route::is('cart.index'))
  <!-- carrito -->
  <dialog id="aside-cart"
    class="group fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent translate-x-full backdrop:bg-black/30 backdrop:opacity-0 transition-[display,overlay,translate] duration-[400ms,1s] transition-discrete backdrop:transition-opacity backdrop:duration-400 open:translate-x-0 open:backdrop:opacity-100 starting:open:translate-x-full starting:open:backdrop:opacity-0"
    closedby="any">
    <div tabindex="0" class="absolute right-0 size-full max-w-md focus:outline-none">
      <div class="flex h-full flex-col overflow-y-auto bg-white shadow-xl">
        <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
          <div class="flex items-start justify-between">
            <h2 id="drawer-title" class="text-lg font-medium text-gray-900">
              Mi carrito ({{ count($cart) }})
            </h2>
            <form method="dialog" class="ml-3 flex h-7 items-center">
              <button type="submit" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                <x-icons.x />
              </button>
            </form>
          </div>

          @php $total = 0; @endphp
          @if (!empty($cart))
            <div class="mt-8 flow-root">
              <ul role="list" class="-my-6 divide-y divide-gray-200">
                @foreach ($cart as $item)
                  @php $total += $item->price * $item->quantity; @endphp

                  <li class="flex py-6">
                    <div class="size-24 shrink-0 overflow-hidden rounded-md border border-gray-200">
                      <img src="{{ asset('images/products/' . $item->attributes->image) }}"
                        alt="{{ $item->attributes->description }}" class="size-full object-cover" />
                    </div>
                    <div class="ml-4 flex flex-1 flex-col">
                      <div>
                        <div class="flex justify-between text-base font-medium text-gray-900">
                          <h3>
                            <a href="{{ route('product.show', $item->id) }}">{{ $item->name }}</a>
                          </h3>
                          <p class="ml-4">${{ number_format($item->price, 2, ',', '.') }}</p>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">{{ $item->attributes->brand }}</p>
                      </div>
                      <div class="flex flex-1 items-end justify-between text-sm">
                        <p class="text-gray-500">Cantidad: {{ $item->quantity }}</p>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">Quitar</button>
                        </form>
                      </div>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          @else
            <h4 class="relative top-1/2 -translate-y-1/2 font-bold text-2xl text-center">Sin productos en el carrito
            </h4>
          @endif
        </div>

        <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
          <div class="flex justify-between text-base font-medium text-gray-900">
            <p>Subtotal</p>
            <p>${{ number_format($total, 2, ',', '.') }}</p>
          </div>
          <div class="mt-6">
            <a href="{{ route('cart.index') }}"
              class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-xs hover:bg-indigo-700">
              Ver Carrito</a>
          </div>
          <div class="mt-6 flex justify-center gap-1 text-sm text-gray-500">
            <span>o</span>
            <form method="dialog">
              <button type="submit"
                class="font-medium text-indigo-600 hover:text-indigo-500 underline-offset-4 hover:underline transition-all duration-100">
                Continue Comprando
                <span aria-hidden="true"> &rarr;</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </dialog>
@endif

@session('success')
  <div
    class="fixed top-20 right-5 z-10 flex items-center w-full max-w-xs p-4 space-x-4 text-sm rounded-lg shadow-sm text-green-300 bg-green-600/90 animate-[700ms_ease-in-out_3s_fadeOut]"
    role="alert">
    <span class="text-green-300">
      <x-icons.success class="size-6" />
    </span>
    <p class="grow font-medium text-white">{{ $value }}</p>
    <button class="p-1 rounded-md text-green-300 hover:bg-green-300/20 cursor-pointer" type="button">
      <x-icons.x class="size-6" />
    </button>
  </div>
@endsession
