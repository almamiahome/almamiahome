<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedido_articulos', function (Blueprint $table) {
            $table->decimal('bulto', 8, 2)->default(0)->after('puntos');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_articulos', function (Blueprint $table) {
            $table->dropColumn('bulto');
        });
    }
};
