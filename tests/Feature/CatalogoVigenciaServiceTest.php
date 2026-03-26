<?php

namespace Tests\Feature;

use App\Models\Catalogo;
use App\Models\CatalogoPagina;
use App\Services\PedidoCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogoVigenciaServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_obtener_paginas_catalogo_prioriza_catalogo_vigente_por_fecha(): void
    {
        $hoy = now()->startOfDay();

        $catalogoPasado = Catalogo::query()->create([
            'nombre' => 'Catálogo pasado',
            'anio' => (int) $hoy->copy()->subYear()->year,
            'numero' => 4,
            'fecha_inicio' => $hoy->copy()->subMonths(6)->toDateString(),
            'fecha_fin' => $hoy->copy()->subMonths(3)->subDay()->toDateString(),
        ]);

        $catalogoVigente = Catalogo::query()->create([
            'nombre' => 'Catálogo vigente',
            'anio' => (int) $hoy->year,
            'numero' => 2,
            'fecha_inicio' => $hoy->copy()->subMonth()->toDateString(),
            'fecha_fin' => $hoy->copy()->addMonths(2)->subDay()->toDateString(),
        ]);

        CatalogoPagina::query()->create([
            'catalogo_id' => $catalogoPasado->id,
            'numero' => 1,
            'imagen' => 'catalogo/paginas/pasado.jpg',
        ]);

        CatalogoPagina::query()->create([
            'catalogo_id' => $catalogoVigente->id,
            'numero' => 1,
            'imagen' => 'catalogo/paginas/vigente.jpg',
        ]);

        $service = app(PedidoCartService::class);
        $paginas = $service->obtenerPaginasCatalogo();

        $this->assertCount(1, $paginas);
        $this->assertSame(1, $paginas[0]['numero']);
        $this->assertStringContainsString('vigente.jpg', (string) $paginas[0]['imagen']);
    }

    public function test_obtener_paginas_catalogo_toma_catalogo_futuro_si_no_hay_vigente(): void
    {
        $hoy = now()->startOfDay();

        $catalogoFuturoCercano = Catalogo::query()->create([
            'nombre' => 'Catálogo futuro cercano',
            'anio' => (int) $hoy->copy()->addYear()->year,
            'numero' => 1,
            'fecha_inicio' => $hoy->copy()->addDays(10)->toDateString(),
            'fecha_fin' => $hoy->copy()->addMonths(3)->addDays(9)->toDateString(),
        ]);

        $catalogoFuturoLejano = Catalogo::query()->create([
            'nombre' => 'Catálogo futuro lejano',
            'anio' => (int) $hoy->copy()->addYear()->year,
            'numero' => 2,
            'fecha_inicio' => $hoy->copy()->addDays(60)->toDateString(),
            'fecha_fin' => $hoy->copy()->addMonths(5)->toDateString(),
        ]);

        CatalogoPagina::query()->create([
            'catalogo_id' => $catalogoFuturoCercano->id,
            'numero' => 1,
            'imagen' => 'catalogo/paginas/cercano.jpg',
        ]);

        CatalogoPagina::query()->create([
            'catalogo_id' => $catalogoFuturoLejano->id,
            'numero' => 1,
            'imagen' => 'catalogo/paginas/lejano.jpg',
        ]);

        $service = app(PedidoCartService::class);
        $paginas = $service->obtenerPaginasCatalogo();

        $this->assertCount(1, $paginas);
        $this->assertStringContainsString('cercano.jpg', (string) $paginas[0]['imagen']);
    }
}
