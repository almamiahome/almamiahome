<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('liquidaciones_cierre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cierre_campana_id')->constrained('cierres_campana')->cascadeOnDelete();
            $table->foreignId('lider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('coordinadora_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('saldo_a_cobrar', 14, 2)->default(0);
            $table->decimal('saldo_a_pagar', 14, 2)->default(0);
            $table->decimal('deuda_arrastrada', 14, 2)->default(0);
            $table->decimal('descuento_aplicado', 14, 2)->default(0);
            $table->decimal('balance_neto', 14, 2)->default(0);
            $table->json('detalle_json')->nullable();
            $table->string('estado')->default('borrador');
            $table->timestamp('auditado_en')->nullable();
            $table->foreignId('auditado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('motivo_auditoria')->nullable();
            $table->timestamps();

            $table->index(['cierre_campana_id', 'estado']);
            $table->index(['lider_id', 'estado']);
            $table->index(['coordinadora_id', 'estado']);
            $table->unique(['cierre_campana_id', 'lider_id'], 'uniq_liquidacion_cierre_lider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones_cierre');
    }
};
