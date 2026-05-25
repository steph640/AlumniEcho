<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Utilise notre pagination Bootstrap 5 personnalisée en français
        Paginator::defaultView('vendor.pagination.bootstrap-5');
    }
}
