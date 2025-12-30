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

    Route::get('/', function () {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    });

    // 2. ROUTE TAMU (Guest Middleware)
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

        Route::get('/force-fill-categories', function () {
            $user = Illuminate\Support\Facades\Auth::user();


            $defaults = [
                ['name' => 'Makan & Minum', 'type' => 'expense'],
                ['name' => 'Transportasi', 'type' => 'expense'],
                ['name' => 'Belanja', 'type' => 'expense'],
                ['name' => 'Tagihan', 'type' => 'expense'],
                ['name' => 'Hiburan', 'type' => 'expense'],
                ['name' => 'Kesehatan', 'type' => 'expense'],
                ['name' => 'Gaji', 'type' => 'income'],
                ['name' => 'Bonus', 'type' => 'income'],
            ];

            $count = 0;
            foreach ($defaults as $cat) {
                $exists = \App\Models\Category::where('user_id', $user->id)
                    ->where('name', $cat['name'])
                    ->exists();

                if (!$exists) {
                    \App\Models\Category::create([
                        'user_id' => $user->id,
                        'name' => $cat['name'],
                        'type' => $cat['type']
                    ]);
                    $count++;
                }
            }

            return redirect()->route('transactions.index')
                ->with('success', "Berhasil memulihkan $count kategori! Coba cek dropdown sekarang.");
        })->middleware('auth');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
        Route::post('/wallets', [WalletController::class, 'store'])->name('wallets.store');
        Route::delete('/wallets/{id}', [WalletController::class, 'destroy'])->name('wallets.destroy');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

        Route::get('/goals', [SavingGoalController::class, 'index'])->name('goals.index');
        Route::post('/goals', [SavingGoalController::class, 'store'])->name('goals.store');
        Route::post('/goals/{id}/allocate', [SavingGoalController::class, 'storeAllocation'])->name('goals.allocate');
        Route::put('/goals/{id}', [SavingGoalController::class, 'update'])->name('goals.update');
        Route::delete('/goals/{id}', [SavingGoalController::class, 'destroy'])->name('goals.destroy');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('/notifications/read-all', function () {
            Auth::user()->unreadNotifications->markAsRead();
            return redirect()->back();
        })->name('notifications.readAll');
    });
