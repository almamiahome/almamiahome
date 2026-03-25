<?php

use App\Models\CierreCampana;
use App\Models\Cobro;
use App\Models\LiquidacionCierre;
use App\Models\Pago;
use App\Models\Pedido;
use App\Services\LiquidacionCierreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function crearUsuarioDemo(int $id, string $nombre, string $email, ?int $coordinadoraId = null): void
{
    DB::table('users')->insert([
        'id' => $id,
        'name' => $nombre,
        'email' => $email,
        'password' => bcrypt('secret'),
        'username' => str($email)->before('@')->value(),
        'coordinadora_id' => $coordinadoraId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function crearPedidoDemo(int $id, int $liderId, int $coordinadoraId, int $vendedoraId): Pedido
{
    return Pedido::query()->create([
        'id' => $id,
        'codigo_pedido' => 'PED-'.$id,
        'vendedora_id' => $vendedoraId,
        'lider_id' => $liderId,
        'coordinadora_id' => $coordinadoraId,
        'responsable_id' => $liderId,
        'fecha' => now()->toDateString(),
        'mes' => now()->format('Y-m'),
        'catalogo_nro' => 'CAT-1',
        'total_precio_catalogo' => 1000,
        'total_gastos' => 100,
        'total_ganancias' => 200,
        'total_a_pagar' => 900,
        'total_puntos' => 10,
        'cantidad_unidades' => 2,
        'unidades_facturables' => 2,
        'unidades_auxiliares' => 0,
        'estado' => 'pendiente',
        'estado_pago' => 'pendiente',
    ]);
}

it('T17 calcula deuda acumulada, descuento futuro y balance neto en cierres sucesivos', function () {
    crearUsuarioDemo(2, 'Lider Demo', 'lider@example.com', 3);
    crearUsuarioDemo(3, 'Coord Demo', 'coord@example.com');
    crearUsuarioDemo(4, 'Vendedora Demo', 'vendedora@example.com');

    $cierre1 = CierreCampana::create([
        'nombre' => 'Cierre 1',
        'codigo' => '2026-C1',
        'estado' => 'cerrado',
    ]);

    $cierre2 = CierreCampana::create([
        'nombre' => 'Cierre 2',
        'codigo' => '2026-C2',
        'estado' => 'en_liquidacion',
    ]);

    $pedidoC1 = crearPedidoDemo(101, 2, 3, 4);

    Cobro::create([
        'pedido_id' => $pedidoC1->id,
        'lider_id' => 2,
        'coordinadora_id' => 3,
        'mes_campana' => '2026-C1',
        'monto' => 10000,
        'estado' => 'pagado',
    ]);

    Pago::create([
        'pedido_id' => $pedidoC1->id,
        'vendedora_id' => 4,
        'mes_campana' => '2026-C1',
        'monto' => 15000,
        'estado' => 'pagado',
    ]);

    $servicio = app(LiquidacionCierreService::class);
    $liquidacionC1 = $servicio->liquidarPorLider($cierre1, \App\Models\User::query()->findOrFail(2));

    expect($liquidacionC1->deuda_arrastrada)->toBe(0.0)
        ->and($liquidacionC1->balance_neto)->toBe(-5000.0);

    $descuento1 = $servicio->registrarDescuentoFuturo($liquidacionC1, $cierre2, 'arrastre_positivo', 2000);
    $descuento2 = $servicio->registrarDescuentoFuturo($liquidacionC1, $cierre2, 'arrastre_positivo', 2000);

    expect($descuento1->id)->toBe($descuento2->id);
    expect($servicio->aplicarDescuentosFuturosAlCierre($cierre2))->toBe(1);

    $pedidoC2 = crearPedidoDemo(102, 2, 3, 4);

    Cobro::create([
        'pedido_id' => $pedidoC2->id,
        'lider_id' => 2,
        'coordinadora_id' => 3,
        'mes_campana' => '2026-C2',
        'monto' => 20000,
        'estado' => 'pagado',
    ]);

    Pago::create([
        'pedido_id' => $pedidoC2->id,
        'vendedora_id' => 4,
        'mes_campana' => '2026-C2',
        'monto' => 7000,
        'estado' => 'pagado',
    ]);

    $liquidacionC2 = $servicio->liquidarPorLider($cierre2, \App\Models\User::query()->findOrFail(2));

    expect($liquidacionC2->deuda_arrastrada)->toBe(5000.0)
        ->and($liquidacionC2->descuento_aplicado)->toBe(2000.0)
        ->and($liquidacionC2->balance_neto)->toBe(10000.0);
});

it('T17 mantiene idempotencia al reprocesar aplicación de descuentos sin duplicar impacto', function () {
    crearUsuarioDemo(12, 'Lider Demo', 'lider12@example.com', 13);
    crearUsuarioDemo(13, 'Coord Demo', 'coord13@example.com');

    $cierreOrigen = CierreCampana::create([
        'nombre' => 'Cierre 10',
        'codigo' => '2026-C10',
        'estado' => 'cerrado',
    ]);

    $cierreDestino = CierreCampana::create([
        'nombre' => 'Cierre 11',
        'codigo' => '2026-C11',
        'estado' => 'en_liquidacion',
    ]);

    $liquidacionOrigen = LiquidacionCierre::create([
        'cierre_campana_id' => $cierreOrigen->id,
        'lider_id' => 12,
        'coordinadora_id' => 13,
        'saldo_a_cobrar' => 50000,
        'saldo_a_pagar' => 25000,
        'deuda_arrastrada' => 5000,
        'descuento_aplicado' => 0,
        'balance_neto' => 20000,
        'estado' => 'auditada',
    ]);

    $service = app(LiquidacionCierreService::class);

    $service->registrarDescuentoFuturo($liquidacionOrigen, $cierreDestino, 'arrastre_positivo', 3000);
    expect($service->aplicarDescuentosFuturosAlCierre($cierreDestino))->toBe(1)
        ->and($service->aplicarDescuentosFuturosAlCierre($cierreDestino))->toBe(0);

    $liquidacionDestino = LiquidacionCierre::query()
        ->where('cierre_campana_id', $cierreDestino->id)
        ->where('lider_id', 12)
        ->first();

    expect($liquidacionDestino)->not->toBeNull();
    expect((float) $liquidacionDestino->descuento_aplicado)->toBe(3000.0)
        ->and((float) $liquidacionDestino->balance_neto)->toBe(3000.0);
});
