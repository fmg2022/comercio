<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyMercadoPagoWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $xSignature = $request->header('X-Signature');
        $xRequestId = $request->header('X-Request-Id');

        if (!$xSignature || !$xRequestId) {
            Log::warning('Webhook rechazado: Formato inválido en x-signature', ['xSignature' => $xSignature]);
            return response()->json(['error' => 'No se pudo verificar el webhook de MercadoPago.'], 401);
        }

        $signatureParts = [];
        parse_str(str_replace(',', '&', $xSignature), $signatureParts);

        $timestamp = $signatureParts['ts'] ?? null;
        $receivedHash = $signatureParts['v1'] ?? null;

        if (!$timestamp || !$receivedHash) {
            Log::warning('Webhook rechazado: Formato inválido en x-signature', ['header' => $xSignature]);
            return response()->json(['error' => 'Formato inválido en x-signature.'], 401);
        }

        $now = time();
        if (abs($now - (int)$timestamp) > 300) {
            Log::warning('Webhook rechazado: Timestamp expirado', ['ts' => $timestamp, 'now' => $now]);
            return response()->json(['error' => 'Timestamp expirado.'], 401);
        }

        $secret = config('commerce.mercadopago.webhook_secret');
        if (!$secret) {
            Log::error('Webhook: Secreto no configurado en config/services.php');
            return response()->json(['error' => 'Error de configuración del servidor.'], 500);
        }

        $eventId = $request['data_id'] ?? null;

        if (!$eventId) {
            Log::warning('Webhook: No se pudo extraer el ID del evento', ['event id' => $eventId]);
            return response()->json(['error' => 'ID de evento no encontrado'], 400);
        }

        $manifestString = "id:{$eventId};request-id:{$xRequestId};ts:{$timestamp};";
        $expectedHash = hash_hmac('sha256', $manifestString, $secret);

        if (!hash_equals($expectedHash, $receivedHash)) {
            Log::warning('Webhook rechazado: Firma inválida (manifest)', [
                'manifest' => $manifestString,
                'expected' => $expectedHash,
                'received' => $receivedHash,
            ]);
            return response()->json(['error' => 'Firma inválida.'], 401);
        }

        // Todo valido, la petición continua
        return $next($request);
    }
}
