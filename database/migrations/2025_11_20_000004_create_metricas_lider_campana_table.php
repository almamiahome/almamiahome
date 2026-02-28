<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metricas_lider_campana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lider_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('revendedora_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('cierre_campana_id')
                ->constrained('cierres_campana')
                ->cascadeOnDelete();
            $table->foreignId('rango_lider_id')
                ->constrained('rangos_lideres')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('actividad')->default(0);
            $table->unsignedSmallInteger('altas')->default(0);
            $table->unsignedInteger('unidades')->default(0);
            $table->unsignedSmallInteger('cobranzas')->default(0);
            $table->unsignedSmallInteger('crecimiento')->default(0);
            $table->unsignedInteger('compras_1c')->default(0);
            $table->unsignedInteger('compras_2c')->default(0);
            $table->unsignedInteger('compras_3c')->default(0);
            $table->decimal('premio_base', 12, 2)->default(0);
            $table->decimal('premio_variable', 12, 2)->default(0);
            $table->decimal('premio_total', 12, 2)->default(0);
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->unique([
                'lider_id',
                'cierre_campana_id',
                'rango_lider_id',
            ], 'lider_campana_rango_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metricas_lider_campana');
    }
};
