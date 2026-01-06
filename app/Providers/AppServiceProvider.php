<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tambahkan logika ini untuk Vercel:
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();
        User::observe(UserObserver::class);
    }
}
