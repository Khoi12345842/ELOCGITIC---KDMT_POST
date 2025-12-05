<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Staff\OrderController as StaffOrderController;
use App\Http\Controllers\Staff\Auth\LoginController as StaffLoginController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\IncidentController;
use App\Http\Controllers\Staff\InfoController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::prefix('staff')->name('staff.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [StaffLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [StaffLoginController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'role:staff'])->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [StaffOrderController::class, 'index'])->name('orders.index');
    Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/info', [InfoController::class, 'index'])->name('info');
    Route::get('/info/genz', [InfoController::class, 'genz'])->name('info.genz');
        
        // Status update routes
        Route::get('/orders/{order}/status', [\App\Http\Controllers\Staff\StatusUpdateController::class, 'edit'])->name('orders.status.edit');
        Route::put('/orders/{order}/status', [\App\Http\Controllers\Staff\StatusUpdateController::class, 'update'])->name('orders.status.update');
    });
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User management
        Route::resource('users', AdminUserController::class);
        Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Order management (Admin-specific with higher privileges)
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/assign-staff', [\App\Http\Controllers\Admin\OrderController::class, 'assignStaff'])->name('orders.assign-staff');
        Route::put('/orders/{order}/update-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('/orders/bulk-assign', [\App\Http\Controllers\Admin\OrderController::class, 'bulkAssign'])->name('orders.bulk-assign');
        
        // Incidents
        Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
        Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
        
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    });
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

    // Notifications routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    
    // Settings routes
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
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

