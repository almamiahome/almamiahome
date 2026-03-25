<?php

use App\Models\CanjePremio;
use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\RevendedoraPunto;
use App\Models\RevendedoraRacha;
use App\Models\TiendaPremio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function cargarFixtureJson(string $archivo): array
{
    $ruta = database_path("seeders/fixtures/{$archivo}");

    return json_decode(file_get_contents($ruta), true, 512, JSON_THROW_ON_ERROR);
}

it('mantiene racha de 3 cierres consecutivos con recálculo idempotente por cierre', function () {
    $fixture = cargarFixtureJson('catalogo_cierre_base_v2.json');

    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create($fixture['catalogo']);

    $cierres = collect($fixture['cierres'])->map(function (array $datos) use ($catalogo) {
        return CierreCampana::query()->create(array_merge($datos, ['catalogo_id' => $catalogo->id]));
    });

    foreach ($cierres as $indice => $cierre) {
        RevendedoraRacha::query()->updateOrCreate(
            [
                'user_id' => $revendedora->id,
                'catalogo_id' => $catalogo->id,
                'cierre_id' => $cierre->id,
            ],
            [
                'estado' => $indice === 2 ? 'premiada' : 'activa',
                'racha_actual' => $indice + 1,
                'mejor_racha' => $indice + 1,
                'fecha_inicio' => $cierres->first()->fecha_inicio,
                'fecha_ultimo_movimiento' => $cierre->fecha_cierre,
                'origen' => 'fixture',
                'motivo' => 'Simulación de cierre consecutivo',
                'saldo_posterior' => $indice + 1,
            ],
        );
    }

    RevendedoraRacha::query()->updateOrCreate(
        [
            'user_id' => $revendedora->id,
            'catalogo_id' => $catalogo->id,
            'cierre_id' => $cierres->last()->id,
        ],
        [
            'estado' => 'premiada',
            'racha_actual' => 3,
            'mejor_racha' => 3,
            'fecha_inicio' => $cierres->first()->fecha_inicio,
            'fecha_ultimo_movimiento' => $cierres->last()->fecha_cierre,
            'origen' => 'fixture',
            'motivo' => 'Recálculo idempotente',
            'saldo_posterior' => 3,
        ],
    );

    $ultimaRacha = RevendedoraRacha::query()
        ->where('user_id', $revendedora->id)
        ->latest('id')
        ->firstOrFail();

    expect(RevendedoraRacha::query()->count())->toBe(3)
        ->and($ultimaRacha->estado)->toBe('premiada')
        ->and($ultimaRacha->racha_actual)->toBe(3)
        ->and($ultimaRacha->mejor_racha)->toBe(3)
        ->and($ultimaRacha->motivo)->toBe('Recálculo idempotente');
});

it('controla acumulación, canje y vencimiento de puntos con saldo trazable', function () {
    $fixture = cargarFixtureJson('revendedora_premios_cierre_v2.json');

    $revendedora = User::factory()->create();
    $catalogo = Catalogo::query()->create($fixture['catalogo']);
    $cierre = CierreCampana::query()->create(array_merge($fixture['cierre'], ['catalogo_id' => $catalogo->id]));

    $saldo = 0;

    foreach ($fixture['movimientos_puntos'] as $movimiento) {
        $saldo += $movimiento['puntos'];

        RevendedoraPunto::query()->create([
            'user_id' => $revendedora->id,
            'catalogo_id' => $catalogo->id,
            'cierre_id' => $cierre->id,
            'estado' => $movimiento['estado'],
            'puntos' => $movimiento['puntos'],
            'origen' => $movimiento['origen'],
            'motivo' => $movimiento['motivo'],
            'saldo_posterior' => $saldo,
            'datos' => $movimiento['datos'] ?? null,
        ]);
    }

    $premio = TiendaPremio::query()->create([
        'user_id' => $revendedora->id,
        'catalogo_id' => $catalogo->id,
        'cierre_id' => $cierre->id,
        'estado' => 'activo',
        'nombre' => 'Kit de inicio',
        'descripcion' => 'Premio para validar flujo de canje',
        'puntos_requeridos' => 120,
        'stock' => 10,
        'origen' => 'fixture',
    ]);

    CanjePremio::query()->create([
        'user_id' => $revendedora->id,
        'tienda_premio_id' => $premio->id,
        'catalogo_id' => $catalogo->id,
        'cierre_id' => $cierre->id,
        'estado' => 'aprobado',
        'puntos_canjeados' => 120,
        'saldo_posterior' => 130,
        'origen' => 'fixture',
        'motivo' => 'Canje de validación',
    ]);

    $saldoFinal = (int) RevendedoraPunto::query()->where('user_id', $revendedora->id)->sum('puntos');
    $vencidos = (int) RevendedoraPunto::query()->where('user_id', $revendedora->id)->where('estado', 'vencido')->sum('puntos');

    expect($saldoFinal)->toBe(0)
        ->and($vencidos)->toBe(-130)
        ->and(CanjePremio::query()->where('user_id', $revendedora->id)->count())->toBe(1)
        ->and(RevendedoraPunto::query()->where('user_id', $revendedora->id)->latest('id')->value('saldo_posterior'))->toBe(0);
});
