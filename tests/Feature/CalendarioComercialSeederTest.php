<?php

namespace Tests\Feature;

use Database\Seeders\AlmamiaSeederPremios;
use Database\Seeders\CalendarioComercialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CalendarioComercialSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_genera_catalogos_y_cierres_deterministicos(): void
    {
        $anio = (int) now()->year;

        Artisan::call('db:seed', ['--class' => CalendarioComercialSeeder::class, '--no-interaction' => true]);

        $this->assertSame(4, DB::table('catalogos')->where('anio', $anio)->count());
        $this->assertSame(12, DB::table('cierres_campana')->where('codigo', 'like', sprintf('CAMP-%d-%%', $anio))->count());

        $primerCierre = DB::table('cierres_campana')->where('codigo', sprintf('CAMP-%d-01', $anio))->first();

        $this->assertNotNull($primerCierre);
        $this->assertSame('planificado', $primerCierre->estado);
        $this->assertSame("{$anio}-01-01", $primerCierre->fecha_inicio);
        $this->assertSame("{$anio}-01-21", $primerCierre->fecha_cierre);
        $this->assertSame("{$anio}-01-31", $primerCierre->fecha_liquidacion);
    }

    public function test_seeder_premios_no_trunca_cierres_productivos(): void
    {
        $anio = (int) now()->year;

        Artisan::call('db:seed', ['--class' => CalendarioComercialSeeder::class, '--no-interaction' => true]);
        Artisan::call('db:seed', ['--class' => AlmamiaSeederPremios::class, '--no-interaction' => true]);

        $this->assertSame(12, DB::table('cierres_campana')->where('codigo', 'like', sprintf('CAMP-%d-%%', $anio))->count());
        $this->assertDatabaseHas('cierres_campana', ['codigo' => 'CAMP-BASE']);
    }
}
