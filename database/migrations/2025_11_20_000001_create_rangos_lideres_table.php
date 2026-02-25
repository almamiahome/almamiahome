<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rangos_lideres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedSmallInteger('revendedoras_minimas')->default(0);
            $table->unsignedSmallInteger('revendedoras_maximas')->default(0);
            $table->unsignedInteger('unidades_minimas')->default(0);
            $table->decimal('premio_actividad', 12, 2)->default(0);
            $table->decimal('premio_unidades', 12, 2)->default(0);
            $table->decimal('premio_cobranzas', 12, 2)->default(0);
            $table->decimal('reparto_referencia', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rangos_lideres');
    }
};
