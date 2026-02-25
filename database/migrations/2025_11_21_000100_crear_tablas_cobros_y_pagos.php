<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendedora_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('mes_campana')->nullable();
            $table->string('mes_pago_programado')->nullable();
            $table->decimal('monto', 12, 2)->default(0);
            $table->enum('estado', ['pendiente', 'programado', 'pagado', 'cancelado'])->default('pendiente');
            $table->date('fecha_pago')->nullable();
            $table->text('detalle')->nullable();
            $table->timestamps();
        });

        Schema::create('cobros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->nullOnDelete();
            $table->foreignId('lider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('coordinadora_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('mes_campana')->nullable();
            $table->string('mes_pago_programado')->nullable();
            $table->decimal('monto', 12, 2)->default(0);
            $table->enum('estado', ['pendiente', 'programado', 'pagado', 'cancelado'])->default('pendiente');
            $table->string('concepto')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobros');
        Schema::dropIfExists('pagos');
    }
};
