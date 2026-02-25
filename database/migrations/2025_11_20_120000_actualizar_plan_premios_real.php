<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('rangos_lideres', 'bono_base')) {
            Schema::table('rangos_lideres', function (Blueprint $table) {
                $table->dropColumn([
                    'bono_base',
                    'actividad_minima',
                    'altas_minimas',
                    'cobranzas_minimas',
                    'crecimiento_minimo',
                ]);
            });
        }

        if (! Schema::hasColumn('rangos_lideres', 'revendedoras_minimas')) {
            Schema::table('rangos_lideres', function (Blueprint $table) {
                $table->unsignedSmallInteger('revendedoras_minimas')->default(0)->after('posicion');
                $table->unsignedSmallInteger('revendedoras_maximas')->default(0)->after('revendedoras_minimas');
                $table->decimal('premio_actividad', 12, 2)->default(0)->after('unidades_minimas');
                $table->decimal('premio_unidades', 12, 2)->default(0)->after('premio_actividad');
                $table->decimal('premio_cobranzas', 12, 2)->default(0)->after('premio_unidades');
                $table->decimal('reparto_referencia', 12, 2)->default(0)->after('premio_cobranzas');
            });
        }

        if (Schema::hasColumn('premio_reglas', 'porcentaje')) {
            Schema::table('premio_reglas', function (Blueprint $table) {
                $table->dropColumn('porcentaje');
            });
        }

        if (! Schema::hasColumn('premio_reglas', 'cuotas')) {
            Schema::table('premio_reglas', function (Blueprint $table) {
                $table->unsignedTinyInteger('cuotas')->default(1)->after('monto');
            });
        }

        if (Schema::hasColumn('repartos_compras', 'rango_lider_id')) {
            Schema::table('repartos_compras', function (Blueprint $table) {
                $table->dropForeign(['rango_lider_id']);
                $table->dropUnique(['rango_lider_id', 'compra_orden']);
                $table->dropColumn(['rango_lider_id', 'compra_orden', 'bono_fijo', 'porcentaje_lider', 'porcentaje_revendedora']);
            });
        }

        if (! Schema::hasColumn('repartos_compras', 'tipo_compra')) {
            Schema::table('repartos_compras', function (Blueprint $table) {
                $table->string('tipo_compra', 3)->after('id');
                $table->decimal('monto_por_revendedora', 12, 2)->default(0)->after('tipo_compra');
                $table->decimal('porcentaje_lider', 5, 2)->nullable()->default(null)->after('monto_por_revendedora');
                $table->decimal('porcentaje_revendedora', 5, 2)->nullable()->default(null)->after('porcentaje_lider');
                $table->unique('tipo_compra');
            });
        }
    }

    public function down(): void
    {
        Schema::table('repartos_compras', function (Blueprint $table) {
            $table->dropUnique(['tipo_compra']);
            $table->dropColumn(['tipo_compra', 'monto_por_revendedora', 'porcentaje_lider', 'porcentaje_revendedora']);
            $table->foreignId('rango_lider_id')->after('id')->constrained('rangos_lideres')->cascadeOnDelete();
            $table->unsignedTinyInteger('compra_orden')->after('rango_lider_id');
            $table->decimal('porcentaje_lider', 5, 2)->default(0)->after('compra_orden');
            $table->decimal('porcentaje_revendedora', 5, 2)->default(0)->after('porcentaje_lider');
            $table->decimal('bono_fijo', 12, 2)->default(0)->after('porcentaje_revendedora');
            $table->unique(['rango_lider_id', 'compra_orden']);
        });

        Schema::table('premio_reglas', function (Blueprint $table) {
            $table->dropColumn('cuotas');
            $table->decimal('porcentaje', 5, 2)->nullable()->after('monto');
        });

        Schema::table('rangos_lideres', function (Blueprint $table) {
            $table->dropColumn([
                'revendedoras_minimas',
                'revendedoras_maximas',
                'premio_actividad',
                'premio_unidades',
                'premio_cobranzas',
                'reparto_referencia',
            ]);

            $table->decimal('bono_base', 12, 2)->default(0)->after('posicion');
            $table->unsignedSmallInteger('actividad_minima')->default(0)->after('bono_base');
            $table->unsignedSmallInteger('altas_minimas')->default(0)->after('actividad_minima');
            $table->unsignedInteger('unidades_minimas')->default(0)->after('altas_minimas');
            $table->unsignedSmallInteger('cobranzas_minimas')->default(0)->after('unidades_minimas');
            $table->unsignedSmallInteger('crecimiento_minimo')->default(0)->after('cobranzas_minimas');
        });
    }
};
