<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return Inertia::render('Checkout/Show', [
            'product' => $product,
        ]);
    }

    public function store(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
        ]);

        $order = Order::create([
            'product_id' => $product->id,
            'customer_name' => $data['name'],
            'customer_email' => $data['email'],
            'customer_address' => $data['address'],
        ]);

        return redirect()->route('checkout.show', $product->uuid)->with('success', 'Pedido realizado com sucesso!');
    }

    public function createCheckoutSession(Product $product)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd', // ou 'brl'
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price * 100, // em centavos
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['product' => $product->id]),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Product $product)
    {
        return view('checkout.success', compact('product'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
