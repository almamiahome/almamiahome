<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            // Campos obligatorios
            $table->string('nombre');
            $table->decimal('precio', 12, 2);
            $table->unsignedInteger('puntos_por_unidad');

            // Campos opcionales
            $table->string('sku')->unique()->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('stock_actual')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('altura')->nullable();
            $table->string('anchura')->nullable();
            $table->string('profundidad')->nullable();
            $table->string('bulto')->nullable();
            $table->string('imagen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

