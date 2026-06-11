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

    public function __construct(Collection $payments)
    {
        $this->payments = $payments;
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
            $payment->order->user->fullName() ?? 'Sin nombre',
            $payment->id,
            $payment->method,
            $payment->paymentProvider->name,
            $payment->order->date->format('d/m/Y H:i'),
            $payment->amount_formated,
            $payment->nro_fee,
            $payment->paymentState->code,
            $payment->date_formated,
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
}
