<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a {{ config('app.name') }}</title>
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
        style="max-width:600px; background-color:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 10px 25px -5px rgba(0,0,0,0.05); margin:20px auto;">

        <!-- HEADER con logo y nombre -->
        <tr>
          <td
            style="background: linear-gradient(135deg, #0b3662 0%, #1a4d7a 100%); padding: 30px 30px 25px; text-align: center;">
            <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}"
              style="max-width: 160px; height: auto; margin-bottom: 15px;">
            <h1 style="margin:10px 0 0; color:#ffffff; font-size: 28px; font-weight: 600;">¡Bienvenido,
              {{ $user->fullName() }}!</h1>
            <p style="margin:10px 0 0; color:#d1e3ff; font-size: 16px;">Tu cuenta ha sido creada exitosamente</p>
          </td>
        </tr>

        <!-- CUERPO PRINCIPAL -->
        <tr>
          <td style="padding: 35px 30px 25px;">
            <p style="margin:0 0 18px; font-size: 16px; color:#2d3748;">Hola <strong>{{ $user->fullName() }}</strong>,
            </p>
            <p style="margin:0 0 18px; font-size: 16px; color:#4a5568;">Gracias por formar parte de
              {{ config('app.name') }}. Para que puedas acceder a tu cuenta, hemos generado una contraseña temporal.</p>

            <!-- CREDENCIALES Y VERIFICACIÓN -->
            <div
              style="background-color:#f0f9ff; border-left: 4px solid #0b3662; padding: 15px; margin-bottom: 25px; border-radius: 8px;">
              <p style="margin:0 0 8px; color:#1a4d7a; font-size: 15px;"><strong>🔐 Tus credenciales de acceso</strong>
              </p>
              <p style="margin:0 0 5px; font-size: 15px; color:#2d3748;"><strong>Correo:</strong> {{ $user->email }}
              </p>
              <p style="margin:0 0 10px; font-size: 15px; color:#2d3748;"><strong>Contraseña temporal:</strong> <code
                  style="background:#e2e8f0; padding:4px 8px; border-radius:6px;">{{ $plainPassword }}</code></p>
              <p style="margin:8px 0 0; font-size: 13px; color:#4a5568;">⚠️ Por seguridad, deberás cambiar tu contraseña
                después de iniciar sesión.</p>
            </div>

            @if (!empty($customMessage))
              <div
                style="background-color:#f0f9ff; border-left: 4px solid #0b3662; padding: 15px; margin-bottom: 25px; border-radius: 8px;">
                <p style="margin:0; color:#1a4d7a; font-size: 15px;">{{ $customMessage }}</p>
              </div>
            @endif

            <!-- BOTÓN PARA VERIFICAR EMAIL (obligatorio) -->
            <div style="text-align: center; margin: 20px 0 20px;">
              <p style="margin:0 0 12px; font-size: 15px; color:#4a5568;">Antes de acceder, debes verificar tu dirección
                de correo electrónico:</p>
              <a href="{{ $verificationUrl }}"
                style="background-color:#0b3662; color:#ffffff; padding: 14px 32px; text-decoration:none; border-radius: 40px; font-weight:600; display:inline-block; font-size:16px;">✅
                Verificar mi correo</a>
            </div>

            <h3 style="margin:30px 0 12px; font-size: 18px; color:#1e2a3a;">✨ ¿Qué puedes hacer ahora?</h3>
            <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 25px;">
              <tr>
                <td
                  style="padding: 8px 0 8px 25px; background: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 24 24\' fill=\'%230b3662\'%3E%3Cpath d=\'M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z\'/%3E%3C/svg%3E') left center no-repeat; background-size: 16px;">
                  Acceso a contenido exclusivo y ofertas personalizadas
                </td>
              </tr>
              <tr>
                <td
                  style="padding: 8px 0 8px 25px; background: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 24 24\' fill=\'%230b3662\'%3E%3Cpath d=\'M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z\'/%3E%3C/svg%3E') left center no-repeat; background-size: 16px;">
                  Soporte prioritario las 24 horas
                </td>
              </tr>
            </table>

            <div style="text-align: center; margin: 25px 0 20px;">
              <a href="{{ route('dashboard.index') }}"
                style="background-color:#0b3662; color:#ffffff; padding: 14px 32px; text-decoration:none; border-radius: 40px; font-weight:600; display:inline-block; font-size:16px;">🎉
                Ir a mi panel</a>
            </div>

            <p style="margin:25px 0 0; font-size:14px; color:#718096;">¿Necesitas ayuda? Escríbenos a <a
                href="mailto:soporte@tudominio.com" style="color:#0b3662;">soporte@tudominio.com</a> o responde este
              correo.</p>
            <p style="margin:15px 0 0; font-size:14px; color:#4a5568;">¡Gracias por formar parte de
              {{ config('app.name') }}!</p>
          </td>
        </tr>

        <!-- FOOTER con logo pequeño, redes sociales y datos legales -->
        <tr>
          <td style="background-color:#f8fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
            <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}"
              style="max-width: 80px; height: auto; margin-bottom: 15px;">
            <p style="margin:0 0 15px; font-size:13px; color:#475569;">Síguenos en redes</p>
            <div style="margin:10px 0 20px;">
              <a href="#" style="display:inline-block; margin:0 8px;"><img
                  src="https://cdn-icons-png.flaticon.com/512/733/733547.png" width="28" height="28"
                  alt="Facebook" style="max-width:28px;"></a>
              <a href="#" style="display:inline-block; margin:0 8px;"><img
                  src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" width="28" height="28"
                  alt="Instagram" style="max-width:28px;"></a>
              <a href="#" style="display:inline-block; margin:0 8px;"><img
                  src="https://cdn-icons-png.flaticon.com/512/733/733579.png" width="28" height="28"
                  alt="Twitter" style="max-width:28px;"></a>
            </div>
            <p style="margin:0 0 8px; font-size:12px; color:#64748b;">© {{ date('Y') }} {{ config('app.name') }}.
              Todos los derechos reservados.</p>
            <p style="margin:0; font-size:11px; color:#94a3b8;">Si no solicitaste esta verificación, puedes ignorar este
              mensaje.</p>
            <p style="margin:10px 0 0; font-size:11px;"><a href="#" style="color:#94a3b8;">Cancelar
                suscripción</a></p>
          </td>
        </tr>
      </table>
    </div>
  </center>
</body>

</html>
