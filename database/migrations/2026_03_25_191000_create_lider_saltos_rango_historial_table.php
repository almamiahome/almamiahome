<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lider_saltos_rango_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cierre_campana_id')->constrained('cierres_campana')->cascadeOnDelete();
            $table->foreignId('rango_anterior_id')->nullable()->constrained('rangos_lideres')->nullOnDelete();
            $table->foreignId('rango_nuevo_id')->nullable()->constrained('rangos_lideres')->nullOnDelete();
            $table->string('estado')->default('registrado');
            $table->string('motivo')->nullable();
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->unique(['lider_id', 'cierre_campana_id'], 'lider_salto_rango_por_cierre_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lider_saltos_rango_historial');
    }
};
