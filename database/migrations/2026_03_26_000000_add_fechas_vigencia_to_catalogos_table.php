<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            $table->date('fecha_inicio')->nullable()->after('numero');
            $table->date('fecha_fin')->nullable()->after('fecha_inicio');
            $table->index(['fecha_inicio', 'fecha_fin'], 'catalogos_rango_fechas_idx');
        });
    }

    public function down(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            $table->dropIndex('catalogos_rango_fechas_idx');
            $table->dropColumn(['fecha_inicio', 'fecha_fin']);
        });
    }
};
