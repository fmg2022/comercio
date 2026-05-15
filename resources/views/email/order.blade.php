<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Tu factura - Pedido #{{ $order->id }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
  <div style="max-width: 600px; margin: 0 auto; padding: 20px;">

    <h1 style="color: #333;">¡Gracias por tu compra!</h1>

    <p>Hola <strong>{{ $order->user->fullName() ?? 'cliente' }}</strong>,</p>

    <p>Tu pedido <strong>#{{ $order->id }}</strong> ha sido procesado correctamente.</p>

    <p>Adjunto a este correo encontrarás la <strong>factura en PDF</strong> con todos los detalles de tu compra.</p>

    <hr style="margin: 20px 0;">

    <h3>Resumen rápido:</h3>
    <ul>
      <li><strong>Fecha:</strong> {{ $order->date_formated }}</li>
      <li><strong>Total:</strong> ${{ number_format($order->total, 2, ',', '.') }}</li>
      <li><strong>Medio de pago:</strong> {{ $order->payment->payment_method }}</li>
      <li><strong>Artículos:</strong> {{ $order->totalProducts }}</li>
    </ul>

    <hr style="margin: 20px 0;">

    <p style="font-size: 12px; color: #666;">
      ¿Preguntas? Contáctanos en <a href="mailto:soporte@mitienda.com">soporte@mitienda.com</a>
    </p>

    <p>Saludos,<br>
      <strong>Equipo de {{ config('app.name') }}</strong>
    </p>
  </div>
</body>

</html>
