<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tu pedido #{{ $order->id }} está listo para retirar</title>
  <style>
    .ExternalClass,
    .ReadMsgBody {
      width: 100%;
      background-color: #f4f7fb;
    }

    body,
    table,
    td,
    p,
    a {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    table,
    td {
      border-collapse: collapse;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    img {
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #f4f7fb;
      font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }
  </style>
</head>

<body
  style="margin:0; padding:0; background-color:#f4f7fb; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <center style="width:100%; table-layout:fixed;">
    <div style="max-width:600px; margin:0 auto;">
      <table align="center" cellpadding="0" cellspacing="0" width="100%"
        style="max-width:600px; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.05); margin:20px auto;">

        <!-- HEADER con logo a la izquierda y texto a la derecha -->
        <tr>
          <td style="background: linear-gradient(135deg, #1f2b3d 0%, #0f172a 100%); padding: 20px 30px;">
            <table cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <!-- Logo izquierda -->
                <td style="width: 80px; vertical-align: middle;">
                  <img src="{{ asset('images/logo/logo.jpg') }}" alt="Logo {{ config('app.name') }}"
                    style="max-width: 70px; height: auto; border-radius: 12px; display: block;">
                </td>
                <!-- Texto a la derecha -->
                <td style="vertical-align: middle; text-align: right;">
                  <h1 style="margin:0; color:#ffffff; font-size: 24px; font-weight:600;">
                    {{ config('app.name', 'Mi Tienda') }}</h1>
                  <p style="margin:4px 0 0; color:#facc15; font-size: 13px;">Comprobante de compra y retiro</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- CUERPO PRINCIPAL (igual que antes, pero se incluye el bloque de retiro y detalles) -->
        <tr>
          <td style="padding: 30px 30px 20px;">
            <h2 style="margin:0 0 8px; font-size: 24px; color:#1e2a3a;">¡Gracias por tu compra,
              {{ $order->user->fullName() ?? 'cliente' }}!</h2>
            <p style="margin:0 0 24px; color:#4a5568; font-size: 16px;">Tu pedido <strong>#{{ $order->id }}</strong>
              fue procesado correctamente y ya está listo para retirar.</p>

            <!-- BLOQUE DE RETIRO -->
            <div
              style="background-color:#fff8e7; border-left: 6px solid #f5a623; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
              <p style="margin:0 0 8px; font-size: 18px; font-weight:bold; color:#c75d00;">📦 Retirá tu pedido en
                nuestro local</p>
              <p style="margin:0 0 12px; color:#4a5568;"><strong>Dirección:</strong>
                {{ $store->address ?? 'Av. Corrientes 1234, CABA' }}</p>
              <p style="margin:0 0 12px; color:#4a5568;"><strong>Horario de atención para retiros:</strong>
                {{ $store->pickup_hours ?? 'Lunes a Viernes de 10 a 18 hs, Sábados 10 a 13 hs' }}</p>
              <p style="margin:0 0 8px; color:#4a5568;"><strong>¿Qué necesitas presentar?</strong> Este correo (o la
                factura adjunta) + tu DNI.</p>
              <p style="margin:12px 0 0; font-size:14px; color:#2d3748;">⚠️ Tenés
                <strong>{{ $order->pickup_deadline_days ?? 7 }} días</strong> para retirar tu pedido. Pasado ese plazo,
                deberás coordinar con nosotros.
              </p>
            </div>

            <!-- TABLA DE DETALLE DEL PEDIDO -->
            <h3 style="margin:0 0 12px; font-size:18px; color:#1e2a3a;">📋 Detalle de tu compra</h3>
            <table cellpadding="0" cellspacing="0" width="100%"
              style="background-color:#f9fafb; border-radius:12px; margin-bottom:24px;">
              <thead>
                <tr style="background-color:#eef2ff; border-bottom:1px solid #e2e8f0;">
                  <th style="padding:12px; text-align:left; font-size:14px; color:#1f2937;">Producto</th>
                  <th style="padding:12px; text-align:center; font-size:14px; color:#1f2937;">Cant.</th>
                  <th style="padding:12px; text-align:right; font-size:14px; color:#1f2937;">Precio</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->products as $item)
                  <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px; font-size:14px; color:#2d3748;">{{ $item->name }}</td>
                    <td style="padding:12px; text-align:center; font-size:14px; color:#2d3748;">
                      {{ $item->pivot->quantity }}
                    </td>
                    <td style="padding:12px; text-align:right; font-size:14px; color:#2d3748;">
                      ${{ number_format($item->pivot->price, 2, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2" style="padding:12px; text-align:right; font-weight:bold;">Subtotal</td>
                  <td style="padding:12px; text-align:right;">${{ number_format($order->subtotal, 2, ',', '.') }}</td>
                </tr>
                @if ($order->discount > 0)
                  <tr>
                    <td colspan="2" style="padding:6px 12px; text-align:right;">Descuento</td>
                    <td style="padding:6px 12px; text-align:right;">
                      -${{ number_format($order->discount, 2, ',', '.') }}</td>
                  </tr>
                @endif
                <tr>
                  <td colspan="2" style="padding:6px 12px; text-align:right;">IVA
                    ({{ (float) config('commerce.tax_rate') }}% incluido)</td>
                  <td style="padding:6px 12px; text-align:right;">${{ number_format($order->iva, 2, ',', '.') }}</td>
                </tr>
                <tr style="background-color:#fef9e3;">
                  <td colspan="2" style="padding:12px; text-align:right; font-weight:bold; font-size:16px;">Total
                  </td>
                  <td style="padding:12px; text-align:right; font-weight:bold; font-size:16px;">
                    ${{ number_format($order->total, 2, ',', '.') }}</td>
                </tr>
              </tfoot>
            </table>

            <!-- MÉTODO DE PAGO Y FECHA -->
            <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:24px;">
              <tr>
                <td style="width:50%; vertical-align:top; padding-right:10px;">
                  <div style="background-color:#f9fafb; border-radius:12px; padding:15px;">
                    <p style="margin:0 0 6px; font-weight:bold;">💳 Medio de pago</p>
                    <p style="margin:0; color:#4a5568;">{{ $order->payment->method ?? 'Cuenta corriente' }}</p>
                    @if ($order->payment->provider_transaction_id)
                      <p style="margin:6px 0 0; font-size:12px; color:#718096;">ID Transacción:
                        {{ $order->payment->provider_transaction_id }}</p>
                    @endif
                  </div>
                </td>
                <td style="width:50%; vertical-align:top; padding-left:10px;">
                  <div style="background-color:#f9fafb; border-radius:12px; padding:15px;">
                    <p style="margin:0 0 6px; font-weight:bold;">📅 Fecha del pedido</p>
                    <p style="margin:0; color:#4a5568;">{{ $order->date_formated }}</p>
                    <p style="margin:6px 0 0; font-size:12px; color:#718096;">Estado:
                      <strong>{{ $order->orderState->code ?? 'Listo para retirar' }}</strong>
                    </p>
                  </div>
                </td>
              </tr>
            </table>

            {{-- <!-- BOTÓN MAPS -->
            <div style="text-align:center; margin: 30px 0 20px;">
              <a href="{{ $store->maps_link ?? 'https://maps.google.com/?q='.urlencode($store->address ?? '') }}" style="background-color:#1f2b3d; color:#ffffff; padding:12px 24px; text-decoration:none; border-radius:40px; font-weight:600; display:inline-block;">📍 Ver ubicación en Google Maps</a>
            </div> --}}

            <hr style="margin:30px 0 20px; border:none; border-top:1px solid #e2e8f0;">
            <p style="font-size:13px; color:#718096; text-align:center;">¿Necesitas ayuda? Respondé este correo o
              escribinos a <a href="mailto:{{ config('mail.from.address') }}"
                style="color:#1f2b3d;">{{ config('mail.from.address') }}</a></p>
          </td>
        </tr>

        <!-- FOOTER con logo centrado o a la izquierda (usaremos centrado para que sea más profesional) -->
        <tr>
          <td style="background-color:#f8fafc; padding: 20px 30px; text-align: center; border-top:1px solid #e2e8f0;">
            <!-- Logo pequeño en el footer -->
            <img src="{{ asset('images/logo/logo.jpg') }}" alt="Logo {{ config('app.name') }}"
              style="max-width: 60px; height: auto; margin-bottom: 12px; opacity: 0.8;">
            <p style="margin:0; font-size:12px; color:#64748b;">© {{ date('Y') }} {{ config('app.name') }}. Todos
              los derechos reservados.</p>
            <p style="margin:8px 0 0; font-size:12px; color:#94a3b8;">Este correo es informativo. Guardá la factura
              adjunta para cualquier gestión.</p>
            <p style="margin:12px 0 0; font-size:11px; color:#a0aec0;">
              {{ $store->address ?? 'Av. Corrientes 1234, CABA' }} | Tel: {{ $store->phone ?? '(011) 1234-5678' }}</p>
          </td>
        </tr>
      </table>
    </div>
  </center>
</body>

</html>
