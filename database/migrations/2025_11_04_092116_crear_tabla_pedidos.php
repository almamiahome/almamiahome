<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_pedido')->unique();
            $table->foreignId('vendedora_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('lider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha')->nullable();
            $table->string('mes')->nullable();
            $table->string('catalogo_nro')->nullable();
            $table->decimal('total_precio_catalogo', 12, 2)->default(0);
            $table->decimal('total_gastos', 12, 2)->default(0);
            $table->decimal('total_ganancias', 12, 2)->default(0);
            $table->decimal('total_a_pagar', 12, 2)->default(0);
            $table->unsignedInteger('total_puntos')->default(0);
            $table->unsignedInteger('cantidad_unidades')->default(0);
            $table->enum('estado', [
                'Nuevo', 'En espera', 'Procesando', 'En viaje', 'Entregado', 'Completado', 'Cancelado'
            ])->default('Nuevo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('pedido_articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->string('producto')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_catalogo', 12, 2)->default(0);
            $table->decimal('porcentaje_descuento', 5, 2)->default(0);
            $table->decimal('ganancia', 12, 2)->default(0);
            $table->decimal('precio_unitario', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->unsignedInteger('puntos')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_articulos');
        Schema::dropIfExists('pedidos');
    }
};
