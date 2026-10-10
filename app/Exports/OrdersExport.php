<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;

class OrdersExport implements FromCollection, WithHeadings, WithProperties, WithMapping
{
    protected Collection $orders;

    public function __construct(array $filters)
    {
        $this->orders = $this->getOrdersFilters($filters);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Método de pago',
            'Fecha',
            'Total',
            'Estado',
            'Cantidad Productos',
            'Productos',
            'Cliente',
            'Dirección',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->payment?->paymentProvider?->name ?? 'N/A',
            $order->date->format('d/m/Y H:i'),
            number_format($order->total, 2, ',', '.'),
            $order->orderState->name ?? 'N/A',
            $order->total_products,
            $order->products->pluck('name')->implode(', ') ?? 'N/A',
            $order->user->fullName() ?? 'N/A',
            $order->shipping->address?->street ?? 'N/A',
        ];
    }

    public function properties(): array
    {
        return [
            'title'       => 'Ordenes',
            'description' => 'Exportación de las ordenes registradas',
            'subject'     => 'Ordenes',
            'keywords'    => 'ordenes, exportación',
            'category'    => 'Ordenes',
        ];
    }

    private function getOrdersFilters(array $filters): Collection
    {
        return Order::query()
            ->with(['user:id,name,surname', 'orderState:id,name', 'products', 'payment.paymentProvider:id,name'])
            ->when(isset($filters['users']), fn($query) => $query->whereIn('user_id', $filters['users']))
            ->when(isset($filters['states']), fn($query) => $query->whereIn('order_state_id', $filters['states']))
            ->when(isset($filters['providers']), fn($query) => $query->whereHas('payment.paymentProvider', fn($q) => $q->whereIn('id', $filters['providers'])))
            ->when(isset($filters['date_from']), fn($query) => $query->where('date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn($query) => $query->where('date', '<=', $filters['date_to']))
            ->when(isset($filters['total_from']), fn($query) => $query->where('total', '>=', $filters['total_from']))
            ->when(isset($filters['total_to']), fn($query) => $query->where('total', '<=', $filters['total_to']))
            ->orderByDesc('date')
            ->get();
    }
}
