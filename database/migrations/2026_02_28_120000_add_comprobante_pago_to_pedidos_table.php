<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->enum('estado_pago', ['sin_pago', 'pendiente_verificacion_lider', 'verificado', 'rechazado'])
                ->default('sin_pago')
                ->after('estado');
            $table->string('comprobante_pago_path')->nullable()->after('estado_pago');
            $table->timestamp('comprobante_pago_subido_en')->nullable()->after('comprobante_pago_path');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'estado_pago',
                'comprobante_pago_path',
                'comprobante_pago_subido_en',
            ]);
        });
    }
};
