<?php

use App\Models\CierreCampana;
use App\Models\LiquidacionCierre;
use App\Services\LiquidacionCierreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('aplica descuentos futuros con idempotencia por origen destino motivo', function () {
    DB::table('users')->insert([
        'id' => 2,
        'name' => 'Lider Demo',
        'email' => 'lider@example.com',
        'password' => bcrypt('secret'),
        'username' => 'lider-demo',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('users')->insert([
        'id' => 3,
        'name' => 'Coord Demo',
        'email' => 'coord@example.com',
        'password' => bcrypt('secret'),
        'username' => 'coord-demo',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $cierreOrigen = CierreCampana::create([
        'nombre' => 'Cierre 1',
        'codigo' => '2026-C1',
        'estado' => 'cerrado',
    ]);

    $cierreDestino = CierreCampana::create([
        'nombre' => 'Cierre 2',
        'codigo' => '2026-C2',
        'estado' => 'en_liquidacion',
    ]);

    $liquidacionOrigen = LiquidacionCierre::create([
        'cierre_campana_id' => $cierreOrigen->id,
        'lider_id' => 2,
        'coordinadora_id' => 3,
        'saldo_a_cobrar' => 50000,
        'saldo_a_pagar' => 25000,
        'deuda_arrastrada' => 5000,
        'descuento_aplicado' => 0,
        'balance_neto' => 20000,
        'estado' => 'auditada',
    ]);

    $service = app(LiquidacionCierreService::class);

    $descuento1 = $service->registrarDescuentoFuturo($liquidacionOrigen, $cierreDestino, 'arrastre_positivo', 3000);
    $descuento2 = $service->registrarDescuentoFuturo($liquidacionOrigen, $cierreDestino, 'arrastre_positivo', 3000);

    expect($descuento1->id)->toBe($descuento2->id);

    $aplicados = $service->aplicarDescuentosFuturosAlCierre($cierreDestino);

    expect($aplicados)->toBe(1);

    $liquidacionDestino = LiquidacionCierre::query()
        ->where('cierre_campana_id', $cierreDestino->id)
        ->where('lider_id', 2)
        ->first();

    expect($liquidacionDestino)->not->toBeNull();
    expect((float) $liquidacionDestino->descuento_aplicado)->toBe(3000.0);
});
