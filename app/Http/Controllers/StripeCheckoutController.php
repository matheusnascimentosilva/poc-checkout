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

        $quantity = $request->input('quantity', 1);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $request->input('product_name'),
                    ],
                    'unit_amount' => $request->input('product_price') * 100, // Convert to cents
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['product' => $request->input('product_id')]),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return response()->json(['id' => $session->id]);
    }
}
