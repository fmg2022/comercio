@extends('layouts.dashboard')

@push('scripts-dashboard')
  {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="{{ asset('js/dashboard/useCharts.js') }}" defer></script> --}}
@endpush

@section('content')
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
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M12 6v13m0-13l4 4m-4-4l-4 4" />
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
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6v13m0-13l4 4m-4-4l-4 4" />
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
    <table class="table-auto w-full divide-y divide-slate-500 [&_td,&_th]:px-5 [&_td,&_th]:py-3">
      <thead class="text-sm dark:text-slate-400">
        <tr class="text-left">
          <th>Orden N°</th>
          <th class=" hidden sm:table-cell">Cliente</th>
          <th class=" hidden md:table-cell">Fecha</th>
          <th>Total</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody class="text-sm">
        <tr class="hover:bg-slate-200 dark:hover:bg-slate-950/30">
          <td>
            <!-- link idem a "Ver todo" -->
            <a href="#"
              class="text-purple-950 font-semibold hover:text-purple-900 dark:text-purple-500 dark:hover:text-purple-600 hover:underline hover:underline-offset-4 transition-all duration-300 ease-in-out">#125</a>
          </td>
          <td class="hidden sm:table-cell">
            <!-- Si no tiene imagen el usuario -->
            <div class="flex items-center gap-2">
              <div class="p-3 size-8 flex items-center justify-center bg-purple-600/40 rounded-full">
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
              <div class="p-3 size-8 flex items-center justify-center bg-green-600/40 rounded-full">
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
              <div class="p-3 size-8 flex items-center justify-center bg-purple-600/40 rounded-full">
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
              <div class="p-3 size-8 flex items-center justify-center bg-purple-600/40 rounded-full">
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
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="1.5"
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
@endsection
