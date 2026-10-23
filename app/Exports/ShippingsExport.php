<?php

namespace App\Exports;

use App\Models\Shipping;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ShippingsExport implements FromCollection
{
    protected Collection $shippings;

    public function __construct(array $filters)
    {
        $this->shippings = $this->getShippingsFilters($filters);
    }

    private function getShippingsFilters(array $filters): Collection
    {
        return Shipping::query()
            ->with(['shippingState:id,name', 'address:id,street', 'transportUser:id,name'])
            ->when(isset($filters['users']), fn($query) => $query->whereIn('transport_user_id', $filters['users']))
            ->when(isset($filters['states']), fn($query) => $query->whereIn('shipping_state_id', $filters['states']))
            ->when(isset($filters['date_from']), fn($query) => $query->where('estimated_delivery_date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn($query) => $query->where('estimated_delivery_date', '<=', $filters['date_to']))
            ->when(isset($filters['date_delivered_from']), fn($query) => $query->where('delivered_to', '>=', $filters['date_delivered_from']))
            ->when(isset($filters['date_delivered_to']), fn($query) => $query->where('delivered_to', '<=', $filters['date_delivered_to']))
            ->when(isset($filters['delivered_null']), fn($query) => $query->whereNull('delivered_to'))
            ->orderByDesc('estimated_delivery_date')
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->shippings;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Orden ID',
            'Dirección',
            'Transportista',
            'Fecha Estimada',
            'Fecha Entrega',
            'N° Seguimiento',
            'Distancia',
            'Costo',
            'Estado',
            'Notas',
        ];
    }

    public function map(Shipping $shipping): array
    {
        return [
            $shipping->id,
            $shipping->order_id,
            $shipping->address?->street ?? 'N/A',
            $shipping->transportUser?->fullName() ?? 'N/A',
            $shipping->estimated_delivery_date?->format('d/m/Y H:i') ?? 'N/A',
            $shipping->delivered_to?->format('d/m/Y H:i') ?? 'N/A',
            $shipping->tracking_number ?? 'N/A',
            $shipping->distance_km ?? 'N/A',
            $shipping->cost ?? 'N/A',
            $shipping->shippingState?->name ?? 'N/A',
            $shipping->notes ?? 'N/A',
        ];
    }

    public function properties(): array
    {
        return [
            'title'       => 'Envíos',
            'description' => 'Exportación de los envíos registrados',
            'subject'     => 'Envíos',
            'keywords'    => 'envíos, exportación',
            'category'    => 'Envíos',
        ];
    }
}
