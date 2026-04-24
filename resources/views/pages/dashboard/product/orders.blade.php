@extends('layouts.dashboard')

@pushIf($orders->count() > 0, 'scripts-dashboard')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="module">
  const options = {
    chart: {
      type: 'line',
      zoom: {
        enabled: false
      },
      toolbar: {
        show: false
      },
      dropShadow: {
        enabled: true,
        color: 'oklch(44.4% 0.177 26.899)',
        top: 16,
        left: 7,
        blur: 6,
        opacity: 0.55
      },
    },
    colors: ['#EA1E8C'],
    series: [{
      name: 'productos',
      data: [25, 15, 10, 30, 45, 80, 50, 62, 65]
    }],
    title: {
      text: 'Productos vendidos por mes (2025)',
      align: 'left',
      style: {
        fontSize: '20px',
        fontWeight: 'bold',
        color: '#fff'
      }
    },
    dataLabels: {
      enabled: true,
    },
    stroke: {
      curve: 'smooth'
    },
    grid: {
      show: false
    },
    yaxis: {
      title: {
        text: 'Cantidad',
        style: {
          color: '#fff',
          fontSize: '16px',
          fontWeight: 'semibold'
        }
      },
      labels: {
        style: {
          colors: '#fff'
        }
      }
    },
    xaxis: {
      categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep'],
      labels: {
        style: {
          colors: '#fff'
        }
      }
    },
  }
  const chart = new ApexCharts(document.querySelector("#chart-product-orders"), options)
  chart.render()
</script>
@endPushIf

@push('scripts-dashboard')
  <script src="{{ asset('js/modal.js') }}" defer></script>
@endpush

@section('content')
  <article class="py-4 flex flex-col justify-center items-center gap-4 md:mb-7 md:flex-row">
    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
      class="size-56 rounded-lg md:size-32">
    <div class="grow">
      <h1 class="text-2xl font-semibold">
        {{ $product->name }}
        <span>{{ $product->brand->name }}</span>
        {{ $product->trashed() ? ' (Eliminado/a)' : '' }}
      </h1>
      <p class="relative mb-4">
        <x-buttons.link href="{{ route('products.show', $product->id) }}"
          class="text-slate-100 hover:text-purple-500 peer/popup">
          SKU: {{ $product->sku }}
        </x-buttons.link>
        <x-popups.text class="top-full left-0 hidden bg-purple-800/80 peer-hover/popup:inline-block">
          Ver Producto
        </x-popups.text>
      </p>
    </div>
    <div class="mb-4 flex flex-wrap gap-4">
      <x-buttons.linkFill href="{{ route('products.index') }}" class="bg-slate-700 active:bg-slate-600">
        Volver al listado
      </x-buttons.linkFill>
      <x-buttons.linkFill href="" class="bg-red-700 active:bg-red-800">
        Generar PDF
      </x-buttons.linkFill>
      @if ($product->trashed())
        <button type="button" onclick="openModal('restDialog')"
          class="px-3 py-2 bg-green-900 rounded-md hover:bg-green-800 cursor-pointer">
          Restaurar Producto
        </button>
        <x-modals.simple id="restDialog" title="{{ 'Restaurar el producto ' . $product->name }}">
          <div class="flex flex-col items-center justify-center">
            <span class="text-slate-500">
              <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24">
                <path fill="currentColor"
                  d="M12 20a8 8 0 1 0 0-16a8 8 0 0 0 0 16m0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-6h2v2h-2zm0-10h2v8h-2z" />
              </svg>
            </span>
            <p class="px-2 py-4 mb-3">¿Está seguro de que desea restaurar el producto?</p>
          </div>
          <div class="flex justify-end gap-3 text-white">
            <form action="{{ route('products.restore', $product->id) }}" method="POST">
              @csrf
              <button type="submit"
                class="px-3 py-2 bg-green-900 rounded-md hover:bg-green-800 cursor-pointer">Restaurar</button>
            </form>
            <form method="dialog">
              <button class="px-3 py-2 bg-slate-700 rounded-md hover:bg-slate-600 cursor-pointer">Cancelar</button>
            </form>
          </div>
        </x-modals.simple>
      @endif
    </div>
  </article>

  @if ($orders->count() > 0)
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>#</th>
          <th>Usuario</th>
          <th>Orden</th>
          <th>Fecha</th>
          <th class="hidden md:table-cell">Estado</th>
          <th>Cantidad</th>
          <th class="hidden sm:table-cell">Precio</th>
        </tr>
      </x-slot:thead>

      @foreach ($orders as $index => $order)
        <tr>
          @php
            $orderDate = Str::substr($order->date, 0, 10);
          @endphp
          <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}</td>
          <td class="relative">
            <x-buttons.link href="" class="hover:text-purple-500 peer/popup">
              {{ $order->user->fullName() }}
            </x-buttons.link>
            <x-popups.text class="top-3/4 left-1/4 hidden bg-purple-800/80 peer-hover/popup:inline-block">
              Ver Perfil
            </x-popups.text>
          </td>
          <td class="relative">
            <x-buttons.link href="{{ route('orders.show', $order->id) }}" class="hover:text-purple-500 peer/popup">
              #{{ $order->id }}
            </x-buttons.link>
            <x-popups.text class="top-3/4 left-1/4 hidden bg-purple-800/80 peer-hover/popup:inline-block">
              Ver Orden
            </x-popups.text>
          </td>
          <td>{{ $orderDate }}</td>
          <td class="hidden md:table-cell">
            <span @class([
                "font-semibold before:content-['●'] before:me-px",
                'text-amber-400' => $order->orderState->code === 'CREADO',
                'text-blue-400' => $order->orderState->code === 'PENDIENTE',
                'text-cyan-400' => $order->orderState->code === 'PAGADO',
                'text-green-400' => $order->orderState->code === 'COMPLETO',
                'text-purple-400' => $order->orderState->code === 'REEMBOLSADO',
                'text-red-400' => $order->orderState->code === 'CANCELADO',
            ])>
              {{ $order->orderState->code }}
            </span>
          </td>
          <td><span class="ms-2">{{ $order->pivot->quantity }}</span></td>
          <td class="hidden sm:table-cell">{{ $order->pivot->price }}</td>
        </tr>
      @endforeach
      </x-tables-table>

      <div id="chart-product-orders" class="w-full py-3 mb-2 mt-10 mx-auto text-slate-900 md:max-w-xl"></div>
    @else
      <h3 class="text-2xl font-semibold text-center">No hay ordenes encotradas</h3>
  @endif
@endsection
