<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Public routes (không cần login)
Route::get('/track', [OrderController::class, 'track'])->name('orders.track');
Route::get('/estimate', [OrderController::class, 'estimate'])->name('orders.estimate');

// Post office routes
Route::get('/post-offices', [\App\Http\Controllers\PostOfficeController::class, 'index'])->name('post-offices.index');

// Support routes
Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::get('/support/faq', [SupportController::class, 'faq'])->name('support.faq');
Route::get('/support/contact', [SupportController::class, 'contact'])->name('support.contact');
Route::post('/support/contact', [SupportController::class, 'submitContact'])->name('support.contact.submit');

// Routes cho khách hàng (cần đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    
    // Settings routes
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::put('/settings/notifications', [\App\Http\Controllers\SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    
    // Tạo đơn hàng cho individual
    Route::get('/orders/create/individual', [\App\Http\Controllers\CreateOrderController::class, 'showIndividualForm'])
        ->name('orders.create.individual');
    Route::post('/orders/create/individual', [\App\Http\Controllers\CreateOrderController::class, 'storeIndividual'])
        ->name('orders.create.individual.store');
    
    // Tạo đơn theo lô cho business (chỉ business user)
    Route::middleware('user.type:business')->group(function () {
        Route::get('/orders/create/bulk', [\App\Http\Controllers\CreateOrderController::class, 'showBulkForm'])
            ->name('orders.create.bulk');
        Route::post('/orders/create/bulk', [\App\Http\Controllers\CreateOrderController::class, 'storeBulk'])
            ->name('orders.create.bulk.store');
        
        // Shop linking routes (chỉ business user)
        Route::get('/shop/link', [\App\Http\Controllers\ShopController::class, 'showLinkForm'])
            ->name('shop.link');
        Route::post('/shop/link', [\App\Http\Controllers\ShopController::class, 'linkShop'])
            ->name('shop.link.store');
        Route::get('/shop/dashboard', [\App\Http\Controllers\ShopController::class, 'dashboard'])
            ->name('shop.dashboard');
        Route::post('/shop/sync', [\App\Http\Controllers\ShopController::class, 'syncOrders'])
            ->name('shop.sync');
    });
});

