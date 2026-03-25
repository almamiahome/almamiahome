<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canjes_premios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tienda_premio_id')->constrained('tienda_premios')->cascadeOnDelete();
            $table->foreignId('catalogo_id')->nullable()->constrained('catalogos')->nullOnDelete();
            $table->foreignId('cierre_id')->nullable()->constrained('cierres_campana')->nullOnDelete();
            $table->string('estado')->default('pendiente');
            $table->unsignedInteger('puntos_canjeados');
            $table->string('origen')->nullable();
            $table->string('motivo')->nullable();
            $table->integer('saldo_posterior')->default(0);
            $table->timestamp('fecha_entrega')->nullable();
            $table->timestamp('fecha_canje')->useCurrent();
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
            $table->index(['user_id', 'catalogo_id', 'cierre_id', 'estado'], 'canjes_premios_usuario_catalogo_cierre_estado_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canjes_premios');
    }
};
