<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // pega os pedidos apenas dos produtos do vendedor logado
        $orders = Order::whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }
}
