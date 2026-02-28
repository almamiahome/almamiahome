<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premio_reglas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rango_lider_id')
                ->constrained('rangos_lideres')
                ->cascadeOnDelete();
            $table->foreignId('cierre_campana_id')
                ->nullable()
                ->constrained('cierres_campana')
                ->nullOnDelete();
            $table->string('tipo');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('umbral_minimo')->default(0);
            $table->unsignedInteger('umbral_maximo')->nullable();
            $table->decimal('monto', 12, 2)->default(0);
            $table->unsignedTinyInteger('cuotas')->default(1);
            $table->unsignedTinyInteger('compra_orden')->nullable();
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->index(['tipo', 'rango_lider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premio_reglas');
    }
};
