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
      name: 'Productos',
      data: @json($quantity)
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
      categories: @json($months),
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

@pushIf(auth()->user()?->can('manage products-and-attributes'), 'scripts-dashboard')
<script src="{{ asset('js/dashboard/modalDelete.js') }}" defer></script>
@endPushIf

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
      @if ($product->trashed())
        <button type="button" data-text="Producto: '{{ $product->name }}'" data-uid="{{ $product->id }}"
          data-modalID="restDialog" data-path="{{ $product->id . '/restore' }}" data-delete="false"
          class="w-full px-4 py-2.5 flex items-center gap-3 cursor-pointer hover:bg-slate-700 transition-colors button-delete-restore">
          <span>
            <x-icons.restore class="size-5" />
          </span>
          Restaurar Producto
        </button>
      @endif
    </div>
  </article>

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

    @forelse ($orders as $index => $order)
      <tr>
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
        <td>{{ Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td>
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
        <td class="hidden sm:table-cell">${{ number_format($order->pivot->price, 2, '.', ',') }}</td>
      </tr>

    @empty
      <tr>
        <td colspan="8" class="text-center font-semibold text-slate-300">Sin ordenes registradas</td>
      </tr>
    @endforelse
  </x-tables.table>

  {{ $orders->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  @if (!empty($orders))
    <div id="chart-product-orders" class="w-full py-3 mb-2 mt-10 mx-auto text-slate-900 md:max-w-xl"></div>
  @endif

  {{ $orders->onEachSide(1)->links('pages.dashboard.partials.pagination') }}

  @can('manage products-and-attributes')
    <x-modals.delete id="restDialog" />
  @endcan
@endsection
