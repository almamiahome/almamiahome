<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('puntaje_reglas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('min_unidades')->nullable();
            $table->unsignedInteger('max_unidades')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('bonificacion', 10, 2)->nullable();
            $table->decimal('porcentaje', 5, 2)->nullable();
            $table->text('beneficios')->nullable();
            $table->unsignedInteger('puntaje_minimo')->nullable();
            $table->text('puntaje_minimo_descripcion')->nullable();
            $table->unsignedInteger('puntos_mensuales')->nullable();
            $table->unsignedInteger('puntos_por_campania')->nullable();
            $table->json('datos')->nullable();

            $table->timestamps();
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->foreignId('puntaje_regla_id')
                ->nullable()
                ->constrained('puntaje_reglas')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropConstrainedForeignId('puntaje_regla_id');
        });

        Schema::dropIfExists('puntaje_reglas');
    }
};
