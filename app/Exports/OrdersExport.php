<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;

class OrdersExport implements FromCollection, WithHeadings, WithProperties, WithMapping
{
    protected Collection $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
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
            $order->date->format('d/m/Y H:i'),
            number_format($order->total, 2, ',', '.'),
            $order->orderState->name ?? 'N/A',
            $order->total_products,
            $order->products->pluck('name')->implode(', ') ?? 'N/A',
            $order->user->fullName() ?? 'N/A',
            $order->address,
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
}
