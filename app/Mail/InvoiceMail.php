<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\PDFService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public PDFService $pdfService;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->pdfService = app(PDFService::class);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu factura adjunta - Pedido #' . $this->order->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.order',
            with: ['order' => $this->order],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = $this->pdfService->generateOrderPDF($this->order);
        return [
            Attachment::fromData(fn() => $pdf->output(), 'Factura_B_' . str_pad($this->order->id, 8, '0', STR_PAD_LEFT) . '.pdf')
                ->withMime('application/pdf')
        ];
    }
}
