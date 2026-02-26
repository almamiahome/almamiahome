<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FolioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // El montaje de páginas Folio se define en bootstrap/app.php
    }
}
