<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cuenta desactivada - {{ config('app.name') }}</title>
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

        <!-- HEADER con logo y aviso de desactivación -->
        <tr>
          <td
            style="background: linear-gradient(135deg, #8B0000 0%, #A52A2A 100%); padding: 30px 30px 25px; text-align: center;">
            <img src="{{ $logo_url ?? asset('images/logo-blanco.png') }}" alt="{{ config('app.name') }}"
              style="max-width: 160px; height: auto; margin-bottom: 15px;">
            <h1 style="margin:10px 0 0; color:#ffffff; font-size: 28px; font-weight: 600;">Cuenta desactivada</h1>
            <p style="margin:10px 0 0; color:#ffd1d1; font-size: 16px;">
              Desactivada por {{ $deactivatedBy['role'] ?? 'Administrador' }} -
              {{ $deactivatedBy['name'] ?? 'Sistema' }}
            </p>
          </td>
        </tr>

        <!-- CUERPO PRINCIPAL -->
        <tr>
          <td style="padding: 35px 30px 25px;">
            <p style="margin:0 0 18px; font-size: 16px; color:#2d3748;">Hola <strong>{{ $user->fullName() }}</strong>,
            </p>
            <p style="margin:0 0 18px; font-size: 16px; color:#4a5568;">
              Lamentamos informarte que tu cuenta en {{ config('app.name') }} ha sido <strong>desactivada</strong>.
            </p>

            @if (!empty($customMessage))
              <div
                style="background-color:#fff3f3; border-left: 4px solid #8B0000; padding: 15px; margin-bottom: 25px; border-radius: 8px;">
                <p style="margin:0; color:#8B0000; font-size: 15px;">{{ $customMessage }}</p>
              </div>
            @endif

            <p style="margin:0 0 25px; font-size: 16px; color:#4a5568;">
              Si deseas más información sobre los motivos de esta acción o consideras que se trata de un error,
              por favor comunícate con nuestro equipo de soporte.
            </p>

            <!-- Información de contacto -->
            <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 25px;">
              <tr>
                <td style="padding: 12px; background-color:#f8fafc; border-radius: 8px;">
                  <p style="margin:0 0 8px; font-weight:600; font-size:15px; color:#2d3748;">📧 Correo de soporte:</p>
                  <a href="mailto:{{ $supportEmail ?? 'soporte@tudominio.com' }}"
                    style="color:#8B0000; font-size:15px; text-decoration:none;">
                    {{ $supportEmail ?? 'soporte@tudominio.com' }}
                  </a>
                </td>
              </tr>
              <tr>
                <td style="padding: 12px; background-color:#f8fafc; border-radius: 8px; margin-top: 10px;">
                  <p style="margin:0 0 8px; font-weight:600; font-size:15px; color:#2d3748;">📞 Teléfono de soporte:</p>
                  <span style="color:#4a5568; font-size:15px;">{{ $supportPhone ?? '+1 (800) 123-4567' }}</span>
                </td>
              </tr>
            </table>

            <p style="margin:0 0 15px; font-size:14px; color:#718096;">
              Nuestro equipo atenderá tu consulta lo antes posible.
            </p>
            <p style="margin:15px 0 0; font-size:14px; color:#4a5568;">
              Atentamente,<br>El equipo de {{ config('app.name') }}
            </p>
          </td>
        </tr>

        <!-- FOOTER -->
        <tr>
          <td style="background-color:#f8fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
            <img src="{{ $logo_small_url ?? asset('images/logo-oscuro.png') }}" alt="{{ config('app.name') }}"
              style="max-width: 80px; height: auto; margin-bottom: 15px;">
            <p style="margin:0 0 15px; font-size:13px; color:#475569;">Síguenos en redes</p>
            <div style="margin:10px 0 20px;">
              <a href="#a" style="display:inline-block; margin:0 8px;"><img
                  src="https://cdn-icons-png.flaticon.com/512/733/733547.png" width="28" height="28"
                  alt="Facebook" style="max-width:28px;"></a>
              <a href="#a" style="display:inline-block; margin:0 8px;"><img
                  src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" width="28" height="28"
                  alt="Instagram" style="max-width:28px;"></a>
              <a href="#a" style="display:inline-block; margin:0 8px;"><img
                  src="https://cdn-icons-png.flaticon.com/512/733/733579.png" width="28" height="28"
                  alt="Twitter" style="max-width:28px;"></a>
            </div>
            <p style="margin:0 0 8px; font-size:12px; color:#64748b;">© {{ date('Y') }} {{ config('app.name') }}.
              Todos los derechos reservados.</p>
            <p style="margin:0; font-size:11px; color:#94a3b8;">Si crees que esta desactivación fue un error, por favor
              contacta a soporte.</p>
          </td>
        </tr>
      </table>
    </div>
  </center>
</body>

</html>
