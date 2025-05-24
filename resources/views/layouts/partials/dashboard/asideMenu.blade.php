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
          <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </span>
    </label>
  </div>
  <div class="relative overflow-y-auto py-4 xl:overflow-x-hidden">
    <!-- Aplicar lógica para los link activos según la dirección url -->
    <ul id="sidebar-menu"
      class="relative flex flex-col space-y-3 px-3 text-slate-800 dark:text-slate-200/75 [&>li.active]:bg-slate-200 dark:[&>li.active]:bg-slate-700 [&>li.active]:text-purple-500">
      <li
        class="hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900/75 dark:hover:text-purple-500 rounded-md transition-color duration-300 ease-in-out">
        <a href="{{ route('dashboard') }}" class="relative px-3 py-2 flex items-center gap-3" data-section="dashboard">
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
        <a href="{{ route('orders.index') }}" class="px-3 py-2 flex items-center gap-3" data-section="orders">
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
        <a href="{{ route('products.index') }}" class="px-3 py-2 flex items-center gap-3" data-section="products">
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
        <a href="#" class="relative px-3 py-2 flex items-center gap-3" data-section="users">
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
        <a href="#" class="px-3 py-2 flex items-center gap-3" data-section="support">
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
