<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premio_lider_cierre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cierre_campana_id')->constrained('cierres_campana')->cascadeOnDelete();
            $table->foreignId('rango_lider_id')->nullable()->constrained('rangos_lideres')->nullOnDelete();
            $table->foreignId('metrica_lider_campana_id')->nullable()->constrained('metricas_lider_campana')->nullOnDelete();

            $table->decimal('premio_actividad', 12, 2)->default(0);
            $table->decimal('premio_retencion', 12, 2)->default(0);
            $table->decimal('premio_altas', 12, 2)->default(0);
            $table->decimal('premio_cobranza', 12, 2)->default(0);
            $table->decimal('premio_crecimiento', 12, 2)->default(0);
            $table->decimal('premio_reparto', 12, 2)->default(0);
            $table->decimal('premio_plus_crecimiento', 12, 2)->default(0);
            $table->decimal('premio_unidades', 12, 2)->default(0);
            $table->decimal('premio_total', 12, 2)->default(0);
            $table->json('detalle')->nullable();
            $table->timestamps();

            $table->unique(['lider_id', 'cierre_campana_id'], 'premio_lider_cierre_unico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premio_lider_cierre');
    }
};
