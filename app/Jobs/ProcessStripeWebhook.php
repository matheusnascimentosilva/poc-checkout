<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessStripeWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    public function __construct($event)
    {
        // Serializamos só os dados necessários, para não ter problema no queue
        $this->event = $event;
    }

    public function handle()
    {
        if ($this->event->type !== 'checkout.session.completed') {
            Log::info("Evento ignorado no job: {$this->event->type}");
            return;
        }

        $session = $this->event->data->object;

        if (!isset($session->customer_details)) {
            Log::warning('Sessão sem detalhes do cliente.', ['session_id' => $session->id]);
            return;
        }

        Log::info('💰 Processando checkout.session.completed no job', ['session' => $session]);

        $productId = $session->metadata->product_id ?? null;
        $quantity = is_numeric($session->metadata->quantity ?? null) ? (int) $session->metadata->quantity : 1;
        $customerEmail = $session->customer_details->email ?? null;

        if (!$productId || !$session->id) {
            Log::warning('Dados incompletos no webhook no job.', compact('productId', 'session'));
            return;
        }

        $amount = $session->amount_total / 100;
        $user = User::where('email', $customerEmail)->first();

        Order::updateOrCreate(
            ['reference' => $session->id],
            [
                'product_id' => $productId,
                'customer_name' => $session->customer_details->name,
                'customer_email' => $customerEmail,
                'customer_address' => json_encode($session->customer_details->address),
                'status' => 'paid',
                'total_amount' => $amount,
                'quantity' => $quantity,
                'user_id' => $user?->id,
            ]
        );

        Log::info('✅ Pedido atualizado com sucesso no job!', [
            'product_id' => $productId,
            'user_id' => $user?->id,
            'amount' => $amount,
            'quantity' => $quantity,
        ]);
    }
}
