<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use App\Models\OrderState;
use App\Models\UserSessionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(30)->startOfDay();

        // Si se envía un rango personalizado
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        }

        // Período anterior (misma duración)
        $daysDiff = $startDate->diffInDays($endDate) + 1;
        $prevStart = (clone $startDate)->subDays($daysDiff);
        $prevEnd = (clone $endDate)->subDays($daysDiff);

        // ------------------------------
        // 1. KPIs principales
        // ------------------------------

        // Ingresos totales (órdenes completadas/entregadas)
        $revenueData = $this->getRevenue($startDate, $endDate, $prevStart, $prevEnd);

        // Número de pedidos completados
        $ordersData = $this->getOrdersCount($startDate, $endDate, $prevStart, $prevEnd);

        // Valor medio del carrito (AOV)
        $aovData = $this->getAOV($startDate, $endDate, $prevStart, $prevEnd);

        // Tasa de conversión (pedidos / usuarios únicos que visitaron)
        $conversionData = $this->getConversionRate($startDate, $endDate, $prevStart, $prevEnd);

        // Customer Lifetime Value (LTV) - promedio de ingresos por cliente desde su primer pedido
        $ltv = $this->getLTV();

        // ------------------------------
        // 2. Gráficos
        // ------------------------------

        // Ventas mensuales últimos 6 meses (línea)
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        $monthlySales = $this->getMonthlyRevenue($sixMonthsAgo, Carbon::now()->endOfMonth());

        // Top 5 productos más vendidos (cantidad)
        $topProducts = $this->getTopProducts($startDate, $endDate, 5);

        // Bottom 5 productos menos vendidos (incluye cero ventas)
        $bottomProducts = $this->getBottomProducts($startDate, $endDate, 5);

        // Ventas por categoría (dona) - último mes
        $categorySales = $this->getSalesByCategory($startDate, $endDate);

        // ------------------------------
        // 3. Tablas auxiliares
        // ------------------------------
        $recentOrders = $this->getRecentOrders(10);

        // ------------------------------
        // 4. Tablas de pedidos
        // ------------------------------
        $maxOrders = $this->getBestOrders($startDate, $endDate, 5);
        $nonPayedOrders = $this->getIncompleteOrders($startDate, $endDate, 5);

        return view('pages.dashboard.adminIndex', compact(
            'startDate',
            'endDate',
            'revenueData',
            'ordersData',
            'aovData',
            'conversionData',
            'ltv',
            'monthlySales',
            'topProducts',
            'bottomProducts',
            'categorySales',
            'recentOrders',
            'maxOrders',
            'nonPayedOrders'
        ));
    }

    // --- Métodos privados para cálculos ---

    private function getRevenue(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd)
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
        $uniqueCustomers = Order::whereIn('order_state_id', $this->completedStateIds())
            ->distinct('user_id')
            ->count('user_id');

        if ($uniqueCustomers == 0) return 0;
        return round($totalRevenue / $uniqueCustomers, 2);
    }

    /**
     * Ingresos mensuales para gráfico de líneas
     */
    private function getMonthlyRevenue(Carbon $start, Carbon $end): array
    {
        $results = Order::whereIn('order_state_id', $this->completedStateIds())
            ->whereBetween('date', [$start, $end])
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $results->pluck('month'),
            'values' => $results->pluck('revenue')
        ];
    }

    /**
     * Top N productos por cantidad vendida
     */
    private function getTopProducts(Carbon $start, Carbon $end, int $limit = 5): Collection
    {
        return Product::query()
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->whereIn('orders.order_state_id', $this->completedStateIds())
            ->whereBetween('orders.date', [$start, $end])
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_product.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    /**
     * Bottom N productos (incluye los que no se vendieron)
     */
    private function getBottomProducts(Carbon $start, Carbon $end, int $limit = 5): Collection
    {
        // Subconsulta para obtener ventas por producto en el período
        $salesSubquery = Order::query()
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->whereIn('orders.order_state_id', $this->completedStateIds())
            ->whereBetween('orders.date', [$start, $end])
            ->select(
                'order_product.product_id',
                DB::raw('SUM(order_product.quantity) as total_sold')
            )
            ->groupBy('order_product.product_id');

        $products = Product::leftJoinSub($salesSubquery, 'sales', 'products.id', '=', 'sales.product_id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('COALESCE(sales.total_sold, 0) as total_sold')
            )
            ->orderBy('total_sold')
            ->limit($limit)
            ->get();

        return $products;
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
            ->select(
                'categories.name as category',
                DB::raw('SUM(order_product.quantity * order_product.price) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        return [
            'labels' => $results->pluck('category'),
            'values' => $results->pluck('total_revenue')
        ];
    }

    private function getRecentOrders(int $limit = 10): Collection
    {
        return Order::with(['user:id,name,surname', 'orderState:id,code'])
            ->orderByDesc('date')
            ->limit($limit)
            ->get();
    }

    private function getBestOrders(Carbon $start, Carbon $end, int $limit = 5): Collection
    {
        return Order::with(['user:id,name,surname', 'orderState:id,code'])
            ->whereBetween('orders.date', [$start, $end])
            ->whereIn('order_state_id', $this->completedStateIds())
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    private function getIncompleteOrders(Carbon $start, Carbon $end, int $limit = 5): Collection
    {
        return Order::with(['user:id,name,surname', 'orderState:id,code'])
            ->whereBetween('orders.date', [$start, $end])
            ->whereIn('order_state_id', OrderState::whereIn('code', ['CREADO', 'PENDIENTE'])->pluck('id'))
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Devuelve array de ids de estados considerados "completados" (entregados o pagados)
     */
    private function completedStateIds()
    {
        // Cachear para no consultar cada vez
        return Cache::remember('completed_order_state_ids', 3600, function () {
            return OrderState::whereIn('code', ['COMPLETO', 'PAGADO', 'ENTREGADO'])->pluck('id')->toArray();
        });
    }

    private function calculateChange(float $current, float $previous): float
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
}
