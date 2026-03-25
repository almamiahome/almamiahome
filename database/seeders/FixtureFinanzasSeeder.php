<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixtureFinanzasSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = database_path('seeders/fixtures/finanzas_cierre_fixture.json');
        $datos = json_decode((string) file_get_contents($ruta), true, 512, JSON_THROW_ON_ERROR);

        DB::table('zonas')->upsert($datos['zonas'], ['id'], ['nombre', 'codigo', 'activa']);
        DB::table('departamentos')->upsert($datos['departamentos'], ['id'], ['zona_id', 'nombre', 'codigo', 'activo']);

        foreach ($datos['liquidaciones_cierre'] as $fila) {
            $fila['detalle_json'] = ['fuente' => 'fixture_financiero'];
            DB::table('liquidaciones_cierre')->updateOrInsert(['id' => $fila['id']], $fila);
        }

        foreach ($datos['descuentos_futuros'] as $fila) {
            $fila['detalle_json'] = ['fuente' => 'fixture_financiero'];
            DB::table('descuentos_futuros')->updateOrInsert(['id' => $fila['id']], $fila);
        }
    }
}
