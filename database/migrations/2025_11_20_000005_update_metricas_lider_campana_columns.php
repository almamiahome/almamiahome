<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metricas_lider_campana', function (Blueprint $table) {
            $table->boolean('actividad_ok')->default(false)->after('rango_lider_id');
            $table->boolean('altas_ok')->default(false)->after('actividad_ok');
            $table->boolean('unidades_ok')->default(false)->after('altas_ok');
            $table->boolean('cobranzas_ok')->default(false)->after('unidades_ok');
            $table->boolean('crecimiento_ok')->default(false)->after('cobranzas_ok');
            $table->json('altas_pagadas_en_cierre')->nullable()->after('crecimiento_ok');
            $table->unsignedInteger('cantidad_1c')->default(0)->after('altas_pagadas_en_cierre');
            $table->unsignedInteger('cantidad_2c')->default(0)->after('cantidad_1c');
            $table->unsignedInteger('cantidad_3c')->default(0)->after('cantidad_2c');
            $table->decimal('monto_reparto_total', 12, 2)->default(0)->after('cantidad_3c');
            $table->decimal('premio_actividad', 12, 2)->default(0)->after('monto_reparto_total');
            $table->decimal('premio_unidades', 12, 2)->default(0)->after('premio_actividad');
            $table->decimal('premio_cobranzas', 12, 2)->default(0)->after('premio_unidades');
            $table->decimal('premio_altas', 12, 2)->default(0)->after('premio_cobranzas');
            $table->decimal('premio_crecimiento', 12, 2)->default(0)->after('premio_altas');
            $table->timestamp('fecha_pago_equipo')->nullable()->after('premio_crecimiento');
        });

        $connection = DB::connection();
        $columnasOriginales = [
            'actividad',
            'altas',
            'unidades',
            'cobranzas',
            'crecimiento',
            'compras_1c',
            'compras_2c',
            'compras_3c',
            'premio_base',
            'premio_variable',
        ];

        if ($connection->getDriverName() === 'sqlite') {
            foreach ($columnasOriginales as $columna) {
                $connection->statement("ALTER TABLE metricas_lider_campana DROP COLUMN {$columna}");
            }
        } else {
            $connection->statement('ALTER TABLE metricas_lider_campana '
                . 'DROP COLUMN actividad, '
                . 'DROP COLUMN altas, '
                . 'DROP COLUMN unidades, '
                . 'DROP COLUMN cobranzas, '
                . 'DROP COLUMN crecimiento, '
                . 'DROP COLUMN compras_1c, '
                . 'DROP COLUMN compras_2c, '
                . 'DROP COLUMN compras_3c, '
                . 'DROP COLUMN premio_base, '
                . 'DROP COLUMN premio_variable');
        }
    }

    public function down(): void
    {
        $connection = DB::connection();
        $nuevasColumnas = [
            'actividad_ok',
            'altas_ok',
            'unidades_ok',
            'cobranzas_ok',
            'crecimiento_ok',
            'altas_pagadas_en_cierre',
            'cantidad_1c',
            'cantidad_2c',
            'cantidad_3c',
            'monto_reparto_total',
            'premio_actividad',
            'premio_unidades',
            'premio_cobranzas',
            'premio_altas',
            'premio_crecimiento',
            'fecha_pago_equipo',
        ];

        if ($connection->getDriverName() === 'sqlite') {
            foreach ($nuevasColumnas as $columna) {
                $connection->statement("ALTER TABLE metricas_lider_campana DROP COLUMN {$columna}");
            }
        } else {
            $connection->statement('ALTER TABLE metricas_lider_campana '
                . 'DROP COLUMN actividad_ok, '
                . 'DROP COLUMN altas_ok, '
                . 'DROP COLUMN unidades_ok, '
                . 'DROP COLUMN cobranzas_ok, '
                . 'DROP COLUMN crecimiento_ok, '
                . 'DROP COLUMN altas_pagadas_en_cierre, '
                . 'DROP COLUMN cantidad_1c, '
                . 'DROP COLUMN cantidad_2c, '
                . 'DROP COLUMN cantidad_3c, '
                . 'DROP COLUMN monto_reparto_total, '
                . 'DROP COLUMN premio_actividad, '
                . 'DROP COLUMN premio_unidades, '
                . 'DROP COLUMN premio_cobranzas, '
                . 'DROP COLUMN premio_altas, '
                . 'DROP COLUMN premio_crecimiento, '
                . 'DROP COLUMN fecha_pago_equipo');
        }

        Schema::table('metricas_lider_campana', function (Blueprint $table) {
            $table->unsignedSmallInteger('actividad')->default(0);
            $table->unsignedSmallInteger('altas')->default(0);
            $table->unsignedInteger('unidades')->default(0);
            $table->unsignedSmallInteger('cobranzas')->default(0);
            $table->unsignedSmallInteger('crecimiento')->default(0);
            $table->unsignedInteger('compras_1c')->default(0);
            $table->unsignedInteger('compras_2c')->default(0);
            $table->unsignedInteger('compras_3c')->default(0);
            $table->decimal('premio_base', 12, 2)->default(0);
            $table->decimal('premio_variable', 12, 2)->default(0);
        });
    }
};
