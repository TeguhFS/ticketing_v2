<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\FieldOfficerController as AdminFieldOfficerController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

// Controller Officer
use App\Http\Controllers\Officer\AuthController as OfficerAuthController;
use App\Http\Controllers\Officer\DashboardController as OfficerDashboardController;
use App\Http\Controllers\Officer\HistoryController as OfficerHistoryController;
use App\Http\Controllers\Officer\ProfileController as OfficerProfileController;
use App\Http\Controllers\Officer\ScanController as OfficerScanController;

// Route Public
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/events', [\App\Http\Controllers\User\EventController::class, 'index'])->name('events.index');
Route::get('/blogs', [\App\Http\Controllers\User\BlogController::class, 'index'])->name('blogs.index');
Route::get('/faqs', [\App\Http\Controllers\User\FaqController::class, 'index'])->name('faqs.index');

// ─── User Authenticated Routes ────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets', [\App\Http\Controllers\User\TicketController::class, 'index'])->name('tickets');
    Route::get('/orders', [\App\Http\Controllers\User\OrderController::class, 'index'])->name('orders');
});

// Route untuk Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Event
    Route::resource('events', AdminEventController::class);

    // Ticket
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');

    // Order
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::resource('orders', AdminOrderController::class);
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Category
    Route::resource('categories', AdminCategoryController::class)
        ->except(['create', 'show', 'edit']);

    // Transaction
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [AdminTransactionController::class, 'export'])->name('transactions.export');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/methods', [AdminPaymentController::class, 'methods'])->name('methods');
        Route::post('/methods', [AdminPaymentController::class, 'storeMethod'])->name('methods.store');
        Route::put('/methods/{method}', [AdminPaymentController::class, 'updateMethod'])->name('methods.update');
        Route::delete('/methods/{method}', [AdminPaymentController::class, 'destroyMethod'])->name('methods.destroy');
        Route::post('/methods/{method}/bank-accounts', [AdminPaymentController::class, 'storeBankAccount'])->name('bank-accounts.store');
        Route::delete('/bank-accounts/{bankAccount}', [AdminPaymentController::class, 'destroyBankAccount'])->name('bank-accounts.destroy');
        Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('show');
        Route::patch('/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('verify');
        Route::patch('/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('reject');
    });

    // Blog
    Route::resource('blogs', AdminBlogController::class);

    // FAQ
    Route::get('/faqs', [AdminFaqController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [AdminFaqController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/{faq}', [AdminFaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [AdminFaqController::class, 'destroy'])->name('faqs.destroy');
    Route::post('/faqs/reorder', [AdminFaqController::class, 'reorder'])->name('faqs.reorder');

    // Users
    Route::resource('users', AdminUserController::class);
    Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Field Officers
    Route::get('/officers', [AdminFieldOfficerController::class, 'index'])->name('officers.index');
    Route::post('/officers', [AdminFieldOfficerController::class, 'store'])->name('officers.store');
    Route::get('/officers/{officer}', [AdminFieldOfficerController::class, 'show'])->name('officers.show');
    Route::put('/officers/{officer}', [AdminFieldOfficerController::class, 'update'])->name('officers.update');
    Route::delete('/officers/{officer}', [AdminFieldOfficerController::class, 'destroy'])->name('officers.destroy');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingController::class, 'index'])->name('index');
        Route::patch('/general', [AdminSettingController::class, 'updateGeneral'])->name('general.update');
        Route::patch('/social', [AdminSettingController::class, 'updateSocial'])->name('social.update');
        Route::patch('/seo', [AdminSettingController::class, 'updateSeo'])->name('seo.update');
    });
});

// ─── Officer Routes ───────────────────────────────────────────────
Route::prefix('officer')->name('officer.')->group(function () {

    // Auth
    Route::get('/login', [OfficerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [OfficerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [OfficerAuthController::class, 'logout'])->name('logout');

    // Protected
    Route::middleware(['auth', 'officer'])->group(function () {
        Route::get('/dashboard', [OfficerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/scan', [OfficerScanController::class, 'index'])->name('scan');
        Route::post('/scan/validate', [OfficerScanController::class, 'validate'])->name('scan.validate');
        Route::get('/history', [OfficerHistoryController::class, 'index'])->name('history');
        Route::get('/profile',  [OfficerProfileController::class, 'index'])->name('profile');
        Route::patch('/profile',  [OfficerProfileController::class, 'update'])->name('profile.update');
    });
});

// Route untuk User biasa
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
