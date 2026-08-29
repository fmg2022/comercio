<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{
    public function generateOrderPDF(Order $order)
    {
        $data = $this->prepareData($order);

        return Pdf::loadView('pdf.order', $data);
    }

    private function prepareData(Order $order)
    {
        $comercio = (object) [
            'nombre' => config('app.name'),
            'cuit' => config('app_settings.cuit'),
            'domicilio' => config('app_settings.address'),
            'condicion_iva' => config('app_settings.iva_condition'),
            'ingresos_brutos' => config('app_settings.gross_income'),
            'fecha_inicio' => date('d/m/Y', strtotime(config('commerce.start_date'))),
            'horario_atencion' => config('app_settings.pickup_hours'),
        ];

        $branches = json_decode(config('app_settings.branches'), true) ?? [];
        $iva = (float) config('commerce.tax_rate');

        $factura = (object) [
            'punto_venta' => $branches[0]['nro'] ?? '0001',
            'numero' => str_pad($order->id, 8, '0', STR_PAD_LEFT),
            'fecha_emision' => $order->date->format('Y-m-d'),
            'cliente_nombre' => $order->user->fullName(),
            'cliente_tipo_doc' => 'DNI',
            'cliente_documento' => $order->user->dni,
            'cliente_domicilio' => $order->shipping->address?->street_1 ?? '',
            'cliente_telefono' => $order->user->phone,
            'subtotal' => $order->subtotal,
            'iva' => $order->total * ($iva / 100),
            'total' => $order->total * (100 + $iva) / 100,
            'medio_pago' => $order->payment->payment_method,
            'cuotas' => $order->payment->nro_fee,
            'operacion_numero' => 'TRX-12345',
            'cae' => '12345678901234', // Obtener valor de la SDK de ARCA
            'cae_vencimiento' => $order->date->addDays(10)->format('d/m/Y'), // Obtener valor de la SDK de ARCA
            'qr_base64' => null, // Obtener valor de la SDK de ARCA
            'items' => $order->products
        ];

        return [
            'comercio' => $comercio,
            'factura' => $factura
        ];
    }
}
