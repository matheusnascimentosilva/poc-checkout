<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\Webhook\PaymentWebhookController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/buy/{id}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/buy/{id}', [CheckoutController::class, 'store'])->name('checkout.store');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::post('/checkout/{product}', [CheckoutController::class, 'createCheckoutSession'])->name('checkout.create');

Route::post('/checkout/create/{product}', [StripeCheckoutController::class, 'create'])->name('checkout.create');

Route::get('/checkout/success/{product}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::get('/checkout/success/{product}', fn() => inertia('Checkout/Success'))->name('checkout.success');
Route::get('/checkout/cancel/{product}', fn() => inertia('Checkout/Cancel'))->name('checkout.cancel');

Route::post('/webhook/payment', [PaymentWebhookController::class, 'handle']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rota para acessar o painel de administração dos produtos
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
});

// Rota para acessar os produtos públicos
Route::get('/', [PublicProductController::class, 'index'])->name('public.home');

require __DIR__.'/auth.php';
