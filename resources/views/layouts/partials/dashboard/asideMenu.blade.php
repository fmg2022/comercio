<input type="checkbox" id="sidebar-toggle" class="peer hidden" />
<div
  class="fixed -left-full z-25 w-full h-full bg-slate-950/60 overlay opacity-0 peer-checked:opacity-100 peer-checked:left-0 xl:hidden">
</div>
<aside
  class="group fixed -left-full inset-y-0 z-30 h-screen w-72 bg-slate-800 border-b border-slate-700/60 transition-all duration-500 ease-in-out peer-checked:left-0 xl:sticky xl:left-0 xl:w-[90px] hover:w-72 xl:peer-checked:w-72">
  <div class="flex justify-between py-5 px-3 xl:min-w-full xl:w-72">
    <a href="{{ route('home') }}" class="relative flex gap-2 items-center px-3">
      <img src="{{ asset('images/logo/logo.jpg') }}" alt="logo" width="40px">
      <span
        class="relative block font-semibold text-lg xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible transition duration-500 ease-in">{{ config('app.name', 'Comercio') }}</span>
    </a>
    <label for="sidebar-toggle"
      class="relative p-2 hover:bg-slate-900 rounded-full cursor-pointer opacity-80 xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible transition-opacity duration-500">
      <span class="block xl:hidden peer-checked:xl:block">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="m7.85 13l2.85 2.85q.3.3.288.7t-.288.7q-.3.3-.712.313t-.713-.288L4.7 12.7q-.3-.3-.3-.7t.3-.7l4.575-4.575q.3-.3.713-.287t.712.312q.275.3.288.7t-.288.7L7.85 11H19q.425 0 .713.288T20 12t-.288.713T19 13z" />
        </svg>
      </span>
      <span class="hidden xl:block peer-checked:xl:hidden">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
          <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </span>
    </label>
  </div>
  <div class="relative overflow-y-auto py-4 xl:overflow-x-hidden">
    <ul id="sidebar-menu"
      class="relative flex flex-col space-y-3 px-3 text-slate-200/75 [&>li]:transition-colors [&>li]:duration-200 [&>li]:rounded-md [&>li.active]:bg-slate-700 [&>li.active]:text-purple-500">
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="{{ route('dashboard') }}" class="px-3 py-2 flex items-center gap-3" data-section="dashboard">
          <span>
            <x-icons.dashboard />
          </span>
          <span
            class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Dashboard</span>
        </a>
      </li>
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="{{ route('orders.index') }}" class="px-3 py-2 flex items-center gap-3" data-section="orders">
          <span>
            <x-icons.order />
          </span>
          <span
            class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Ordenes</span>
        </a>
      </li>
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="{{ route('payments.index') }}" class="px-3 py-2 flex items-center gap-3" data-section="payments">
          <span>
            <x-icons.payment />
          </span>
          <span class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Pagos</span>
        </a>
      </li>
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="{{ route('products.index') }}" class="px-3 py-2 flex items-center gap-3" data-section="products">
          <span>
            <x-icons.product />
          </span>
          <span
            class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Productos</span>
        </a>
      </li>
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="{{ route('categories.index') }}" class="px-3 py-2 flex items-center gap-3" data-section="categories">
          <span>
            <x-icons.category />
          </span>
          <span
            class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Categorías</span>
        </a>
      </li>
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="{{ route('users.index') }}" class="relative px-3 py-2 flex items-center gap-3" data-section="users">
          <span>
            <x-icons.user />
          </span>
          <span
            class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Usuarios</span>
        </a>
      </li>
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="{{ route('addresses.index') }}" class="relative px-3 py-2 flex items-center gap-3"
          data-section="addresses">
          <span>
            <x-icons.address />
          </span>
          <span
            class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Direcciones</span>
        </a>
      </li>
      <li class="hover:bg-slate-700 hover:text-purple-500">
        <a href="#" class="px-3 py-2 flex items-center gap-3" data-section="support">
          <span>
            <x-icons.support />
          </span>
          <span
            class="xl:opacity-0 xl:invisible group-hover:opacity-100 group-hover:visible check-visible">Soporte</span>
        </a>
      </li>
    </ul>
  </div>
</aside>
