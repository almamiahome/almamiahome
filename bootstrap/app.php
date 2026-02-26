<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$themeName = 'anchor';
$themeJsonPath = dirname(__DIR__).'/theme.json';

if (is_file($themeJsonPath)) {
    $themeJson = json_decode((string) file_get_contents($themeJsonPath), true);

    if (is_array($themeJson) && isset($themeJson['name']) && is_string($themeJson['name']) && $themeJson['name'] !== '') {
        $themeName = $themeJson['name'];
    }
}

$folioPagesPath = dirname(__DIR__)."/resources/themes/{$themeName}/pages";

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Lab404\Impersonate\ImpersonateServiceProvider::class,
        \Wave\WaveServiceProvider::class,
        \DevDojo\Themes\ThemesServiceProvider::class,
        \DevDojo\Themes\ThemesServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        pages: is_dir($folioPagesPath) ? $folioPagesPath : null,
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->encryptCookies(except: [
            'theme',
        ]);
        $middleware->validateCsrfTokens(except: [
            '/webhook/paddle',
            '/webhook/stripe',
        ]);

        $middleware->append(\Filament\Http\Middleware\DisableBladeIconComponents::class);

        $middleware->web(\RalphJSmit\Livewire\Urls\Middleware\LivewireUrlsMiddleware::class);

        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
