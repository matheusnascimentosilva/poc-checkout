<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $secret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('⚠️  Webhook signature verification failed.', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // 🔍 Aqui você pode tratar os eventos que quiser
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            Log::info('💰 Checkout finalizado!', ['session' => $session]);
            // Aqui você pode atualizar o pedido no banco, etc.
        }

        return response()->json(['status' => 'success']);
    }
}
