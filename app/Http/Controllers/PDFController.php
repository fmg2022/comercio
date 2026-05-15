<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PDFService;

class PDFController extends Controller
{
    protected PDFService $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function generatePDFOrders(Order $order)
    {
        $pdf = $this->pdfService->generateOrderPDF($order);

        // Configuración para factura
        $pdf->setPaper('a4', 'portrait');

        // Descargar o stream
        return $pdf->stream("Factura_B_" . str_pad($order->id, 8, '0', STR_PAD_LEFT) . ".pdf");
    }
}
