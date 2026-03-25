<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('descuentos_futuros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origen_liquidacion_id')->constrained('liquidaciones_cierre')->cascadeOnDelete();
            $table->foreignId('cierre_destino_id')->constrained('cierres_campana')->cascadeOnDelete();
            $table->foreignId('lider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('coordinadora_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('monto', 14, 2);
            $table->string('motivo');
            $table->json('detalle_json')->nullable();
            $table->string('estado')->default('pendiente');
            $table->timestamp('auditado_en')->nullable();
            $table->foreignId('auditado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('motivo_auditoria')->nullable();
            $table->timestamps();

            $table->index(['cierre_destino_id', 'estado']);
            $table->unique(['origen_liquidacion_id', 'cierre_destino_id', 'motivo'], 'uniq_descuento_futuro_idempotente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descuentos_futuros');
    }
};
