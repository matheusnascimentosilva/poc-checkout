<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Você pode validar a assinatura aqui se quiser
        $payload = $request->getContent();
        $event = json_decode($payload, true);

        Log::info('Stripe Webhook recebido:', $event);

        if (!isset($event['type'])) {
            return response()->json(['message' => 'Evento inválido'], 400);
        }

        switch ($event['type']) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentSuccess($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentFailure($paymentIntent);
                break;

            default:
                Log::info('Evento não tratado: ' . $event['type']);
        }

        return response()->json(['message' => 'Webhook processado com sucesso'], Response::HTTP_OK);
    }

    private function handlePaymentSuccess($paymentIntent)
    {
        // Se você salvou `reference` no campo metadata do paymentIntent
        $reference = $paymentIntent['metadata']['reference'] ?? null;

        if (!$reference) {
            Log::warning('Pagamento recebido sem referência');
            return;
        }

        $order = Order::where('reference', $reference)->first();
        if (!$order) {
            Log::warning("Pedido com referência {$reference} não encontrado");
            return;
        }

        $order->status = 'paid';
        $order->save();
    }

    private function handlePaymentFailure($paymentIntent)
    {
        $reference = $paymentIntent['metadata']['reference'] ?? null;

        if (!$reference) {
            Log::warning('Falha de pagamento sem referência');
            return;
        }

        $order = Order::where('reference', $reference)->first();
        if (!$order) {
            Log::warning("Pedido com referência {$reference} não encontrado");
            return;
        }

        $order->status = 'failed';
        $order->save();
    }
}


