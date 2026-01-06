<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\AdminController;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    Route::get('/', function () {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);

        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::middleware('role:user')->group(function () {

            // Wallets
            Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
            Route::post('/wallets', [WalletController::class, 'store'])->name('wallets.store');
            Route::delete('/wallets/{id}', [WalletController::class, 'destroy'])->name('wallets.destroy');

            // Transactions
            Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
            Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
            Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

            // Saving Goals
            Route::get('/goals', [SavingGoalController::class, 'index'])->name('goals.index');
            Route::post('/goals', [SavingGoalController::class, 'store'])->name('goals.store');
            Route::post('/goals/{id}/allocate', [SavingGoalController::class, 'storeAllocation'])->name('goals.allocate');
            Route::put('/goals/{id}', [SavingGoalController::class, 'update'])->name('goals.update');
            Route::delete('/goals/{id}', [SavingGoalController::class, 'destroy'])->name('goals.destroy');

            // Reports
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

            // Notifications
            Route::get('/notifications/read-all', function () {
                Auth::user()->unreadNotifications->markAsRead();
                return redirect()->back();
            })->name('notifications.readAll');

            // Profile
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
            Route::get('/users', [AdminController::class, 'users'])->name('users.index');
            Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create'); 
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
            Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        });
    });
});
