<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('catalogo_id')
                ->nullable()
                ->after('catalogo_nro')
                ->constrained('catalogos')
                ->nullOnDelete();

            $table->foreignId('cierre_id')
                ->nullable()
                ->after('catalogo_id')
                ->constrained('cierres_campana')
                ->nullOnDelete();

            $table->unsignedInteger('unidades_facturables')
                ->default(0)
                ->after('cantidad_unidades');

            $table->unsignedInteger('unidades_auxiliares')
                ->default(0)
                ->after('unidades_facturables');

            $table->index(['catalogo_id', 'lider_id', 'fecha'], 'pedidos_catalogo_lider_fecha_idx');
            $table->index(['cierre_id', 'lider_id', 'fecha'], 'pedidos_cierre_lider_fecha_idx');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropIndex('pedidos_catalogo_lider_fecha_idx');
            $table->dropIndex('pedidos_cierre_lider_fecha_idx');
            $table->dropConstrainedForeignId('cierre_id');
            $table->dropConstrainedForeignId('catalogo_id');
            $table->dropColumn(['unidades_facturables', 'unidades_auxiliares']);
        });
    }
};
