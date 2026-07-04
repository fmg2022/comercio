@extends('layouts.dashboard')

@push('scripts-dashboard')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Datos desde el controlador (igual que antes)
      const monthlyLabels = @json($monthlySales['labels']);
      const monthlyValues = @json($monthlySales['values']);
      const topProducts = @json($topProducts);
      const bottomProducts = @json($bottomProducts);
      const categoryLabels = @json($categorySales['labels']);
      const categoryValues = @json($categorySales['values']);

      const darkThemeBase = {
        tooltip: {
          theme: 'dark'
        },
        grid: {
          borderColor: '#334155',
          strokeDashArray: 3
        },
        xaxis: {
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
            formatter: (value) => value.toLocaleString()
          }
        },
        legend: {
          labels: {
            colors: '#e2e8f0'
          }
        }
      };

      // 1. Gráfico de líneas - Ingresos mensuales
      const revenueOptions = {
        ...darkThemeBase,
        series: [{
          name: 'Ingresos ($)',
          data: monthlyValues
        }],
        chart: {
          type: 'line',
          height: 450,
          toolbar: {
            tools: {
              download: false,
              pan: false,
              zoom: true
            }
          }
        },
        stroke: {
          curve: 'smooth',
          width: 3,
          colors: ['#60a5fa']
        },
        colors: ['#60a5fa'],
        xaxis: {
          ...darkThemeBase.xaxis,
          categories: monthlyLabels,
          title: {
            text: 'Mes',
            style: {
              color: '#94a3b8'
            }
          }
        },
        yaxis: {
          ...darkThemeBase.yaxis,
          title: {
            text: 'Ingresos ($)',
            style: {
              color: '#94a3b8'
            }
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
      const revenueChart = new ApexCharts(document.querySelector("#revenueLineChart"), revenueOptions);
      revenueChart.render();

      // 2. Gráfico de dona - Ventas por categoría
      const categoryOptions = {
        series: categoryValues.map(v => parseFloat(v)),
        chart: {
          type: 'donut',
          height: 450,
          background: 'transparent',
          toolbar: {
            show: false
          },
        },
        labels: categoryLabels,
        colors: ['#f97316', '#10b981', '#8b5cf6', '#ef4444', '#06b6d4', '#eab308'],
        legend: {
          position: 'bottom',
          labels: {
            colors: '#e2e8f0'
          }
        },
        plotOptions: {
          pie: {
            donut: {
              size: '60%',
              labels: {
                show: true,
                name: {
                  color: '#e2e8f0'
                },
                value: {
                  color: '#e2e8f0',
                  formatter: (val) => parseFloat(val).toLocaleString('es-AR', {
                    style: 'currency',
                    currency: 'ARS'
                  })
                },
                total: {
                  show: true,
                  label: 'Total',
                  formatter: () => {
                    const sum = categoryValues.reduce((acc, val) => acc + parseFloat(val), 0);
                    return sum.toLocaleString('es-AR', {
                      style: 'currency',
                      currency: 'ARS'
                    });
                  },
                  color: '#facc15'
                }
              }
            }
          }
        },
        tooltip: {
          theme: 'dark',
          y: {
            formatter: (val) => parseFloat(val).toLocaleString('es-AR', {
              style: 'currency',
              currency: 'ARS'
            })
          }
        }
      };
      const categoryChart = new ApexCharts(document.querySelector("#categoryDoughnutChart"), categoryOptions);
      categoryChart.render();

      // 3. Gráfico de barras horizontales - Top 5 productos más vendidos
      const topOptions = {
        ...darkThemeBase,
        series: [{
          name: 'Unidades vendidas',
          data: topProducts.map(p => p.total_sold)
        }],
        chart: {
          type: 'bar',
          height: 350,
          background: 'transparent',
          toolbar: {
            show: false
          }
        },
        plotOptions: {
          bar: {
            horizontal: true,
            barHeight: '70%',
            dataLabels: {
              position: 'top'
            }
          }
        },
        colors: ['#34d399'],
        xaxis: {
          ...darkThemeBase.xaxis,
          categories: topProducts.map(p => p.name),
          title: {
            text: 'Cantidad vendida',
            style: {
              color: '#94a3b8'
            }
          }
        },
        yaxis: {
          ...darkThemeBase.yaxis,
          labels: {
            style: {
              colors: '#cbd5e1'
            }
          }
        },
        dataLabels: {
          enabled: true,
          formatter: (val) => val + ' uds',
          style: {
            colors: ['#f1f5f9']
          }
        },
        tooltip: {
          theme: 'dark',
          y: {
            formatter: (val) => val + ' unidades'
          }
        }
      };
      const topChart = new ApexCharts(document.querySelector("#topProductsBarChart"), topOptions);
      topChart.render();

      // 4. Gráfico de barras horizontales - Top 5 menos vendidos
      const bottomOptions = {
        ...darkThemeBase,
        series: [{
          name: 'Unidades vendidas',
          data: bottomProducts.map(p => p.total_sold)
        }],
        chart: {
          type: 'bar',
          height: 350,
          background: 'transparent',
          toolbar: {
            show: false
          }
        },
        plotOptions: {
          bar: {
            horizontal: true,
            barHeight: '70%',
            dataLabels: {
              position: 'top'
            }
          }
        },
        colors: ['#f87171'],
        xaxis: {
          ...darkThemeBase.xaxis,
          categories: bottomProducts.map(p => p.name),
          title: {
            text: 'Cantidad vendida',
            style: {
              color: '#94a3b8'
            }
          }
        },
        yaxis: {
          ...darkThemeBase.yaxis,
          labels: {
            style: {
              colors: '#cbd5e1'
            }
          }
        },
        dataLabels: {
          enabled: true,
          formatter: (val) => val + ' uds',
          style: {
            colors: ['#f1f5f9']
          }
        },
        tooltip: {
          theme: 'dark',
          y: {
            formatter: (val) => val + ' unidades'
          }
        }
      };
      const bottomChart = new ApexCharts(document.querySelector("#bottomProductsBarChart"), bottomOptions);
      bottomChart.render();
    });
  </script>
@endPush

@section('content')
  <x-sections.headerTitle>
    <x-slot:textTitle>📊 Panel de Administración - {{ config('app.name') }}</x-slot:textTitle>
  </x-sections.headerTitle>

  {{-- Filtro de fechas --}}
  <div class="p-5 mb-7 bg-slate-800 rounded-md shadow-md shadow-slate-500/60">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-6 flex-wrap">
      <div>
        <label for="date_from" class="block mb-1 text-sm font-medium">Fecha desde:</label>
        <input type="date" name="start_date" id="date_from" value="{{ $startDate->format('Y-m-d') }}"
          class="w-36 px-2 py-1 border border-slate-500 rounded-md outline-none">
      </div>
      <div>
        <label for="date_to" class="block mb-1 text-sm font-medium">Fecha hasta:</label>
        <input type="date" name="end_date" id="date_to" value="{{ $endDate->format('Y-m-d') }}"
          class="w-36 px-2 py-1 border border-slate-500 rounded-md outline-none">
      </div>
      <div class="px-3 flex items-center justify-center gap-3">
        <button type="submit"
          class="px-3 py-2 font-semibold bg-purple-800 rounded-lg hover:bg-purple-700 cursor-pointer">Aplicar</button>
        <a href="{{ route('admin.dashboard') }}"
          class="px-3 py-2 font-semibold bg-slate-600 rounded-lg hover:bg-slate-500">Resetear</a>
      </div>
    </form>
  </div>

  {{-- KPIs --}}
  <section class="mb-8 grid grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-5">
    @php
      $revenue = $revenueData;
      $orders = $ordersData;
      $aov = $aovData;
      $conversion = $conversionData;
    @endphp
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-500/80 transition duration-200">
      <div class="text-sm uppercase tracking-wider text-slate-200">💰 Ingresos Totales</div>
      <div class="my-2 text-3xl font-bold">$ {{ number_format($revenue['current'], 2, ',', '.') }}</div>
      <div class="flex items-center gap-2 text-sm">vs período anterior: <span
          class="{{ $revenue['change'] >= 0 ? 'text-green-600' : 'text-red-500' }} font-bold">{{ $revenue['change'] >= 0 ? '+' : '' }}{{ $revenue['change'] }}%</span>
      </div>
      <small>Anterior: $ {{ number_format($revenue['previous'], 2, ',', '.') }}</small>
    </div>
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-500/80 transition duration-200">
      <div class="text-sm uppercase tracking-wider text-slate-200">📦 Pedidos completados</div>
      <div class="my-2 text-3xl font-bold">{{ number_format($orders['current']) }}</div>
      <div class="flex items-center gap-2 text-sm">vs anterior: <span
          class="{{ $orders['change'] >= 0 ? 'text-green-600' : 'text-red-500' }} font-bold">{{ $orders['change'] >= 0 ? '+' : '' }}{{ $orders['change'] }}%</span>
      </div>
      <small>Anterior: {{ number_format($orders['previous']) }}</small>
    </div>
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-500/80 transition duration-200">
      <div class="text-sm uppercase tracking-wider text-slate-200">🛒 Valor medio carrito (AOV)</div>
      <div class="my-2 text-3xl font-bold">$ {{ number_format($aov['current'], 2, ',', '.') }}</div>
      <div class="flex items-center gap-2 text-sm">vs anterior: <span
          class="{{ $aov['change'] >= 0 ? 'text-green-600' : 'text-red-500' }} font-bold">{{ $aov['change'] >= 0 ? '+' : '' }}{{ $aov['change'] }}%</span>
      </div>
      <small>Anterior: $ {{ number_format($aov['previous'], 2, ',', '.') }}</small>
    </div>
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-500/80 transition duration-200">
      <div class="text-sm uppercase tracking-wider text-slate-200">📈 Tasa de conversión</div>
      <div class="my-2 text-3xl font-bold">{{ $conversion['current'] }}%</div>
      <div class="flex items-center gap-2 text-sm">vs anterior: <span
          class="{{ $conversion['change'] >= 0 ? 'text-green-600' : 'text-red-500' }} font-bold">{{ $conversion['change'] >= 0 ? '+' : '' }}{{ $conversion['change'] }}%</span>
      </div>
      <small>Anterior: {{ $conversion['previous'] }}%</small>
    </div>
    <div
      class="p-4 bg-slate-800 rounded-lg border-t-4 border-t-sky-700 shadow-md shadow-slate-500/80 transition duration-200">
      <div class="text-sm uppercase tracking-wider text-slate-200">💎 LTV (Customer Lifetime Value)</div>
      <div class="my-2 text-3xl font-bold">$ {{ number_format($ltv, 2, ',', '.') }}</div>
      <div class="flex items-center gap-2 text-sm">(histórico desde inicio)</div>
    </div>
  </section>

  {{-- Gráficos --}}
  <div class="mb-8 grid grid-cols-[repeat(auto-fit,minmax(500px,1fr))] gap-6">
    <div class="p-4 bg-slate-800 rounded-md shadow-md shadow-slate-500/50">
      <h3 class="text-xl font-medium">Ingresos mensuales (últimos 6 meses)</h3>
      <div id="revenueLineChart"></div>
    </div>
    <div class="p-4 bg-slate-800 rounded-md shadow-md shadow-slate-500/50">
      <h3 class="text-xl font-medium">Ventas por categoría (último mes)</h3>
      <div id="categoryDoughnutChart"></div>
    </div>
    <div class="p-4 bg-slate-800 rounded-md shadow-md shadow-slate-500/50">
      <h3 class="text-xl font-medium">Top 5 productos más vendidos</h3>
      <div id="topProductsBarChart"></div>
    </div>
    <div class="p-4 bg-slate-800 rounded-md shadow-md shadow-slate-500/50">
      <h3 class="text-xl font-medium">Top 5 productos menos vendidos</h3>
      <div id="bottomProductsBarChart"></div>
    </div>
  </div>

  {{-- Tabla de pedidos recientes --}}
  <section class="mb-8 p-4 bg-slate-800/60 rounded-md shadow-md shadow-slate-500/50">
    <h3 class="mb-3 text-xl font-medium">📋 Últimos pedidos</h3>
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>Orden N°</th>
          <th class="hidden sm:table-cell">Cliente</th>
          <th>Total</th>
          <th class="hidden md:table-cell">Estado</th>
          <th>Fecha</th>
        </tr>
      </x-slot:thead>

      @forelse ($recentOrders as $order)
        <tr>
          <td>#{{ $order->id }}</td>
          <td class="hidden sm:table-cell">{{ $order->user->name ?? 'Anónimo' }}</td>
          <td>$ {{ number_format($order->total, 2, ',', '.') }}</td>
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
          <td>{{ $order->date->format('d/m/Y H:i') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center font-semibold text-slate-300">No hay pedidos recientes</td>
        </tr>
      @endforelse
    </x-tables.table>
  </section>

  {{-- Tablas de pedidos grandes y faltantes de pagos --}}
  <section class="p-4 mb-7 bg-slate-800/60 shadow-md shadow-slate-500/50">
    <h3 class="mb-3 text-xl font-semibold">Ordenes de mayor monto</h3>
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>Orden N°</th>
          <th class="hidden sm:table-cell">Cliente</th>
          <th>Total</th>
          <th class="hidden md:table-cell">Estado</th>
          <th>Fecha</th>
        </tr>
      </x-slot:thead>

      @forelse ($maxOrders as $order)
        <tr>
          <td>
            <x-buttons.link href="{{ route('orders.show', $order->id) }}"
              class="font-semibold text-purple-500 hover:text-purple-600">
              #{{ $order->id }}
            </x-buttons.link>
          </td>
          <td class="hidden sm:table-cell">
            <span>{{ $order->user->fullName() }}</span>
          </td>
          <td>
            <span class="me-px text-slate-300/80">$</span>
            {{ number_format($order->total, 2, ',', '.') }}
          </td>
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
          <td class="text-slate-300/80">{{ $order->date->format('d/m/Y H:i') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center font-semibold text-slate-300">No hay ordenes disponibles</td>
        </tr>
      @endforelse
    </x-tables.table>
  </section>
  <section class="p-4 mb-7 bg-slate-800/60 shadow-md shadow-slate-500/50">
    <h4 class="mb-3 text-xl font-semibold">Ordenes sin pagar</h4>
    <x-tables.table>
      <x-slot:thead>
        <tr class="text-left">
          <th>Orden N°</th>
          <th class="hidden sm:table-cell">Cliente</th>
          <th>Total</th>
          <th class="hidden md:table-cell">Estado</th>
          <th>Fecha</th>
        </tr>
      </x-slot:thead>

      @forelse ($nonPayedOrders as $order)
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
          <td class="hidden sm:table-cell">
            <span class="me-px text-slate-300/80">$</span>
            {{ number_format($order->total, 2, ',', '.') }}
          </td>
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
          <td class="text-slate-300/80">{{ $order->date->format('d/m/Y H:i') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center font-semibold text-slate-300">No hay ordenes sin pagar</td>
        </tr>
      @endforelse

      {{-- @if ($nonPayedOrders->isNotEmpty() && auth()->user()->can('manage orders'))
        <tr>
          <td colspan="5" class="text-center font-semibold text-slate-300">
            <form method="GET" action="{{ route('orders.filter') }}" class="w-full">
              @foreach ($statesNonPayed as $state)
                <input type="hidden" name="states[]" value="{{ $state }}">
              @endforeach
              <button type="submit" id="exportBtn"
                class="font-semibold text-purple-500 hover:text-purple-600 underline-offset-4 hover:underline cursor-pointer">
                Ver todos
              </button>
            </form>
          </td>
        </tr>
      @endif --}}
    </x-tables.table>
  </section>
@endsection
