<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Historial de cambios de estado por campaña para trazabilidad operativa.
     */
    public function up(): void
    {
        Schema::create('cierre_campana_historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cierre_campana_id')
                ->constrained('cierres_campana')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('motivo')->nullable();
            $table->json('datos')->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
            $table->timestamps();

            $table->index(['cierre_campana_id', 'fecha_cambio'], 'historial_estados_campana_fecha_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cierre_campana_historial_estados');
    }
};
