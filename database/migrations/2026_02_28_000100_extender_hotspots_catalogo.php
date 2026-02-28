<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catalogo_pagina_productos', function (Blueprint $table) {
            $table->boolean('es_grupo')->default(false)->after('producto_id');
            $table->foreignId('producto_id')->nullable()->change();
        });

        Schema::create('catalogo_hotspot_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogo_pagina_producto_id')
                ->constrained('catalogo_pagina_productos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['catalogo_pagina_producto_id', 'producto_id'], 'catalogo_hotspot_producto_unico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_hotspot_producto');

        DB::table('catalogo_pagina_productos')->whereNull('producto_id')->delete();

        Schema::table('catalogo_pagina_productos', function (Blueprint $table) {
            $table->dropColumn('es_grupo');
            $table->foreignId('producto_id')->nullable(false)->change();
        });
    }
};
