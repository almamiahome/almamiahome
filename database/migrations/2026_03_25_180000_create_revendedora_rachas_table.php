<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revendedora_rachas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('catalogo_id')->nullable()->constrained('catalogos')->nullOnDelete();
            $table->foreignId('cierre_id')->nullable()->constrained('cierres_campana')->nullOnDelete();
            $table->string('estado')->default('activa');
            $table->unsignedInteger('racha_actual')->default(0);
            $table->unsignedInteger('mejor_racha')->default(0);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_ultimo_movimiento')->nullable();
            $table->string('origen')->nullable();
            $table->string('motivo')->nullable();
            $table->integer('saldo_posterior')->default(0);
            $table->timestamp('fecha_entrega')->nullable();
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
            $table->unique(['user_id', 'catalogo_id', 'cierre_id'], 'rev_rachas_usuario_catalogo_cierre_unique');
            $table->index(['user_id', 'catalogo_id', 'cierre_id', 'estado'], 'rev_rachas_usuario_catalogo_cierre_estado_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revendedora_rachas');
    }
};
