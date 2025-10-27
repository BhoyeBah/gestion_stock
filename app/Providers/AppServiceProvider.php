<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrapFive();

        Gate::before(function ($user, $ability) {
            // Si c'est toi (par ID ou email), tu bypass toutes les permissions
            if ($user && $user->is_owner && $user->is_platform_user()) {
                return true;
            }
        });

    }
}
