<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Factura B {{ $factura->numero }}</title>
  <style>
    /* ========== ESTILOS COMPATIBLES CON DOMPDF ========== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #e2e8f0;
      font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
      padding: 20px;
      font-size: 10pt;
      line-height: 1.4;
      color: #1a1a1a;
    }

    .invoice-container {
      max-width: 1100px;
      width: 100%;
      margin: 0 auto;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Header ARCA - sin gradient, color sólido */
    .arca-header {
      background-color: #1f2b3d;
      /* sólido, compatible */
      padding: 20px 30px;
      color: white;
      border-bottom: 3px solid #facc15;
      overflow: hidden;
    }

    .arca-logo {
      float: left;
      width: 60%;
    }

    .comprobante-tipo {
      float: right;
      width: 35%;
      background: rgba(255, 255, 240, 0.15);
      padding: 8px 15px;
      border-radius: 40px;
      text-align: center;
    }

    .arca-logo h2 {
      font-size: 24px;
      font-weight: 600;
      margin: 0;
    }

    .arca-logo p {
      font-size: 10px;
      opacity: 0.8;
      margin: 4px 0 0;
    }

    .comprobante-tipo .label {
      font-size: 9px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .comprobante-tipo .tipo {
      font-size: 28px;
      font-weight: 800;
      line-height: 1;
      color: #facc15;
    }

    .clearfix::after {
      content: "";
      clear: both;
      display: table;
    }

    .invoice-body {
      padding: 25px 30px;
    }

    /* Tabla para emisor/receptor (misma fila, dos columnas) */
    .datos-tabla {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      background: #f8fafc;
      border-radius: 12px;
      overflow: hidden;
    }

    .datos-tabla td {
      width: 50%;
      vertical-align: top;
      padding: 12px 15px;
      border: 1px solid #e2e8f0;
    }

    .datos-item {
      margin: 5px 0;
      font-size: 9pt;
    }

    .datos-item strong {
      font-weight: 600;
      display: inline-block;
      min-width: 120px;
    }

    /* Info fiscal (tabla) */
    .info-fiscal {
      background: #f1f5f9;
      padding: 12px 15px;
      margin-bottom: 20px;
      border-radius: 12px;
      width: 100%;
    }

    .info-fiscal table {
      width: 100%;
      border-collapse: collapse;
    }

    .info-fiscal td {
      padding: 4px 8px;
      font-size: 9pt;
    }

    /* Tabla de productos */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .items-table th,
    .items-table td {
      border: 1px solid #ccc;
      padding: 8px;
      font-size: 9pt;
      vertical-align: top;
    }

    .items-table th {
      background: #eef2ff;
      text-align: left;
      font-weight: bold;
    }

    .text-right {
      text-align: right;
    }

    /* Totales e IVA */
    .totales-iva {
      text-align: right;
      margin-bottom: 20px;
    }

    .totales-card {
      display: inline-block;
      background: #f8fafc;
      border-radius: 12px;
      padding: 12px 20px;
      min-width: 260px;
      text-align: left;
    }

    .totales-card p {
      margin: 5px 0;
      font-size: 9pt;
    }

    .totales-card hr {
      margin: 6px 0;
      border: 0;
      border-top: 1px solid #ccc;
    }

    /* Footer legal - garantizado visible */
    .footer-legal {
      margin-top: 20px;
      border-top: 1px solid #e2e8f0;
      padding-top: 15px;
      font-size: 8pt;
      text-align: center;
      color: #475569;
      clear: both;
    }

    .footer-row {
      margin-bottom: 20px;
    }

    .footer {
      margin-top: 10px;
      font-size: 7px;
    }

    .footer-col {
      float: left;
      width: 33%;
      text-align: center;
    }

    .qr-fake {
      background: #f1f5f9;
      width: 60px;
      height: 60px;
      display: inline-block;
      line-height: 60px;
      text-align: center;
      border-radius: 8px;
      font-size: 8px;
      color: #334155;
      font-family: monospace;
    }
  </style>
</head>

<body>
  <div class="invoice-container">
    <!-- Header con color sólido -->
    <div class="arca-header clearfix">
      <div class="arca-logo">
        <h2>ARCA</h2>
        <p>Agencia de Recaudación y Control Aduanero</p>
      </div>
      <div class="comprobante-tipo">
        <div class="label">COMPROBANTE AUTORIZADO</div>
        <div class="tipo">FACTURA B</div>
        <div style="font-size:8px;">Consumidor Final / Exento</div>
      </div>
    </div>

    <div class="invoice-body">
      <!-- Emisor y receptor en tabla de dos columnas (misma fila) -->
      <table class="datos-tabla">
        <tr>
          <td>
            <div class="datos-item"><strong>Razón Social:</strong> {{ $comercio->razon_social ?? $comercio->nombre }}
            </div>
            <div class="datos-item"><strong>CUIT:</strong> {{ $comercio->cuit }}</div>
            <div class="datos-item"><strong>Domicilio Comercial:</strong> {{ $comercio->domicilio }}</div>
            <div class="datos-item"><strong>Condición IVA:</strong> {{ $comercio->condicion_iva }}</div>
            <div class="datos-item"><strong>Ingresos Brutos:</strong> {{ $comercio->ingresos_brutos }}</div>
            <div class="datos-item"><strong>Inicio Actividades:</strong> {{ $comercio->fecha_inicio }}</div>
          </td>
          <td>
            <div class="datos-item"><strong>Cliente:</strong> {{ $factura->cliente_nombre ?? 'Consumidor Final' }}</div>
            <div class="datos-item"><strong>Documento:</strong> {{ $factura->cliente_tipo_doc ?? 'DNI' }}
              {{ $factura->cliente_documento ?? 'Sin documento' }}</div>
            <div class="datos-item"><strong>Condición IVA:</strong> Consumidor Final</div>
            <div class="datos-item"><strong>Domicilio:</strong> {{ $factura->cliente_domicilio ?? 'Sin domicilio' }}
            </div>
            @if ($factura->cliente_telefono ?? false)
              <div class="datos-item"><strong>Teléfono:</strong> {{ $factura->cliente_telefono }}</div>
            @endif
          </td>
        </tr>
      </table>

      <!-- Datos del comprobante -->
      <div class="info-fiscal">
        <table>
          <tr>
            <td><strong>Punto de Venta:</strong> {{ sprintf('%04d', $factura->punto_venta ?? 1) }}</td>
            <td><strong>Número Factura:</strong>
              {{ sprintf('%04d', $factura->punto_venta ?? 1) }}-{{ sprintf('%08d', $factura->numero) }}</td>
            <td><strong>Fecha Emisión:</strong> {{ $factura->fecha_emision }}</td>
          </tr>
          <tr>
            <td><strong>CAE:</strong> <span
                style="background:white; padding:2px 8px; border:1px solid #ccc; border-radius:20px;">{{ $factura->cae ?? 'Pendiente' }}</span>
            </td>
            <td><strong>Vto. CAE:</strong> {{ $factura->cae_vencimiento ?? '---' }}</td>
            <td><strong>Moneda:</strong> Peso Argentino ($)</td>
          </tr>
        </table>
      </div>

      <!-- Tabla de items -->
      <table class="items-table">
        <thead>
          <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th class="text-right">Cantidad</th>
            <th class="text-right">Precio Unitario</th>
            <th class="text-right">Importe</th>
          </tr>
        </thead>
        <tbody>
          @forelse($factura->items as $item)
            <tr>
              <td>{{ $item->sku ?? '---' }}</td>
              <td>{{ $item->descripcion }}</td>
              <td class="text-right">{{ $item->pivot->quantity }}</td>
              <td class="text-right">$ {{ number_format($item->pivot->price, 2, ',', '.') }}</td>
              <td class="text-right">$ {{ number_format($item->pivot->quantity * $item->pivot->price, 2, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-right">No hay productos cargados</td>
            </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" class="text-right"><strong>Subtotal Neto</strong></td>
            <td class="text-right"><strong>$ {{ number_format($factura->subtotal, 2, ',', '.') }}</strong></td>
          </tr>
          <tr style="background:#fef9e3;">
            <td colspan="4" class="text-right"><strong>+ IVA 21% (incluido)</strong></td>
            <td class="text-right"><strong>$ {{ number_format($factura->iva, 2, ',', '.') }}</strong></td>
          </tr>
          <tr class="total-row">
            <td colspan="4" class="text-right"><strong>TOTAL A PAGAR</strong></td>
            <td class="text-right"><strong>$ {{ number_format($factura->total, 2, ',', '.') }}</strong></td>
          </tr>
        </tfoot>
      </table>

      <!-- Desglose transparencia fiscal -->
      <div class="totales-iva">
        <div class="totales-card">
          <p><strong>Transparencia Fiscal - Detalle del IVA Incluido</strong></p>
          <p><span>Subtotal gravado:</span> <span>$ {{ number_format($factura->subtotal, 2, ',', '.') }}</span></p>
          <p><span>Alícuota IVA:</span> <span>21%</span></p>
          <p><span>Importe IVA incluido:</span> <span>$ {{ number_format($factura->iva, 2, ',', '.') }}</span></p>
          <hr>
          <p><span>Monto de otros impuestos:</span> <span>Incluido en precio final</span></p>
          <p><span>Percepciones:</span> <span>{{ $factura->percepciones ?? 'No aplica' }}</span></p>
          <hr>
          <p><strong><span>Total con impuestos incluidos:</span> <span>$
                {{ number_format($factura->total, 2, ',', '.') }}</span></strong></p>
        </div>
      </div>

      <!-- Footer legal (visible) -->
      <div class="footer-legal">
        <div class="footer-row">
          <p class="footer-col" style="text-align: left;">CAE Nº: {{ $factura->cae ?? '---' }} - Vto.:
            {{ $factura->cae_vencimiento ?? '---' }}</p>
          <div class="footer-col">
            @if (isset($factura->qr_base64))
              <img src="data:image/png;base64,{{ $factura->qr_base64 }}" width="60" height="60" alt="QR ARCA">
            @else
              <div class="qr-fake">[ CÓDIGO QR ]<br>validación</div>
            @endif
          </div>
          <p class="footer-col" style="text-align: right;">Factura autorizada por ARCA - RG 1415<br>IVA incluido -
            Comprobante original</p>
        </div>
        <p style="clear: both; font-size: 8px; padding-top: 20px;">
          La presente factura responde a la normativa vigente de ARCA. Verifique en la web oficial.
        </p>
        <!-- FOOTER -->
        <p class="footer">
          Gracias por su compra | {{ $comercio->nombre }} | Horario de atención:
          {{ $comercio->horario_atencion ?? 'Lunes a Domingo 8:00 a 21:00' }}
        </p>
      </div>

    </div>
</body>

</html>
