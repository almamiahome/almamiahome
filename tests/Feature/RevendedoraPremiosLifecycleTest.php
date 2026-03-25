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
