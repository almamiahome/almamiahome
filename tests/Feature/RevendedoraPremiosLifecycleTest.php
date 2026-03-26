<?php

use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\RevendedoraPunto;
use App\Models\RevendedoraRacha;
use App\Models\TiendaPremio;
use App\Models\User;
use App\Services\PremiosRevendedoraService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function cargarFixtureJson(string $archivo): array
{
    $ruta = database_path("seeders/fixtures/{$archivo}");

    return json_decode(file_get_contents($ruta), true, 512, JSON_THROW_ON_ERROR);
}

it('T7 mantiene racha de 3 cierres consecutivos con recálculo idempotente por cierre', function () {
    $fixture = cargarFixtureJson('catalogo_cierre_base_v2.json');

    $servicio = app(PremiosRevendedoraService::class);
    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create($fixture['catalogo']);

    $cierres = collect($fixture['cierres'])->map(function (array $datos) use ($catalogo) {
        return CierreCampana::query()->create(array_merge($datos, ['catalogo_id' => $catalogo->id]));
    });

    foreach ($cierres as $cierre) {
        $servicio->procesarRacha($revendedora, $cierre, true);
    }

    $servicio->procesarRacha($revendedora, $cierres->last(), true);

    $ultimaRacha = RevendedoraRacha::query()
        ->where('user_id', $revendedora->id)
        ->latest('id')
        ->firstOrFail();

    expect(RevendedoraRacha::query()->count())->toBe(3)
        ->and($ultimaRacha->estado)->toBe('premiada')
        ->and($ultimaRacha->racha_actual)->toBe(3)
        ->and($ultimaRacha->mejor_racha)->toBe(3);
});

it('T7+T8+T9 ejecuta flujo completo de cierres consecutivos con saldo y canje consistentes', function () {
    $fixture = cargarFixtureJson('catalogo_cierre_base_v2.json');

    $servicio = app(PremiosRevendedoraService::class);
    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create($fixture['catalogo']);

    $cierres = collect($fixture['cierres'])->map(function (array $datos) use ($catalogo) {
        return CierreCampana::query()->create(array_merge($datos, ['catalogo_id' => $catalogo->id]));
    });

    foreach ($cierres as $indice => $cierre) {
        $servicio->procesarRacha($revendedora, $cierre, true, ['indice' => $indice + 1]);
        $servicio->registrarMovimientoPuntos(
            revendedora: $revendedora,
            cierre: $cierre,
            puntos: 100,
            estado: 'confirmado',
            origen: 'pedido',
            motivo: 'Acumulación por cierre consecutivo',
            idempotenciaClave: 'puntos-cierre-' . $cierre->id,
        );
    }

    $premio = TiendaPremio::query()->create([
        'user_id' => $revendedora->id,
        'catalogo_id' => $catalogo->id,
        'cierre_id' => $cierres->last()->id,
        'estado' => 'activo',
        'nombre' => 'Kit de continuidad',
        'descripcion' => 'Premio de integración T7+T8+T9',
        'puntos_requeridos' => 120,
        'stock' => 2,
        'origen' => 'fixture',
    ]);

    $canje = $servicio->ejecutarCanje($revendedora, $premio, $cierres->last());

    expect($canje->estado)->toBe('aprobado')
        ->and($servicio->saldoPuntos($revendedora, $catalogo))->toBe(180)
        ->and(RevendedoraPunto::query()->where('user_id', $revendedora->id)->count())->toBe(4)
        ->and(RevendedoraRacha::query()->where('user_id', $revendedora->id)->latest('id')->value('estado'))->toBe('premiada');
});

