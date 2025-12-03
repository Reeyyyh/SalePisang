<?php



/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\CartProductController;
use App\Http\Controllers\web\CategoryController;
use App\Http\Controllers\web\OrderController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\ProfileController;
use App\Http\Controllers\web\ResetPasswordMailController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Web\TripayController;
use App\Http\Controllers\web\VerificationMailContorller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landingpage');
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/detail-product/{sku}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/category/{slug}', [CategoryController::class, 'showByCategory'])->name('byCategory');
Route::get('/store-location', function () {
    return view('store.store-location');
})->name('store-location');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/search', [AuthController::class, 'search'])->name('search');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.redirect:user'])->group(function () {
    // Product
    Route::get('/search', [ProductController::class, 'search'])->name('search');

    // Profil pengguna
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/address', [ProfileController::class, 'address'])->name('profile.address');
    Route::get('/profile/history', [ProfileController::class, 'history'])->name('profile.history');
    Route::get('/profile/history-detail/{invoice}', [ProfileController::class, 'orderDetail'])->name('profile.history.detail');
    Route::post('/profile/address/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.setDefault');

    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/address/add', [ProfileController::class, 'addAddress'])->name('profile.address.add');
    Route::put('/profile/address/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::delete('/profile/address/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');

    // Keranjang
    Route::get('/cart', [CartProductController::class, 'getUserCart'])->name('cart');
    Route::post('/cart/add', [CartProductController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartProductController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/delete/{id}', [CartProductController::class, 'deleteCart'])->name('cart.delete');

    // Checkout & Pembayaran
    Route::get('/checkout', [OrderController::class, 'checkoutForm'])->name('checkout.form');
    Route::get('/order/cancel/{uuid}', [OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/order/{uuid}/waiting', [OrderController::class, 'waitingPayment'])->name('order.waiting');
    Route::get('/checkout/snap/{uuid}', [OrderController::class, 'snapPage'])->name('checkout.snap');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Filament)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin-dashboard')->group(function () {
    // Filament admin panel otomatis
});

/*
|--------------------------------------------------------------------------
| Seller Routes (Filament)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:seller'])->prefix('seller-dashboard')->name('seller.')->group(function () {
    // Dashboard
    Route::get('/', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [SellerProductController::class, 'index'])->name('index');
        Route::get('/create', [SellerProductController::class, 'create'])->name('create');
        Route::post('/store', [SellerProductController::class, 'store'])->name('store');
        Route::get('{id}/edit', [SellerProductController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [SellerProductController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [SellerProductController::class, 'destroy'])->name('destroy');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [SellerOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [SellerOrderController::class, 'show'])->name('show');
        Route::put('/{id}/update-status', [SellerOrderController::class, 'updateStatus'])->name('updateStatus');
    });
});

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/
Route::get('/email/verify/{id}/{hash}', [VerificationMailContorller::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/resend', [VerificationMailContorller::class, 'resendVerificationEmail'])->name('verification.resend');

/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [ResetPasswordMailController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ResetPasswordMailController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [ResetPasswordMailController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordMailController::class, 'resetPassword'])->name('password.update');
Route::get('/reset-password/expired', function () {
    return view('auth.reset-expired');
})->name('password.link.expired');

/*
|--------------------------------------------------------------------------
| Tripay Callback
|--------------------------------------------------------------------------
*/
Route::post('/callback/tripay', [TripayController::class, 'handleCallback'])->name('tripay.callback');

Route::get('/debug-user', function () {
    return response()->json(Auth::user());
});
