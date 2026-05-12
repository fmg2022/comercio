<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PDFController extends Controller
{
    public function generatePDFOrders(Order $order)
    {
        $comercio = (object) [
            'nombre' => config('app.name') . ' S.R.L.',
            'domicilio' => 'Av. Corrientes 1234, CABA',
            'condicion_iva' => 'Responsable Inscripto',
            'ingresos_brutos' => '123-456789-0',
            'fecha_inicio' => '15/03/1998',
            'horario_atencion' => 'Lunes a Sábado 8:00 a 22:00'
        ];

        $factura = (object) [
            'punto_venta' => '0001',
            'numero' => str_pad($order->id, 8, '0', STR_PAD_LEFT),
            'fecha_emision' => date('d/m/Y', strtotime($order->date)),
            'cliente_nombre' => $order->user->fullName(),
            'cliente_documento' => 'DNI 30.123.456',
            'cliente_domicilio' => $order->user->getCurrentAddress()->shortAddress(),
            'subtotal' => $order->total,
            'iva' => $order->total * (21 / 100), // 21% del subtotal
            'total' => $order->total * (100 + 21) / 100, // 100% del subtotal + 21% IVA
            'medio_pago' => $order->payment->payment_method,
            'cuotas' => $order->payment->nro_fee,
            'operacion_numero' => 'TRX-12345',
            'cae' => '12345678901234',
            'cae_vencimiento' => Carbon::parse($order->date)->addDays(10)->format('d/m/Y'),
            'items' => $order->products
        ];

        $pdf = Pdf::loadView('pdf.order', [
            'comercio' => $comercio,
            'factura' => $factura
        ]);

        // Configuración para factura
        $pdf->setPaper('a4', 'portrait');

        // Descargar o stream
        return $pdf->stream("Factura_B_{$factura->numero}.pdf");
    }
}
