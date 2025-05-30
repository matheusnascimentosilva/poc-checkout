<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Jobs\ProcessStripeWebhook;

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
        Log::error('⚠️ Webhook signature verification failed.', ['error' => $e->getMessage()]);
        return response('Invalid signature', 400);
    }

    // Disparar job passando o evento (de preferência, serializado como array)
    ProcessStripeWebhook::dispatch($event);

    return response()->json(['status' => 'success']);
}
}
