<?php

use App\Models\{Cart, Order, OrderState, Product, UserSessionHistory};
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Cache, DB};
use Livewire\Attributes\{Computed, Layout, Url, Validate};
use Livewire\Component;

new #[Layout('layouts::dashboard')] class extends Component {
    #[Url]
    #[Validate('required|date')]
    public string $startDate;
    #[Url]
    #[Validate('required|date')]
    public string $endDate;

    public array $revenueData = [],
        $ordersData = [],
        $aovData = [],
        $conversionData = [],
        $monthlySales = [],
        $categorySales = [],
        $topProducts = [],
        $bottomProducts = [];
    public float $ltv = 0;

    #[Url]
    public int $selectedPeriod = 6;
    #[Url]
    public int $recentOrdersLimit = 10;

    public function mount()
    {
        if (empty($this->startDate)) {
            $this->startDate = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
        }
        if (empty($this->endDate)) {
            $this->endDate = Carbon::now()->endOfDay()->format('Y-m-d');
        }

        $this->loadData();
        $this->updatedSelectedPeriod($this->selectedPeriod);
    }

    public function updatedStartDate($value)
    {
        $start = Carbon::parse($value);
        $end = Carbon::parse($this->endDate);

        if ($start->greaterThan($end)) {
            $this->endDate = $start->format('Y-m-d');
            $this->addError('startDate', 'La "Fecha desde" debe ser anterior o igual a la "Fecha hasta".');
            return;
        }

        $this->loadData();
    }
    public function updatedEndDate($value)
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($value);

        if ($end->lessThan($start)) {
            $this->startDate = $end->format('Y-m-d');
            $this->addError('endDate', 'La "Fecha hasta" debe ser posterior o igual a la "Fecha desde".');
            return;
        }

        $this->loadData();
    }

    public function resetFilter()
    {
        $this->startDate = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfDay()->format('Y-m-d');
        $this->loadData();
    }

    #[Computed]
    public function recentOrders(): Collection
    {
        return Order::with(['user:id,name,surname', 'orderState:id,name'])
            ->orderByDesc('date')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->limit($this->recentOrdersLimit)
            ->get();
    }

    #[Computed]
    public function maxOrders(int $limit = 5): Collection
    {
        return Order::with(['user:id,name,surname', 'orderState:id,name'])
            ->whereBetween('orders.date', [$this->startDate, $this->endDate])
            ->whereIn('order_state_id', $this->completedStateIds())
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    #[Computed]
    public function nonPayedOrders(int $limit = 5): Collection
    {
        return Order::with(['user:id,name,surname', 'orderState:id,name'])
            ->whereBetween('orders.date', [$this->startDate, $this->endDate])
            ->whereIn('order_state_id', OrderState::where('slug', 'pending')->pluck('id'))
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    public function updatedSelectedPeriod($value)
    {
        $period = Carbon::now()->subMonths($this->selectedPeriod)->startOfMonth();
        $this->monthlySales = $this->getMonthlyRevenue($period, Carbon::now()->endOfMonth());

        $this->dispatch('monthly-sales-updated', [
            'monthlySales' => $this->monthlySales,
        ]);
    }

    public function loadData()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // Período anterior (misma duración)
        $daysDiff = $start->diffInDays($end) + 1;
        $prevStart = (clone $start)->subDays($daysDiff);
        $prevEnd = (clone $end)->subDays($daysDiff);

        // ------------------------------
        // 1. KPIs principales
        // ------------------------------

        // Ingresos totales (órdenes completadas/entregadas)
        $this->revenueData = $this->getRevenue($start, $end, $prevStart, $prevEnd);

        // Número de pedidos completados
        $this->ordersData = $this->getOrdersCount($start, $end, $prevStart, $prevEnd);

        // Valor medio del carrito (AOV)
        $this->aovData = $this->getAOV($start, $end, $prevStart, $prevEnd);

        // Tasa de conversión (pedidos / usuarios únicos que visitaron)
        $this->conversionData = $this->getConversionRate($start, $end, $prevStart, $prevEnd);

        // Customer Lifetime Value (LTV) - promedio de ingresos por cliente desde su primer pedido
        $this->ltv = $this->getLTV();

        // ------------------------------
        // 2. Gráficos
        // ------------------------------
        // Top 5 productos más vendidos (cantidad)
        $this->topProducts = $this->getTopProducts($start, $end, 5);

        // Bottom 5 productos menos vendidos (incluye cero ventas)
        $this->bottomProducts = $this->getBottomProducts($start, $end, 5);

        // Ventas por categoría (dona) - último mes
        $this->categorySales = $this->getSalesByCategory($start, $end);

        // $nonPayedOrders = $this->getIncompleteOrders($start, $end, 5);

        $this->dispatch('dashboard-updated', [
            'categorySales' => $this->categorySales,
            'topProducts' => $this->topProducts,
            'bottomProducts' => $this->bottomProducts,
        ]);
    }

    private function getRevenue(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd): array
    {
        $current = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$start, $end])
            ->sum('total');

        $previous = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->sum('total');

        $change = $this->calculateChange($current, $previous);

        return ['current' => $current, 'previous' => $previous, 'change' => $change];
    }

    private function getOrdersCount(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd)
    {
        $current = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$start, $end])
            ->count();

        $previous = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->count();

        $change = $this->calculateChange($current, $previous);

        return ['current' => $current, 'previous' => $previous, 'change' => $change];
    }

    private function getAOV(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd)
    {
        $currentRevenue = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$start, $end])
            ->sum('total');
        $currentOrders = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$start, $end])
            ->count();
        $currentAOV = $currentOrders > 0 ? $currentRevenue / $currentOrders : 0;

        $prevRevenue = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->sum('total');
        $prevOrders = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->count();
        $prevAOV = $prevOrders > 0 ? $prevRevenue / $prevOrders : 0;

        $change = $this->calculateChange($currentAOV, $prevAOV);

        return ['current' => round($currentAOV, 2), 'previous' => round($prevAOV, 2), 'change' => $change];
    }

    private function getConversionRate(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd)
    {
        $buyersCurrent = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$start, $end])
            ->distinct('user_id')
            ->count('user_id');

        $activeUsersCurrent = UserSessionHistory::whereBetween('last_activity', [$start->timestamp, $end->timestamp])
            ->distinct('user_id')
            ->count('user_id');

        // Si no hay sesiones, usa usuarios con carrito
        if ($activeUsersCurrent == 0) {
            $activeUsersCurrent = Cart::whereBetween('updated_at', [$start, $end])
                ->distinct('user_id')
                ->count('user_id');
        }

        $currentCVR = $activeUsersCurrent > 0 ? ($buyersCurrent / $activeUsersCurrent) * 100 : 0;

        // Período anterior
        $buyersPrev = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$prevStart, $prevEnd])
            ->distinct('user_id')
            ->count('user_id');

        $activeUsersPrev = UserSessionHistory::whereBetween('last_activity', [$prevStart->timestamp, $prevEnd->timestamp])
            ->distinct('user_id')
            ->count('user_id');

        if ($activeUsersPrev == 0) {
            $activeUsersPrev = Cart::whereBetween('updated_at', [$prevStart, $prevEnd])
                ->distinct('user_id')
                ->count('user_id');
        }

        $prevCVR = $activeUsersPrev > 0 ? ($buyersPrev / $activeUsersPrev) * 100 : 0;
        $change = $this->calculateChange($currentCVR, $prevCVR);

        return ['current' => round($currentCVR, 2), 'previous' => round($prevCVR, 2), 'change' => $change];
    }

    /**
     * LTV simple: ingreso total de todos los pedidos completados / número de clientes únicos
     * (desde que existe la tienda)
     */
    private function getLTV(): float
    {
        $totalRevenue = Order::whereIn('order_state_id', $this->completedStateIds())->sum('total');
        $uniqueCustomers = Order::whereIn('order_state_id', $this->completedStateIds())->distinct('user_id')->count('user_id');

        if ($uniqueCustomers == 0) {
            return 0;
        }
        return round($totalRevenue / $uniqueCustomers, 2);
    }

    /**
     * Ingresos mensuales para gráfico de líneas
     */
    private function getMonthlyRevenue(Carbon $start, Carbon $end): array
    {
        $results = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$start, $end])
            ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('SUM(total) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $results->pluck('month'),
            'values' => $results->pluck('revenue'),
        ];
    }

    /**
     * Top N productos por cantidad vendida
     */
    private function getTopProducts(Carbon $start, Carbon $end, int $limit = 5): array
    {
        return Product::query()
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->whereIn('orders.order_state_id', $this->completedStateIds())
            ->whereBetween('orders.date', [$start, $end])
            ->select('products.id', 'products.name', DB::raw('SUM(order_product.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Bottom N productos (incluye los que no se vendieron)
     */
    private function getBottomProducts(Carbon $start, Carbon $end, int $limit = 5): array
    {
        // Subconsulta para obtener ventas por producto en el período
        $salesSubquery = Order::query()
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->whereIn('orders.order_state_id', $this->completedStateIds())
            ->whereBetween('orders.date', [$start, $end])
            ->select('order_product.product_id', DB::raw('SUM(order_product.quantity) as total_sold'))
            ->groupBy('order_product.product_id');

        return Product::leftJoinSub($salesSubquery, 'sales', 'products.id', '=', 'sales.product_id')->select('products.id', 'products.name', DB::raw('COALESCE(sales.total_sold, 0) as total_sold'))->orderBy('total_sold')->limit($limit)->get()->toArray();
    }

    /**
     * Ventas por categoría para gráfico de dona
     */
    private function getSalesByCategory(Carbon $start, Carbon $end): array
    {
        $results = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->whereIn('orders.order_state_id', $this->completedStateIds())
            ->whereBetween('orders.date', [$start, $end])
            ->select('categories.name as category', DB::raw('SUM(order_product.quantity * order_product.price) as total_revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        return [
            'labels' => $results->pluck('category'),
            'values' => $results->pluck('total_revenue'),
        ];
    }

    /**
     * Devuelve array de ids de estados considerados "completados" (completados o pagados)
     */
    private function completedStateIds()
    {
        // Cachear para no consultar cada vez
        return Cache::remember('completed_order_state_ids', 3600, function () {
            return OrderState::whereIn('slug', ['completed', 'paid'])
                ->pluck('id')
                ->toArray();
        });
    }

    private function calculateChange(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }
};
?>

<div>
  <x-sections.headerTitle>
    <x-slot:textTitle>📊 Panel de Administración - {{ config('app.name') }}</x-slot:textTitle>
  </x-sections.headerTitle>

  @island(name: 'date-filter')
    {{-- Filtro de fechas --}}
    <div
      class="sticky top-18 z-20 p-5 mb-7 flex items-center gap-6 flex-wrap bg-slate-800 rounded-lg shadow-md shadow-slate-500/60">
      <div class="relative">
        <label for="date_from" class="block mb-1 text-sm font-medium">Fecha desde:</label>
        <input type="date" id="date_from" wire:model.live.debounce.250ms="startDate"
          class="w-36 px-2 py-1 border border-slate-500 rounded-md outline-none" required>
        <div wire:loading wire:target="startDate, resetFilter" wire:loading.flex value="{{ $startDate }}"
          class="absolute left-0 top-6 w-full h-8.5 items-center justify-center bg-slate-400/40 rounded-md">
          <x-icons.animate.spinner class="size-5 text-purple-500" />
        </div>
      </div>
      <div class="relative">
        <label for="date_to" class="block mb-1 text-sm font-medium">Fecha hasta:</label>
        <input type="date" id="date_to" min="{{ $startDate }}" wire:model.live.debounce.250ms="endDate"
          class="w-36 px-2 py-1 border border-slate-500 rounded-md outline-none" required>
        <div wire:loading wire:target="endDate, resetFilter" wire:loading.flex value="{{ $endDate }}"
          class="absolute left-0 top-6 w-full h-8.5 items-center justify-center bg-slate-400/40 rounded-md">
          <x-icons.animate.spinner class="size-5 text-purple-500" />
        </div>
      </div>
      <button wire:click="resetFilter" class="px-3 py-2 font-semibold bg-slate-600 rounded-lg hover:bg-slate-500">
        Resetear</button>
      @error('endDate')
        <span class="p-4 text-sm font-semibold border-s-4 border-red-600 bg-red-800 rounded-e-md" x-data="{ show: true }"
          x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.duration.300ms>{{ $message }}</span>
      @enderror
      @error('startDate')
        <span class="p-4 text-sm font-semibold border-s-4 border-red-600 bg-red-800 rounded-e-md" x-data="{ show: true }"
          x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.duration.300ms>{{ $message }}</span>
      @enderror
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
  @endisland

  {{-- Gráficos --}}
  <div class="mb-8 grid grid-cols-[repeat(auto-fit,minmax(500px,1fr))] gap-6">
    @island(name: 'monthlySales')
      <div class="p-4 bg-slate-800 rounded-lg shadow-md shadow-slate-500/50">
        <div class="mb-3 flex justify-between items-center">
          <h3 class="text-xl font-medium">Ingresos mensuales (últimos {{ $selectedPeriod }} meses)</h3>
          <select name="date-filter-period" wire:model.live="selectedPeriod"
            class="ps-3 pe-2 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 transition duration-150 ease-in-out">
            <option value="4">4 meses</option>
            <option value="6" selected>6 meses</option>
            <option value="12">1 año</option>
          </select>
        </div>
        <div id="revenueLineChart"></div>
      </div>
    @endisland
    <div class="p-4 bg-slate-800 rounded-lg shadow-md shadow-slate-500/50">
      <h3 class="mb-3 text-xl font-medium">Ventas por categoría (último mes)</h3>
      <div id="categoryDoughnutChart"></div>
    </div>
    <div class="p-4 bg-slate-800 rounded-lg shadow-md shadow-slate-500/50">
      <h3 class="mb-3 text-xl font-medium">Top 5 productos más vendidos</h3>
      <div id="topProductsBarChart"></div>
    </div>
    <div class="p-4 bg-slate-800 rounded-lg shadow-md shadow-slate-500/50">
      <h3 class="mb-3 text-xl font-medium">Top 5 productos menos vendidos</h3>
      <div id="bottomProductsBarChart"></div>
    </div>
  </div>

  @island(name: 'date-filter')
    <div>
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
          <x-slot:tbody>
            @forelse ($this->maxOrders as $order)
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
                      'text-cyan-400' => $order->orderState->slug === 'paid',
                      'text-green-400' => $order->orderState->slug === 'completed',
                  ])>
                    {{ $order->orderState->name }}
                  </span>
                </td>
                <td class="text-slate-300/80">{{ $order->date->format('d/m/Y H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center font-semibold text-slate-300">No hay ordenes disponibles</td>
              </tr>
            @endforelse
          </x-slot:tbody>
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

          <x-slot:tbody>
            @forelse ($this->nonPayedOrders as $order)
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
                  <span class="text-blue-400 font-semibold before:content-['●'] before:me-px">
                    {{ $order->orderState->name }}
                  </span>
                </td>
                <td class="text-slate-300/80">{{ $order->date->format('d/m/Y H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center font-semibold text-slate-300">No hay ordenes sin pagar</td>
              </tr>
            @endforelse
          </x-slot:tbody>
        </x-tables.table>
      </section>
      {{-- Tabla de pedidos recientes --}}
      <section class="mb-8 p-4 bg-slate-800/60 rounded-lg shadow-md shadow-slate-500/50">
        <div class="flex justify-between items-center mb-3">
          <h3 class="text-xl font-medium">📋 Últimos pedidos</h3>
          <select wire:model.live="recentOrdersLimit"
            class="ps-3 pe-2 py-2 bg-slate-700 border border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 transition duration-150 ease-in-out">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
          </select>
        </div>
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

          <x-slot:tbody>
            @forelse ($this->recentOrders as $order)
              <tr>
                <td>#{{ $order->id }}</td>
                <td class="hidden sm:table-cell">{{ $order->user->name ?? 'Anónimo' }}</td>
                <td>$ {{ number_format($order->total, 2, ',', '.') }}</td>
                <td class="hidden md:table-cell">
                  <span @class([
                      "font-semibold before:content-['●'] before:me-px",
                      'text-blue-400' => $order->orderState->slug === 'pending',
                      'text-cyan-400' => $order->orderState->slug === 'paid',
                      'text-green-400' => $order->orderState->slug === 'completed',
                      'text-purple-400' => $order->orderState->slug === 'refunded',
                      'text-red-400' => $order->orderState->slug === 'cancelled',
                  ])>
                    {{ $order->orderState->name }}
                  </span>
                </td>
                <td>{{ $order->date->format('d/m/Y H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center font-semibold text-slate-300">No hay pedidos recientes</td>
              </tr>
            @endforelse
          </x-slot:tbody>
        </x-tables.table>
      </section>
    </div>
  @endisland
</div>

@push('scripts-dashboard')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    // ----- FUNCIONES DE RENDERIZADO (reutilizables) -----

    let revenueChart = null,
      categoryChart = null,
      topChart = null,
      bottomChart = null;

    function renderRevenueChart(labels, values) {
      if (revenueChart) {
        revenueChart.updateOptions({
          xaxis: {
            categories: labels
          }
        });
        revenueChart.updateSeries([{
          name: 'Ingresos ($)',
          data: values
        }]);
        return;
      }
      const options = {
        ...darkThemeBase,
        series: [{
          name: 'Ingresos ($)',
          data: values
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
        labels: labels,
        colors: ['#60a5fa'],
        xaxis: {
          ...darkThemeBase.xaxis,
          categories: labels,
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
        },
      };
      revenueChart = new ApexCharts(document.querySelector("#revenueLineChart"), options);
      revenueChart.render();
    }

    function renderCategoryChart(labels, values) {
      const numericValues = values.map(v => parseFloat(v));
      const totalSum = numericValues.reduce((acc, val) => acc + val, 0);

      if (categoryChart) {
        categoryChart.updateOptions({
          labels: labels,
          plotOptions: {
            pie: {
              donut: {
                labels: {
                  total: {
                    formatter: () => totalSum.toLocaleString('es-AR', {
                      style: 'currency',
                      currency: 'ARS'
                    })
                  }
                }
              }
            }
          }
        });
        categoryChart.updateSeries(numericValues);
        return;
      }

      const options = {
        series: numericValues,
        chart: {
          type: 'donut',
          height: 450,
          background: 'transparent',
          toolbar: {
            show: false
          }
        },
        labels: labels,
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
                  formatter: () => totalSum.toLocaleString('es-AR', {
                    style: 'currency',
                    currency: 'ARS'
                  }),
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
      categoryChart = new ApexCharts(document.querySelector("#categoryDoughnutChart"), options);
      categoryChart.render();
    }

    function renderTopProductsChart(products) {
      let productNames = products.map(p => p.name),
        productData = products.map(p => p.total_sold);

      if (topChart) {
        topChart.updateOptions({
          xaxis: {
            categories: productNames
          }
        });
        topChart.updateSeries([{
          name: 'Unidades vendidas',
          data: productData
        }]);
        return;
      }

      const options = {
        ...darkThemeBase,
        series: [{
          name: 'Unidades vendidas',
          data: productData
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
          categories: productNames,
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
      topChart = new ApexCharts(document.querySelector("#topProductsBarChart"), options);
      topChart.render();
    }

    function renderBottomProductsChart(products) {
      const productNames = products.map(p => p.name);
      const productData = products.map(p => p.total_sold);

      if (bottomChart) {
        bottomChart.updateOptions({
          xaxis: {
            categories: productNames
          }
        });
        bottomChart.updateSeries([{
          name: 'Unidades vendidas',
          data: productData
        }]);
        return;
      }

      const options = {
        ...darkThemeBase,
        series: [{
          name: 'Unidades vendidas',
          data: productData
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
          categories: productNames,
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
      bottomChart = new ApexCharts(document.querySelector("#bottomProductsBarChart"), options);
      bottomChart.render();
    }

    // ----- THEME COMPARTIDO -----
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

    // ----- ACTUALIZADOR PRINCIPAL -----
    function updateDashboard(data) {
      if (data.categorySales) {
        renderCategoryChart(data.categorySales.labels, data.categorySales.values);
      }
      if (data.topProducts) {
        renderTopProductsChart(data.topProducts);
      }
      if (data.bottomProducts) {
        renderBottomProductsChart(data.bottomProducts);
      }
    }

    function updatePeriod(data) {
      if (data) {
        renderRevenueChart(data.labels, data.values);
      }
    }

    document.addEventListener('livewire:initialized', () => {
      const initialData = {
        categorySales: {
          labels: @json($categorySales['labels']),
          values: @json($categorySales['values'])
        },
        topProducts: @json($topProducts),
        bottomProducts: @json($bottomProducts)
      };
      const monthlySales = {
        labels: @json($monthlySales['labels']),
        values: @json($monthlySales['values'])
      };

      updateDashboard(initialData);
      updatePeriod(monthlySales);
    });

    // ----- ESCUCHA DE EVENTOS DE LIVEWIRE -----
    Livewire.on('dashboard-updated', (data) => {
      updateDashboard(data[0]);
    });

    Livewire.on('monthly-sales-updated', (data) => {
      updatePeriod(data[0].monthlySales);
    });
  </script>
@endpush
