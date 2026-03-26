<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billetera_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('catalogo_id')->nullable()->constrained('catalogos')->nullOnDelete();
            $table->foreignId('cierre_id')->nullable()->constrained('cierres_campana')->nullOnDelete();
            $table->foreignId('liquidacion_cierre_id')->nullable()->constrained('liquidaciones_cierre')->nullOnDelete();
            $table->enum('tipo_saldo', ['dinero', 'puntos'])->default('puntos');
            $table->enum('naturaleza', ['credito', 'debito'])->default('credito');
            $table->decimal('monto', 14, 2)->default(0);
            $table->integer('puntos')->nullable();
            $table->string('origen');
            $table->string('estado')->default('confirmado');
            $table->string('detalle')->nullable();
            $table->timestamp('fecha_movimiento')->nullable();
            $table->string('idempotencia_clave')->nullable();
            $table->nullableMorphs('referencia');
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'tipo_saldo', 'created_at'], 'bmov_user_tipo_fecha_idx');
            $table->index(['catalogo_id', 'cierre_id'], 'bmov_catalogo_cierre_idx');
            $table->index(['liquidacion_cierre_id', 'tipo_saldo'], 'bmov_liquidacion_tipo_idx');
            $table->unique(['user_id', 'idempotencia_clave'], 'bmov_user_idempotencia_uniq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billetera_movimientos');
    }
};
