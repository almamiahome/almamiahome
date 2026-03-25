<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajustes de rendimiento para consultas de versión 2 del plan de premios.
     */
    public function up(): void
    {
        if (Schema::hasTable('premio_reglas')) {
            Schema::table('premio_reglas', function (Blueprint $table) {
                $table->index(['campana_id', 'tipo'], 'premio_reglas_campana_tipo_idx');
                $table->index(['rango_lider_id', 'campana_id', 'umbral_minimo'], 'premio_reglas_rango_campana_umbral_idx');
            });
        }

        if (Schema::hasTable('metricas_lider_campana')) {
            Schema::table('metricas_lider_campana', function (Blueprint $table) {
                $table->index(['cierre_campana_id', 'rango_lider_id', 'lider_id'], 'metricas_cierre_rango_lider_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('premio_reglas')) {
            Schema::table('premio_reglas', function (Blueprint $table) {
                $table->dropIndex('premio_reglas_campana_tipo_idx');
                $table->dropIndex('premio_reglas_rango_campana_umbral_idx');
            });
        }

        if (Schema::hasTable('metricas_lider_campana')) {
            Schema::table('metricas_lider_campana', function (Blueprint $table) {
                $table->dropIndex('metricas_cierre_rango_lider_idx');
            });
        }
    }
};
