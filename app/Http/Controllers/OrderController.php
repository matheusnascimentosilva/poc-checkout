<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::whereHas('product', function ($q) {
            $q->where('user_id', Auth::id());
        });

        // Filtro por status (opcional)
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->with('product')
            ->latest()
            ->paginate(10)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'product' => [
                        'name' => $order->product?->name ?? 'Produto Removido'
                    ],
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'customer_address' => $order->customer_address,
                    'reference' => $order->reference,
                    'total_amount' => $order->total_amount,
                    'created_at' => $order->created_at,
                    'status' => [
                        'value' => $order->status->value,
                        'label' => $order->status->label(),
                        'color' => $order->status->color(),
                        'icon' => $order->status->icon(),
                        'iconColor' => $order->status->iconColor(),
                    ],
                ];
            });

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'filters' => [
                'status' => $request->status,
            ]
        ]);
    }
}
