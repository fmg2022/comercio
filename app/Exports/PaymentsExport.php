<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;

class PaymentsExport implements FromCollection, WithHeadings, WithProperties, WithMapping
{
    protected Collection $payments;

    public function __construct(array $filters)
    {
        $this->payments = $this->getPaymentsFilters($filters);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'ID Pago',
            'Método de pago',
            'Proveedor',
            'Fecha de orden',
            'Monto',
            'N° Cuota',
            'Estado',
            'Fecha de pago',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->order->user?->fullName() ?? 'Sin nombre',
            $payment->id,
            $payment->method,
            $payment->paymentProvider?->name ?? 'N/A',
            $payment->order->date->format('d/m/Y H:i'),
            $payment->amount_formated,
            $payment->nro_fee,
            $payment->paymentState?->name ?? 'N/A',
            $payment->paid_at->format('d/m/Y H:i'),
        ];
    }

    public function properties(): array
    {
        return [
            'title'       => 'Pagos',
            'description' => 'Exportación de los pagos registrados',
            'subject'     => 'Pagos',
            'keywords'    => 'pagos, exportación',
            'category'    => 'Pagos',
        ];
    }

    private function getPaymentsFilters(array $filters): Collection
    {
        return Payment::query()
            ->with(['order.user:id,name,surname', 'paymentState:id,name', 'paymentProvider:id,name'])
            ->when(isset($filters['users']), fn($query) => $query->whereHas('order.user', fn($q) => $q->whereIn('id', $filters['users'])))
            ->when(isset($filters['states']), fn($query) => $query->whereIn('payment_state_id', $filters['states']))
            ->when(isset($filters['providers']), fn($query) => $query->whereIn('payment_provider_id', $filters['providers']))
            ->when(isset($filters['date_from']), fn($query) => $query->where('paid_at', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn($query) => $query->where('paid_at', '<=', $filters['date_to']))
            ->when(isset($filters['total_from']), fn($query) => $query->where('amount', '>=', $filters['total_from']))
            ->when(isset($filters['total_to']), fn($query) => $query->where('amount', '<=', $filters['total_to']))
            ->orderByDesc('paid_at')
            ->get();
    }
}
