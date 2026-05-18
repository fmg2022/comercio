<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a {{ config('app.name') }}</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: #f4f4f7;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
      background-color: #0b3662;
      color: white;
      text-align: center;
      padding: 30px 20px;
    }

    .header h1 {
      margin: 0;
      font-size: 28px;
    }

    .content {
      padding: 30px 25px;
      color: #333333;
      line-height: 1.6;
    }

    .button {
      display: inline-block;
      border: 1px solid #0b3662;
      text-decoration: none;
      padding: 12px 25px;
      border-radius: 6px;
      margin: 20px 0;
      font-weight: bold;
    }

    .footer {
      background-color: #f4f4f7;
      text-align: center;
      padding: 20px;
      font-size: 12px;
      color: #777777;
    }

    @media only screen and (max-width: 600px) {
      .container {
        width: 100% !important;
      }

      .content {
        padding: 20px !important;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>¡Bienvenido, {{ $user->fullName() }}!</h1>
    </div>
    <div class="content">
      <p>Hola <strong>{{ $user->fullName() }}</strong>,</p>
      <p>Tu correo electrónico ha sido verificado con éxito. ¡Tu cuenta ya está activa!</p>
      <p>{{ $customMessage }}</p>

      <p>Ahora puedes disfrutar de todos los beneficios de nuestra plataforma:</p>
      <ul>
        <li>Acceso a contenido exclusivo</li>
        <li>Soporte prioritario</li>
        <li>Ofertas especiales para nuevos miembros</li>
      </ul>

      <center>
        <a href="{{ route('dashboard.index') }}" class="button">Ir a mi panel</a>
      </center>

      <p>¿Necesitas ayuda? Contáctanos en <a href="mailto:soporte@tudominio.com">soporte@tudominio.com</a>.</p>
      <p>¡Gracias por formar parte de {{ config('app.name') }}!</p>
    </div>
    <div class="footer">
      © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.<br>
      Si no solicitaste esta verificación, ignora este mensaje.
    </div>
  </div>
</body>

</html>
