<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Etapa2PremiosSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->orderBy('id')->value('id');
        $catalogoId = DB::table('catalogos')->orderBy('id')->value('id');
        $cierreId = DB::table('cierres_campana')->orderBy('id')->value('id');

        if (! $userId || ! $catalogoId || ! $cierreId) {
            return;
        }

        DB::table('revendedora_rachas')->updateOrInsert(
            ['id' => 1],
            [
                'user_id' => $userId,
                'catalogo_id' => $catalogoId,
                'cierre_id' => $cierreId,
                'estado' => 'activa',
                'racha_actual' => 2,
                'mejor_racha' => 4,
                'fecha_inicio' => now()->toDateString(),
                'fecha_ultimo_movimiento' => now()->toDateString(),
                'origen' => 'seeder_etapa_2',
                'motivo' => 'Inicialización mínima de racha',
                'saldo_posterior' => 240,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('revendedora_puntos')->updateOrInsert(
            ['id' => 1],
            [
                'user_id' => $userId,
                'catalogo_id' => $catalogoId,
                'cierre_id' => $cierreId,
                'estado' => 'confirmado',
                'puntos' => 120,
                'origen' => 'pedido',
                'motivo' => 'Carga inicial para pruebas funcionales',
                'saldo_posterior' => 240,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('tienda_premios')->updateOrInsert(
            ['id' => 1],
            [
                'user_id' => $userId,
                'catalogo_id' => $catalogoId,
                'cierre_id' => $cierreId,
                'estado' => 'publicado',
                'nombre' => 'Set fragancias miniatura',
                'descripcion' => 'Premio base para validación de canjes en Etapa 2.',
                'puntos_requeridos' => 150,
                'stock' => 10,
                'origen' => 'seeder_etapa_2',
                'motivo' => 'Catálogo de prueba funcional',
                'saldo_posterior' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('canjes_premios')->updateOrInsert(
            ['id' => 1],
            [
                'user_id' => $userId,
                'tienda_premio_id' => 1,
                'catalogo_id' => $catalogoId,
                'cierre_id' => $cierreId,
                'estado' => 'pendiente',
                'puntos_canjeados' => 150,
                'origen' => 'tienda_premios',
                'motivo' => 'Canje inicial de validación',
                'saldo_posterior' => 90,
                'fecha_entrega' => null,
                'fecha_canje' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
