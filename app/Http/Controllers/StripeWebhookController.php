<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $secret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = Webhook::constructEvent($payload, $sigHeader, $secret);
    } catch (SignatureVerificationException $e) {
        Log::error('⚠️  Webhook signature verification failed.', ['error' => $e->getMessage()]);
        return response('Invalid signature', 400);
    }

    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;

        if (!isset($session->customer_details)) {
            Log::warning('❗Sessão sem detalhes do cliente.', ['session_id' => $session->id]);
            return response()->json(['status' => 'ignored - no customer_details']);
        }

        Log::info('💰 Checkout finalizado!', ['session' => $session]);

        $productId = $session->metadata->product_id ?? null;
        $quantity = is_numeric($session->metadata->quantity ?? null) ? (int) $session->metadata->quantity : 1;
        $customerEmail = $session->customer_details->email ?? null;

        if (!$productId || !$session->id) {
            Log::warning('⚠️ Dados incompletos no webhook.', compact('productId', 'session'));
            return response()->json(['status' => 'ignored - missing data']);
        }

        $amount = $session->amount_total / 100;
        $user = User::where('email', $customerEmail)->first();

        Order::updateOrCreate(
            ['reference' => $session->id],
            [
                'product_id' => $productId,
                'customer_name' => $session->customer_details->name,
                'customer_email' => $customerEmail,
                'customer_address' => isset($session->customer_details->address) ? json_encode($session->customer_details->address) : null,
                'status' => 'paid',
                'total_amount' => $amount,
                'quantity' => $quantity,
                'user_id' => $user?->id,
            ]
        );

        Log::info('✅ Pedido atualizado com sucesso!', [
            'product_id' => $productId,
            'user_id' => $user?->id,
            'amount' => $amount,
            'quantity' => $quantity,
        ]);
    }

        return response()->json(['status' => 'success']);
    }
}
