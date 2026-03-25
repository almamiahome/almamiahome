<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metricas_lider_campana', function (Blueprint $table) {
            $table->boolean('retencion_ok')->default(false)->after('crecimiento_ok');
            $table->boolean('plus_crecimiento_ok')->default(false)->after('retencion_ok');
            $table->decimal('premio_retencion', 12, 2)->default(0)->after('premio_crecimiento');
            $table->decimal('premio_plus_crecimiento', 12, 2)->default(0)->after('premio_retencion');
            $table->unsignedInteger('objetivo_proximo_cierre')->nullable()->after('fecha_pago_equipo');
            $table->unsignedInteger('actividad_cierre_anterior')->nullable()->after('objetivo_proximo_cierre');
        });
    }

    public function down(): void
    {
        Schema::table('metricas_lider_campana', function (Blueprint $table) {
            $table->dropColumn([
                'retencion_ok',
                'plus_crecimiento_ok',
                'premio_retencion',
                'premio_plus_crecimiento',
                'objetivo_proximo_cierre',
                'actividad_cierre_anterior',
            ]);
        });
    }
};
