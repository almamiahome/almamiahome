<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tienda_premios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('catalogo_id')->nullable()->constrained('catalogos')->nullOnDelete();
            $table->foreignId('cierre_id')->nullable()->constrained('cierres_campana')->nullOnDelete();
            $table->string('estado')->default('publicado');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('puntos_requeridos');
            $table->unsignedInteger('stock')->default(0);
            $table->string('origen')->nullable();
            $table->string('motivo')->nullable();
            $table->integer('saldo_posterior')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
            $table->index(['user_id', 'catalogo_id', 'cierre_id', 'estado'], 'tienda_premios_usuario_catalogo_cierre_estado_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tienda_premios');
    }
};
