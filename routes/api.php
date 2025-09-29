<?php

use App\Http\Controllers\Api\ApiAuth;
use App\Http\Controllers\api\ApiCartProduct;
use App\Http\Controllers\api\ApiCategory;
use App\Http\Controllers\api\ApiOrder;
use App\Http\Controllers\api\ApiProducts;
use App\Http\Controllers\api\ApiProfile;
use App\Http\Controllers\api\ApiResetPasswordMail;
use App\Http\Controllers\api\ApiVerificationMail;
use App\Http\Controllers\api\ApiMidtransController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// REGISTER DAN LOGIN
Route::post('auth/register', [ApiAuth::class, 'register']);
Route::post('auth/login', [ApiAuth::class, 'login']);

// Email Verification
Route::get('/email/verify/{id}/{hash}', [ApiVerificationMail::class, 'verifyEmail'])
    ->name('verification.verify.api');

// Email resend verification
Route::post('/resend-verification-email', [ApiVerificationMail::class, 'resendVerificationEmail']);


// Email Reset Password
Route::post('auth/forgot-password', [ApiResetPasswordMail::class, 'forgotPassword']);
Route::post('auth/reset-password', [ApiResetPasswordMail::class, 'resetPassword']);

// ROUTE YANG BUTUH LOGIN (PAKAI TOKEN)
Route::middleware('auth:sanctum')->group(function () {

    // Profile & Authenticated User Routes
    Route::get('/profile', [ApiProfile::class, 'profile']);
    Route::put('/profile', [ApiProfile::class, 'updateProfile']);
    Route::post('auth/logout', [ApiAuth::class, 'logout']);

    // Cart Routes
    Route::get('/cart', [ApiCartProduct::class, 'getUserCart']);
    Route::put('/cart/{id}', [ApiCartProduct::class, 'updateCartItem']);
    Route::delete('/cart/{id}', [ApiCartProduct::class, 'deleteCartItem']);
    Route::post('/cart/add', [ApiCartProduct::class, 'addToCart']);

    // Order Routes
    Route::get('/order', [ApiOrder::class, 'getUserOrder']);
    Route::get('/order/{id}', [ApiOrder::class, 'getOrderById']);

    Route::post('/order', [ApiOrder::class, 'storeOrder']);

    // Midtrans Transaction (Harus login untuk buat transaksi)
    Route::post('/midtrans/transaction', [ApiMidtransController::class, 'createTransaction']);
});

// Route NOTIFIKASI MIDTRANS (Dibuka untuk webhook Midtrans)
Route::post('/midtrans/notification', [ApiMidtransController::class, 'notificationHandler']);

// Route NOTIFIKASI MIDTRANS (Payment Success)
Route::get('/payment/success', [ApiMidtransController::class, 'paymentSuccess']);


// Product route (Bisa diakses tanpa login)
Route::get('/products', [ApiProducts::class, 'getAllProducts']);
Route::post('/products', [ApiProducts::class, 'storeProduct']);
Route::get('/products/{id}', [ApiProducts::class, 'getProductById']);

// Category route (Bisa diakses tanpa login)
Route::get('/category', [ApiCategory::class, 'getAllCategories']);
Route::post('/category', [ApiCategory::class, 'storeCategory']);
Route::get('/category/{id}', [ApiCategory::class, 'getCategoryById']);
