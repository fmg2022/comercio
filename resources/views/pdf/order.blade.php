<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Factura B {{ $factura->numero }}</title>
  <style>
    /* RESET Y CONFIGURACIÓN GLOBAL */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'DejaVu Sans', 'Arial', sans-serif;
      font-size: 10px;
      line-height: 1.4;
      color: #1a1a1a;
      padding: 15px;
    }

    /* CONTENEDOR PRINCIPAL */
    .invoice-container {
      max-width: 100%;
      margin: 0 auto;
    }

    /* ENCABEZADO - TABLA IZQUIERDA-CENTRO-DERECHA */
    .header-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }

    .header-table td {
      vertical-align: top;
      padding: 5px;
    }

    /* LOGO */
    .logo-placeholder {
      width: 80px;
      height: 80px;
      border: 1px solid #ddd;
      line-height: 80px;
      font-size: 11px;
      color: #999;
      background-color: #f9f9f9;
    }

    /* DATOS DEL COMERCIO */
    .comercio-nombre {
      font-size: 16px;
      font-weight: bold;
      color: #1a5f7a;
      margin-bottom: 5px;
    }

    .comercio-datos {
      font-size: 8px;
      color: #555;
    }

    /* TÍTULO FACTURA */
    .factura-titulo {
      font-size: 22px;
      font-weight: bold;
      color: #1a5f7a;
      text-align: center;
    }

    .factura-letra {
      font-size: 28px;
      font-weight: bold;
      color: #d9534f;
    }

    .factura-numero {
      font-size: 14px;
      margin-top: 5px;
    }

    /* CAJAS DE INFORMACIÓN */
    .info-box {
      border: 1px solid #ddd;
      padding: 8px;
      background-color: #fafafa;
      margin-bottom: 15px;
    }

    .info-box-title {
      font-weight: bold;
      font-size: 9px;
      margin-bottom: 5px;
      color: #1a5f7a;
      border-bottom: 1px solid #ddd;
      padding-bottom: 3px;
    }

    /* TABLA DE PRODUCTOS */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }

    .items-table th,
    .items-table td {
      border: 1px solid #ccc;
      padding: 6px;
      vertical-align: top;
    }

    .items-table th {
      background-color: #e8ecef;
      font-weight: bold;
      text-align: center;
      font-size: 9px;
    }

    .items-table td {
      font-size: 9px;
    }

    /* ALINEACIÓN DE COLUMNAS */
    .items-table td:nth-child(1),
    .items-table td:nth-child(2),
    .items-table th:nth-child(1),
    .items-table th:nth-child(2) {
      text-align: left;
    }

    .items-table td:nth-child(3),
    .items-table th:nth-child(3),
    .items-table th:nth-child(4),
    .items-table th:nth-child(5) {
      text-align: center;
    }

    .items-table td:nth-child(4),
    .items-table td:nth-child(5) {
      text-align: right;
    }

    /* TOTALES */
    .totales-table {
      width: 250px;
      float: right;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .totales-table td {
      padding: 5px;
      font-size: 10px;
    }

    .totales-table td:first-child {
      text-align: right;
      font-weight: normal;
    }

    .totales-table td:last-child {
      text-align: right;
      font-weight: bold;
    }

    .total-final {
      font-size: 14px;
      font-weight: bold;
      color: #d9534f;
      border-top: 2px solid #333;
    }

    /* CONDICIONES LEGALES */
    .condiciones-legales {
      clear: both;
      margin-top: 30px;
      padding-top: 10px;
      border-top: 1px solid #ddd;
      font-size: 7px;
      text-align: center;
      color: #777;
    }

    .comprobante-original {
      background-color: #1a5f7a;
      color: white;
      padding: 3px 8px;
      font-size: 7px;
      display: inline-block;
      margin-bottom: 10px;
    }

    /* FOOTER */
    .footer {
      margin-top: 20px;
      font-size: 7px;
      text-align: center;
      color: #999;
    }

    /* CLEAR FIX */
    .clearfix::after {
      content: "";
      clear: both;
      display: table;
    }
  </style>
</head>

<body>
  <div class="invoice-container">

    <!-- ENCABEZADO: Logo | Título Factura | Datos Fecha -->
    <table class="header-table">
      <tr>
        <!-- IZQUIERDA: LOGO O NOMBRE CORTO -->
        <td style="width: 25%; text-align: left;">
          <div class="logo-placeholder">
            <img src="{{ public_path('images/logo/logo.jpg') }}" alt="Logo {{ $comercio->nombre }}"
              style="width: 100%; height: 100%; object-fit: contain;">
          </div>
        </td>

        <!-- CENTRO: TÍTULO FACTURA B -->
        <td style="width: 50%; text-align: center;">
          <div class="factura-titulo">
            FACTURA
          </div>
          <div class="factura-letra">B</div>
          <div class="factura-numero">
            N° {{ $factura->punto_venta ?? '0001' }}-{{ $factura->numero }}
          </div>
        </td>

        <!-- DERECHA: FECHA Y CONDICIÓN -->
        <td style="width: 25%; text-align: right;">
          <div class="comercio-datos" style="text-align: right;">
            <strong>Fecha Emisión:</strong><br>
            {{ $factura->fecha_emision }}<br><br>
            <strong>Condición IVA:</strong><br>
            Consumidor Final<br>
            <span class="comprobante-original">COMPROBANTE ORIGINAL</span>
          </div>
        </td>
      </tr>
    </table>

    <!-- DATOS DEL COMERCIO -->
    <div class="info-box">
      <div class="comercio-nombre">{{ $comercio->nombre }}</div>
      <div class="comercio-datos">
        <strong>Domicilio Comercial:</strong> {{ $comercio->domicilio }}<br>
        <strong>Condición frente al IVA:</strong> {{ $comercio->condicion_iva }}<br>
        <strong>Ingresos Brutos:</strong> {{ $comercio->ingresos_brutos }}<br>
        <strong>Fecha de Inicio de Actividades:</strong> {{ $comercio->fecha_inicio }}
      </div>
    </div>

    <!-- DATOS DEL CLIENTE (OPCIONAL EN FACTURA B) -->
    <div class="info-box">
      <div class="info-box-title">DATOS DEL CLIENTE</div>
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td style="width: 50%;"><strong>Nombre/Razón Social:</strong>
            {{ $factura->cliente_nombre ?? 'Consumidor Final' }}</td>
          <td style="width: 50%;"><strong>Documento:</strong> {{ $factura->cliente_documento ?? 'Sin documento' }}</td>
        </tr>
        <tr>
          <td><strong>Domicilio:</strong> {{ $factura->cliente_domicilio ?? 'Sin domicilio' }}</td>
          <td><strong>Condición IVA:</strong> Consumidor Final</td>
        </tr>
      </table>
    </div>

    <!-- TABLA DE PRODUCTOS -->
    <table class="items-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Descripción</th>
          <th>Cantidad</th>
          <th>Precio Unit.</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse($factura->items as $item)
          <tr>
            <td>{{ $item->sku ?? '---' }}</td>
            <td>{{ $item->name }}<span style="text-transform: uppercase"> {{ $item->brand->name }}</span>
              {{ $item->weight ? ' - ' . $item->weight : '' }}
            </td>
            <td style="text-align: center;">{{ $item->pivot->quantity }}</td>
            <td style="text-align: right;">$ {{ number_format($item->pivot->price, 2, ',', '.') }}</td>
            <td style="text-align: right;">$ {{ $item->pivot->subtotal() }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center;">No hay productos cargados</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <!-- TOTALES (SIN DISCRIMINAR IVA) -->
    <div class="clearfix">
      <table class="totales-table">
        <tr>
          <td><strong>SUBTOTAL:</strong></td>
          <td>$ {{ number_format($factura->subtotal, 2, ',', '.') }}</td>
        </tr>
        <tr style="color: #777;">
          <td>IVA (21% incluido):</td>
          <td>$ {{ number_format($factura->iva, 2, ',', '.') }}</td>
        </tr>
        <tr class="total-final">
          <td><strong>TOTAL:</strong></td>
          <td><strong>$ {{ number_format($factura->total, 2, ',', '.') }}</strong></td>
        </tr>
      </table>
    </div>

    <!-- FORMA DE PAGO -->
    <div class="info-box" style="clear: both; margin-top: 20px;">
      <div class="info-box-title">FORMA DE PAGO</div>
      <table style="width: 100%;">
        <tr>
          <td style="width: 33%;"><strong>Medio de pago:</strong> {{ $factura->medio_pago ?? 'Efectivo' }}</td>
          <td style="width: 33%;"><strong>Cuotas/Cuenta:</strong> {{ $factura->cuotas ?? 'Contado' }}</td>
          <td style="width: 34%;"><strong>N° Operación:</strong> {{ $factura->operacion_numero ?? '---' }}</td>
        </tr>
      </table>
    </div>

    <!-- CONDICIONES LEGALES ARGENTINAS -->
    <div class="condiciones-legales">
      <p>
        <strong>Factura B - Crédito Fiscal No Computable</strong><br>
        Comprobante Original. No válido como factura de crédito fiscal.<br>
        Conforme Resolución General AFIP N° 1415 y modificatorias.<br>
        El comprobante contiene todos los requisitos exigidos por la Ley N° 11.683 y normas complementarias.<br>
        El consumidor podrá realizar reclamos en defensa del consumidor según Ley 24.240.
      </p>
    </div>

    <!-- QR AFIP / CAE (si corresponde) -->
    @if (isset($factura->cae))
      <div style="text-align: center; margin-top: 15px; font-size: 8px;">
        <strong>CAE N°:</strong> {{ $factura->cae }}<br>
        <strong>Vto. CAE:</strong> {{ $factura->cae_vencimiento }}
      </div>
    @endif

    <!-- FOOTER -->
    <div class="footer">
      Gracias por su compra | {{ $comercio->nombre }} | Horario de atención:
      {{ $comercio->horario_atencion ?? 'Lunes a Domingo 8:00 a 21:00' }}
    </div>

  </div>
</body>

</html>
