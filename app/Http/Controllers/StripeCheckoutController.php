<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;

class StripeCheckoutController extends Controller
{
    public function create(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity', 1);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price * 100, // Convert to cents
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['product' => $product->id]),
            'cancel_url' => route('checkout.cancel', ['product' => $product->id]),

            // Aqui está o metadata que você precisa adicionar:
            'metadata' => [
                'product_id' => $product->id,
                'quantity' => $quantity,
            ],
        ]);

        return response()->json(['url' => $session->url]);
    }
}
