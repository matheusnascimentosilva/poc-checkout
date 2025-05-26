<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return Inertia::render('Products/Index', compact('products'));
    }

    public function edit(Product $product)
    {
        if (Auth::id() !== $product->user_id) {
            abort(403, 'Não autorizado.');
        }
        return Inertia::render('Products/Edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (Auth::id() !== $product->user_id) {
            abort(403, 'Não autorizado.');
        }

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

        $data['user_id'] = Auth::id();

        Product::create($data);
        session()->flash('success', 'Product created successfully.');

        return redirect()->route('products.index');
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

}
