<?php

use App\Models\Bread;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// 1. Landing Page
Route::get('/', function () {
    $categories = \App\Models\Category::all();
    $produkTerlaris = collect();
    foreach ($categories as $category) {
        $bread = Bread::query()
            ->select('breads.*', DB::raw('COALESCE(sales.total_terjual, 0) as total_terjual'))
            ->leftJoin(DB::raw('(SELECT bread_id, SUM(quantity) as total_terjual FROM order_items GROUP BY bread_id) as sales'), 'breads.id', '=', 'sales.bread_id')
            ->with('category')
            ->where('breads.category_id', $category->id)
            ->where('breads.stock', '>', 0)
            ->orderByDesc('total_terjual')
            ->orderByDesc('breads.id')
            ->first();

        if ($bread) {
            $produkTerlaris->push($bread);
        }
    }

    $menuBaru = Bread::query()
        ->with('category')
        ->where('stock', '>', 0)
        ->orderByDesc('id')
        ->limit(4)
        ->get();

    return view('welcome', compact('produkTerlaris', 'menuBaru'));
})->name('beranda');
Route::get('/tentang-kami', function () {
    return view('tentang-kami');
})->name('tentang-kami');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/roti/{id}', [\App\Http\Controllers\BreadController::class, 'show'])->name('breads.show');
Route::middleware('auth')->group(function () {
    Route::get('/akun', [AccountController::class, 'profile'])->name('account.profile');
    Route::put('/akun/profil', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/akun/alamat', [AccountController::class, 'address'])->name('account.address');
    Route::put('/akun/alamat', [AccountController::class, 'updateAddress'])->name('account.address.update');
    Route::post('/akun/alamat', [AccountController::class, 'storeAddress'])->name('account.address.store');
    Route::put('/akun/alamat/{address}/default', [AccountController::class, 'setDefaultAddress'])->name('account.address.default');
    Route::delete('/akun/alamat/{address}', [AccountController::class, 'deleteAddress'])->name('account.address.delete');
    Route::get('/akun/riwayat-pesanan', [AccountController::class, 'orders'])->name('account.orders');
});

Route::get('/my-orders', function () {
    return redirect()->route('account.orders');
})->name('orders.history')->middleware('auth');
Route::get('/my-orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show')->middleware('auth');

// 2. Checkout & Pembayaran (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout/item', [OrderController::class, 'updateCheckoutItem'])->name('checkout.item');
    Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/checkout/payment/{order}', [OrderController::class, 'paymentPage'])->name('checkout.payment');
    Route::post('/checkout/payment/{order}/confirm', [OrderController::class, 'confirmPayment'])->name('checkout.payment.confirm');
});

// 3. Autentikasi (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
});

// 4. Logout (Auth Only)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');