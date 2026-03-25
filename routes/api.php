<?php

use App\Http\Controllers\Api\ReporteriaFinanzasController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return auth()->user();
});

Wave::api();

// Posts Example API Route
Route::middleware('auth:api')->group(function () {
    Route::get('/posts', [\App\Http\Controllers\Api\ApiController::class, 'posts']);
});

Route::middleware('auth:api')->prefix('reportes')->group(function () {
    Route::get('/lideres', [ReporteriaFinanzasController::class, 'resumenLideres']);
    Route::get('/coordinadoras', [ReporteriaFinanzasController::class, 'resumenCoordinadoras']);
    Route::get('/cierres', [ReporteriaFinanzasController::class, 'resumenCierres']);
    Route::get('/lideres/{liderId}/timeline', [ReporteriaFinanzasController::class, 'timelineIndividual']);
    Route::get('/comparativa/export', [ReporteriaFinanzasController::class, 'exportarComparativa']);
    Route::post('/cierres/{cierre}/aplicar-descuentos', [ReporteriaFinanzasController::class, 'aplicarDescuentos']);
    Route::get('/liquidaciones/{liquidacion}', [ReporteriaFinanzasController::class, 'liquidacionDetalle']);
});