it('acumula puntos con idempotencia por clave de operación', function () {
    $servicio = app(PremiosRevendedoraService::class);
    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create(['nombre' => 'Catálogo prueba']);
    $cierre = CierreCampana::query()->create([
        'nombre' => 'Cierre 1',
        'codigo' => 'CAMP-TST-1',
        'catalogo_id' => $catalogo->id,
        'numero_cierre' => 1,
    ]);

    $servicio->registrarMovimientoPuntos(
        revendedora: $revendedora,
        cierre: $cierre,
        puntos: 80,
        estado: 'confirmado',
        origen: 'test',
        motivo: 'Primer registro',
        idempotenciaClave: 'operacion-unica',
    );

    $servicio->registrarMovimientoPuntos(
        revendedora: $revendedora,
        cierre: $cierre,
        puntos: 80,
        estado: 'confirmado',
        origen: 'test',
        motivo: 'Reproceso',
        idempotenciaClave: 'operacion-unica',
    );

    expect(RevendedoraPunto::query()->count())->toBe(1)
        ->and($servicio->saldoPuntos($revendedora, $catalogo))->toBe(80);
});

it('reinicia la racha cuando no hay pedido confirmado en el cierre', function () {
    $servicio = app(PremiosRevendedoraService::class);
    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create(['nombre' => 'Catálogo prueba racha']);

    $cierreUno = CierreCampana::query()->create([
        'nombre' => 'Cierre 1',
        'codigo' => 'CAMP-TST-R1',
        'catalogo_id' => $catalogo->id,
        'numero_cierre' => 1,
    ]);
    $cierreDos = CierreCampana::query()->create([
        'nombre' => 'Cierre 2',
        'codigo' => 'CAMP-TST-R2',
        'catalogo_id' => $catalogo->id,
        'numero_cierre' => 2,
    ]);

    $servicio->procesarRacha($revendedora, $cierreUno, true);
    $rachaReiniciada = $servicio->procesarRacha($revendedora, $cierreDos, false);

    expect($rachaReiniciada->estado)->toBe('reiniciada')
        ->and($rachaReiniciada->racha_actual)->toBe(0);
});

it('rechaza canje inválido por saldo insuficiente', function () {
    $servicio = app(PremiosRevendedoraService::class);
    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create(['nombre' => 'Catálogo canje inválido']);
    $cierre = CierreCampana::query()->create([
        'nombre' => 'Cierre canje',
        'codigo' => 'CAMP-TST-CI',
        'catalogo_id' => $catalogo->id,
        'numero_cierre' => 1,
    ]);

    $premio = TiendaPremio::query()->create([
        'user_id' => $revendedora->id,
        'catalogo_id' => $catalogo->id,
        'cierre_id' => $cierre->id,
        'estado' => 'activo',
        'nombre' => 'Premio alto',
        'descripcion' => 'Canje debe fallar',
        'puntos_requeridos' => 1000,
        'stock' => 1,
        'origen' => 'test',
    ]);

    expect(fn () => $servicio->ejecutarCanje($revendedora, $premio, $cierre))
        ->toThrow(\InvalidArgumentException::class);
});

it('evita doble canje cuando el stock llega a cero', function () {
    $servicio = app(PremiosRevendedoraService::class);
    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create(['nombre' => 'Catálogo stock']);
    $cierre = CierreCampana::query()->create([
        'nombre' => 'Cierre stock',
        'codigo' => 'CAMP-TST-ST',
        'catalogo_id' => $catalogo->id,
        'numero_cierre' => 1,
    ]);

    $servicio->registrarMovimientoPuntos(
        revendedora: $revendedora,
        cierre: $cierre,
        puntos: 400,
        estado: 'confirmado',
        origen: 'test',
        idempotenciaClave: 'saldo-inicial',
    );

    $premio = TiendaPremio::query()->create([
        'user_id' => $revendedora->id,
        'catalogo_id' => $catalogo->id,
        'cierre_id' => $cierre->id,
        'estado' => 'activo',
        'nombre' => 'Premio único',
        'descripcion' => 'Stock unitario',
        'puntos_requeridos' => 200,
        'stock' => 1,
        'origen' => 'test',
    ]);

    $servicio->ejecutarCanje($revendedora, $premio, $cierre);

    expect(fn () => $servicio->ejecutarCanje($revendedora, $premio->fresh(), $cierre))
        ->toThrow(\InvalidArgumentException::class);
});
