<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repartos_compras', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_compra', 3);
            $table->decimal('monto_por_revendedora', 12, 2)->default(0);
            $table->decimal('porcentaje_lider', 5, 2)->nullable();
            $table->decimal('porcentaje_revendedora', 5, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->unique('tipo_compra');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repartos_compras');
    }
};
