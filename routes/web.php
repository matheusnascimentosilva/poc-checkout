<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/checkout', function () {
    return Inertia::render('Checkout');
});

Route::get('/cart', function () {
    return Inertia::render('Cart');
});

Route::get('/product/{id}', function ($id) {
    return Inertia::render('Product', ['id' => $id]);
});

Route::get('/login', function () {
    return Inertia::render('Login');
});
