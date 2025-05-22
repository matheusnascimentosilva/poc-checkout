<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', auth()->id())->get();
        return Inertia::render('Products/Index', compact('products'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return Inertia::render('Products/Edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'photo' => 'required|url',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($data);

        return Redirect::route('products.index');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return Redirect::route('products.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'photo' => 'required|url',
            'price' => 'required|numeric|min:0',
        ]);

        auth()->user()->products()->create($data);

        return redirect()->route('products.index');
    }

}
