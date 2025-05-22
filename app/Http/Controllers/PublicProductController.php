<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class PublicProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return Inertia::render('Public/Home', [
            'products' => $products,
        ]);
    }
}
