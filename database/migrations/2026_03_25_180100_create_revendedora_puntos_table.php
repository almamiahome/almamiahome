<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revendedora_puntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('catalogo_id')->nullable()->constrained('catalogos')->nullOnDelete();
            $table->foreignId('cierre_id')->nullable()->constrained('cierres_campana')->nullOnDelete();
            $table->string('estado')->default('confirmado');
            $table->integer('puntos')->default(0);
            $table->string('origen');
            $table->string('motivo')->nullable();
            $table->integer('saldo_posterior')->default(0);
            $table->timestamp('fecha_entrega')->nullable();
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
            $table->index(['user_id', 'catalogo_id', 'cierre_id'], 'rev_puntos_usuario_catalogo_cierre_idx');
            $table->index(['user_id', 'catalogo_id', 'cierre_id', 'estado'], 'rev_puntos_usuario_catalogo_cierre_estado_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revendedora_puntos');
    }
};
