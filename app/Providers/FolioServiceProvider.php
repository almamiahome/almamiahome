<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;

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
        $theme = $this->resolveThemeName();
        $themePagesPath = resource_path("themes/{$theme}/pages");

        if (File::isDirectory($themePagesPath)) {
            Folio::path($themePagesPath)->middleware([
                '*' => [
                    //
                ],
            ]);
        }
    }

    private function resolveThemeName(): string
    {
        $themeJsonPath = base_path('theme.json');

        if (! File::exists($themeJsonPath)) {
            return 'anchor';
        }

        $themeJson = json_decode(File::get($themeJsonPath), true);

        if (! is_array($themeJson)) {
            return 'anchor';
        }

        return $themeJson['name'] ?? 'anchor';
    }
}
