<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;

class PaymentsExport implements FromCollection, WithHeadings, WithProperties
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Payment::with(['order:id,date', 'paymentState:id,code', 'paymentProvider:id,name'])->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'provider_state' => $payment->provider_state,
                    'method' => $payment->method,
                    'provider' => $payment->paymentProvider->name,
                    'order_date' => $payment->order->date_formated,
                    'amount' => $payment->amount_formated,
                    'nro_fee' => $payment->nro_fee,
                    'payment_state_code' => $payment->paymentState->code,
                    'date' => $payment->date_formated,
                    'checkout_url' => $payment->checkout_url,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Pago',
            'ID Transacción',
            'Estado Proveedor',
            'Método de pago',
            'Proveedor',
            'Fecha de orden',
            'Monto',
            'N° Cuota',
            'Estado',
            'Fecha de pago',
            'URL de pago',
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
