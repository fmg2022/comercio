@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const months = @json($monthlySpending->pluck('month'));
      const spends = @json($monthlySpending->pluck('total'));

      const options = {
        grid: {
          borderColor: '#334155',
          strokeDashArray: 3
        },
        series: [{
          name: 'Gasto ($)',
          data: spends
        }],
        chart: {
          type: 'line',
          height: 300,
          toolbar: {
            show: false
          }
        },
        stroke: {
          curve: 'smooth',
          width: 2,
          colors: ['#10b981']
        },
        xaxis: {
          categories: months,
          labels: {
            style: {
              colors: '#cbd5e1',
              fontSize: '11px'
            }
          },
          axisBorder: {
            color: '#475569'
          },
          axisTicks: {
            color: '#475569'
          }
        },
        yaxis: {
          labels: {
            style: {
              colors: '#cbd5e1',
              fontSize: '11px'
            },
            formatter: (val) => val.toLocaleString('es-AR', {
              style: 'currency',
              currency: 'ARS'
            })
          }
        },
        tooltip: {
          theme: 'dark',
          y: {
            formatter: (val) => val.toLocaleString('es-AR', {
              style: 'currency',
              currency: 'ARS'
            })
          }
        }
      };
      new ApexCharts(document.querySelector("#spendingChart"), options).render();
    });
  </script>
@endPush

@section('content')
  <x-sections.headerTitle>
    <x-slot:textTitle>👋 Hola, {{ auth()->user()->name ?? 'Usuario' }}</x-slot:textTitle>
  </x-sections.headerTitle>

  {{-- Estado del carrito --}}
  @if ($currentCart && $currentCart->products->count())
    <section class="px-5 py-4 mb-8 bg-cyan-900 border-l-4 border-blue-500 rounded-r-lg">
      <p class="font-semibold">Tienes un pedido pendiente en tu carrito.</p>
      <x-buttons.link href="{{ route('cart.index') }}" class="hover:text-blue-400">Ver carrito</x-buttons.link>
    </section>
  @endif

  <div class="mb-7 grid md:grid-cols-2 gap-5">
    <section class="p-4 bg-slate-800/60 rounded shadow-md shadow-slate-500/60">
      <h3 class="text-xl font-semibold px-5 mb-3">📦 Últimos pedidos</h3>
      <x-tables.table class="md:[&_td]:px-1 lg:[&_td]:px-3">
        <x-slot:thead>
          <tr class="text-left">
            <th>Orden N°</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
          </tr>
        </x-slot:thead>
        <x-slot:tbody>
          @forelse ($recentOrders as $order)
            <tr>
              <td>
                <x-buttons.link href="{{ route('orders.show', $order->id) }}"
                  class="font-semibold text-purple-500 hover:text-purple-600">
                  #{{ $order->id }}
                </x-buttons.link>
              </td>
              <td>{{ $order->date->format('d/m/Y') }}</td>
              <td>
                ${{ number_format($order->total, 2, ',', '.') }}
              </td>
              <td>
                <span @class([
                    'font-semibold',
                    'text-blue-400' => $order->orderState->slug === 'pending',
                    'text-cyan-400' => $order->orderState->slug === 'paid',
                    'text-green-400' => $order->orderState->slug === 'completed',
                    'text-purple-400' => $order->orderState->slug === 'refunded',
                    'text-red-400' => $order->orderState->slug === 'cancelled',
                ])>
                  {{ $order->orderState->name }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center font-semibold text-slate-300">No hay ordenes disponibles</td>
            </tr>
          @endforelse

          @if ($recentOrders)
            <tr>
              <td colspan="5" class="text-center font-semibold text-slate-300">
                <x-buttons.linkFill href="{{ route('my.orders.index') }}"
                  class="underline-offset-4 hover:text-purple-500 hover:underline">
                  Ver historial completo
                </x-buttons.linkFill>
              </td>
            </tr>
          @endif
        </x-slot:tbody>
      </x-tables.table>
    </section>
    <section class="p-4 bg-slate-800/60 rounded shadow-md shadow-slate-500/60">
      <h3 class="text-xl font-semibold px-5 mb-3">❤️ Tus productos favoritos</h3>
      <ul class="flex flex-col gap-3">
        @foreach ($favoriteProducts as $product)
          <li class="flex items-center gap-3 bg-slate-800">
            <img src="{{ asset('images/products/' . $product->image) }}" class="size-16 object-cover rounded">
            <div class="grow">
              <p class="font-medium">{{ $product->name }}</p>
              <p class="text-sm text-gray-400">${{ number_format($product->price, 2, ',', '.') }}</p>
            </div>
            @if ($product->in_stock)
              <form action="{{ route('cart.addToCart') }}" method="POST" class="w-max me-3 flex flex-wrap gap-2">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <label class="w-full max-w-14 grid grid-cols-1">
                  <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                    class="ps-1.5 pe-0.5 py-1 text-sm text-gray-900 bg-slate-200 rounded-md outline-none">
                </label>
                <button type="submit"
                  class="group/button relative bg-green-800 p-1 rounded-md hover:bg-green-700 cursor-pointer">
                  <x-icons.plus class="size-4" />
                  <span
                    class="hidden group-hover/button:inline absolute top-full right-0 w-max mt-2 px-2 py-1 bg-slate-600 rounded-md text-sm">
                    Agregar al carrito</span>
                </button>
              </form>
            @else
              <button disabled="disabled"
                class="group/button p-1 me-3 relative bg-gray-700 rounded-md cursor-not-allowed">
                <x-icons.plus class="size-4" />
                <span
                  class="hidden group-hover/button:inline absolute top-full right-0 w-max mt-2 px-2 py-1 bg-slate-600 rounded-md text-sm">No
                  disponible</span>
              </button>
            @endif
          </li>
        @endforeach
      </ul>
    </section>
  </div>

  {{-- Gráfico de gastos mensuales --}}
  <div class="p-4 mb-6 rounded shadow">
    <h2 class="mb-3 text-xl font-semibold">📈 Evolución de tus gastos</h2>
    <div id="spendingChart" style="height: 300px;"></div>
  </div>

  <div class="bg-green-700 rounded shadow p-4 text-center">
    <p class="font-semibold">💰 Total ahorrado con promociones</p>
    <p class="text-3xl font-bold">$ {{ number_format($totalSaved, 2, ',', '.') }}</p>
  </div>
@endsection
