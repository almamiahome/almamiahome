<?php

namespace Tests\Feature;

use Database\Seeders\AlmamiaSeeder;
use Database\Seeders\ProductosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeedersCompatibilidadSqliteTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_principales_corren_en_sqlite(): void
    {
        Artisan::call('db:seed', ['--class' => AlmamiaSeeder::class, '--no-interaction' => true]);
        Artisan::call('db:seed', ['--class' => ProductosSeeder::class, '--no-interaction' => true]);

        $this->assertGreaterThan(0, DB::table('categorias')->count());
        $this->assertGreaterThan(0, DB::table('productos')->count());
    }
}
