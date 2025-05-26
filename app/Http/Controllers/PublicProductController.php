<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PublicProductController extends Controller
{
    public function index()
    {
        $products = Product::all()->toArray();
        Log::info('Produtos enviados para o frontend:', $products);
        return Inertia::render('Public/Home', ['products' => $products]);
    }
}

// The only change is that we are now using the Inertia::render() method to render the Inertia template instead of the old Inertia::render() method. We are also passing the products array as a prop to the Inertia template.
