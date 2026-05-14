<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;

class OrdersExport implements FromCollection, WithHeadings, WithProperties
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::with(['user', 'address', 'orderState:id,code', 'products'])->get()
            ->map(function ($order) {
                $totalItems = $order->products->sum('pivot.quantity');

                return [
                    'id' => $order->id,
                    'customer' => $order->user->fullName(),
                    'date' => $order->date_formated,
                    'total' => $order->total_formated,
                    'total_items' => $totalItems,
                    'state' => $order->orderState->code,
                    'address' => $order->address->shortAddress(),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Pedido',
            'Cliente',
            'Fecha',
            'Total',
            'Cantidad Productos',
            'Estado',
            'Dirección',
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
