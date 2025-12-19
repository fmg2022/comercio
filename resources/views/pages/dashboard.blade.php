@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="{{ asset('js/dashboard/useCharts.js') }}" defer></script>
@endpush

@section('content')
  <section class="max-w-7xl px-2 mx-auto mt-4 mb-9 grid grid-cols-1 gap-x-4 gap-y-8 justify-items-center md:grid-cols-2">
    <article
      class="w-[min(100%,36rem)] flex flex-col gap-4 bg-slate-800/70 rounded-md shadow shadow-purple-900 overflow-hidden">
      <section class="p-6 flex flex-col gap-3 md:p-5">
        <div class="flex justify-between">
          <h4 class="text-xl font-semibold">Total de ventas</h4>
          <x-buttons.link href="#!" class="text-purple-500 hover:text-purple-600">
            Ver reporte
          </x-buttons.link>
        </div>
        <div class="flex flex-col gap-1 mb-3">
          <h2 class="text-3xl font-bold">{{ $totalSellers['lastYear'] }}</h2>
          <span class="text-sm text-slate-400">
            {{ $totalSellers['lastMonth'] }} en el último mes
          </span>
        </div>
        <div class="flex flex-col">
          <h5 class="text-sm font-medium">Últimos 7 dias</h5>
          <div class="flex justify-between">
            <h2 class="text-3xl font-bold">{{ $totalSellers['lastWeek'] }}</h2>
            <div class="flex flex-col justify-center items-end">
              <span @class([
                  'flex items-center justify-center',
                  'text-emerald-500' => $totalSellers['diffWeeks']['sign'] === '+',
                  'text-red-500 [&>svg]:rotate-180' =>
                      $totalSellers['diffWeeks']['sign'] === '-',
              ])>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M12 6v13m0-13l4 4m-4-4l-4 4" />
                </svg>
                {{ $totalSellers['diffWeeks']['value'] }}%
              </span>
              <span class="text-xs text-slate-400">vs. semana pasada</span>
            </div>
          </div>
        </div>
      </section>
      <div id="chart-sales" class="mt-auto text-slate-900"></div>
    </article>
    <article class="w-[min(100%,36rem)] flex flex-col gap-4 bg-slate-800/70 rounded-md shadow shadow-purple-900">
      <section class="p-6 flex flex-col gap-2">
        <div class="flex justify-between">
          <h4 class="text-xl font-semibold">Ordenes</h4>
          <h4 class="text-sm opacity-75">
            Del mes de <span class="capitalize">{{ $orderStatistics['month'] }}</span>
          </h4>
        </div>
        <div class="flex justify-between">
          <h2 class="text-3xl font-semibold">{{ $orderStatistics['thisMonth'] }}</h2>
          <div class="flex flex-col justify-center items-end">
            <span @class([
                'flex items-center justify-center',
                'text-emerald-500' => $orderStatistics['diffWeeks']['sign'] === '+',
                'text-red-500 [&>svg]:rotate-180' =>
                    $orderStatistics['diffWeeks']['sign'] === '-',
            ])>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6v13m0-13l4 4m-4-4l-4 4" />
              </svg>
              {{ $orderStatistics['diffWeeks']['value'] }}%
            </span>
            <span class="text-xs text-slate-400">vs. semana pasada</span>
          </div>
        </div>
      </section>
      <div id="chart-ordes" class="text-slate-900 mt-auto"></div>
    </article>
  </section>
  <section class="p-4 mb-7 bg-slate-800/60">
    <h4 class="text-xl font-semibold px-5 mb-3">Ordenes recientes</h4>
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>Orden N°</th>
          <th>Cliente</th>
          <th>Fecha</th>
          <th class=" hidden sm:table-cell">Total</th>
          <th class=" hidden md:table-cell">Estado</th>
        </tr>
      </x-slot:thead>

      @forelse ($orders as $order)
        @php
          $OrderDate = Str::substr($order->date, 0, 10);
        @endphp
        <tr>
          <td>
            <x-buttons.link href="{{ route('orders.show', $order->id) }}"
              class="font-semibold text-purple-500 hover:text-purple-600">
              #{{ $order->id }}
            </x-buttons.link>
          </td>
          <td>
            <span>{{ $order->user->fullName() }}</span>
          </td>
          <td class="text-slate-300/80">{{ $OrderDate }}</td>
          <td class="hidden sm:table-cell">
            <span class="me-px text-slate-300/80">$</span>
            {{ $order->total_formated }}
          </td>
          <td class="hidden md:table-cell">
            <span @class([
                "font-semibold before:content-['●'] before:me-px",
                'text-amber-400' => $order->orderStatus->name === 'Pendiente',
                'text-blue-400' => $order->orderStatus->name === 'Procesando',
                'text-purple-400' => $order->orderStatus->name === 'Completo',
                'text-cyan-400' => $order->orderStatus->name === 'Delivery',
                'text-indigo-400' => $order->orderStatus->name === 'Retirar',
                'text-green-400' => $order->orderStatus->name === 'Entregado',
                'text-red-400' => $order->orderStatus->name === 'Cancelado',
            ])>
              {{ $order->orderStatus->name }}
            </span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center font-semibold text-slate-300">No hay ordenes registradas</td>
        </tr>
      @endforelse
    </x-tables.table>
  </section>
  <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <article class="p-6 bg-slate-800">
      <div class="mb-2 flex justify-between">
        <h3 class="text-lg font-semibold">Top productos</h3>
        <span class="text-sm opacity-75">Semanal</span>
        <!-- Seleccionar el periodo de tiempo: Diario, Semanal, Mensual -->
      </div>
      <ul class="flex flex-col gap-3">
        @foreach ($bestSellers as $seller)
          <li class="flex items-center gap-4">
            <img src="{{ asset('images/products') }}/{{ $seller['image'] ?? 'zz_emptyProducto.webp' }}"
              alt="{{ $seller['name'] }}" class="size-10 aspect-square">
            <div class="grow">
              <h4>{{ $seller['name'] }}</h4>
              <span class="font-thin text-slate-400">${{ $seller['price_formated'] }}</span>
            </div>
            <div class="text-right">
              <h4>${{ $seller['orders_count'] * $seller['price'] }}</h4>
              <span class="font-thin text-slate-400">{{ $seller['orders_count'] }} vendidos</span>
            </div>
          </li>
        @endforeach
      </ul>
    </article>
    <article class="p-6 bg-slate-800">
      <h3 class="mb-2 text-lg font-semibold">Estadísticas generales</h3>
      <ul class="flex flex-col gap-3">
        @foreach ($statistics as $item)
          <li class="flex justify-between items-center">
            <div>
              <h4 class="font-thin text-slate-400">{{ $item['name'] }}</h4>
              <span class="text-xl font-semibold">{{ $item['total'] }}</span>
            </div>
            <span @class([
                'p-2 rounded',
                'bg-cyan-700/50 text-sky-400' => $item['color'] === 'cyan',
                'bg-indigo-700/50 text-violet-400' => $item['color'] === 'indigo',
                'bg-fuchsia-700/50 text-pink-400' => $item['color'] === 'fuchsia',
                'bg-orange-700/50 text-amber-400' => $item['color'] === 'orange',
            ])>
              @include('components.icons.' . $item['icon'], ['class' => 'size-7'])
            </span>
          </li>
        @endforeach
      </ul>
    </article>
  </section>
@endsection
