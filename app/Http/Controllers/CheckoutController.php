<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;


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

}
