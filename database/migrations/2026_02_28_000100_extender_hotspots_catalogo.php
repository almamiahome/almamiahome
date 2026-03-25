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

            /**
             * Orden obligatorio:
             * 1) Se elimina la FK actual de producto_id.
             * 2) Recién después se cambia a nullable.
             * 3) Finalmente se vuelve a crear la FK.
             *
             * En MySQL no es válido alterar nulabilidad mientras la FK sigue activa.
             */
            $table->dropForeign(['producto_id']);
        });

        Schema::table('catalogo_pagina_productos', function (Blueprint $table) {
            $table->foreignId('producto_id')->nullable()->change();
        });

        Schema::table('catalogo_pagina_productos', function (Blueprint $table) {
            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
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

        Schema::table('catalogo_pagina_productos', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
        });

        DB::table('catalogo_pagina_productos')->whereNull('producto_id')->delete();

        Schema::table('catalogo_pagina_productos', function (Blueprint $table) {
            /**
             * Orden obligatorio inverso:
             * 1) Con FK eliminada, se limpia data nula incompatible.
             * 2) Se vuelve producto_id NOT NULL.
             * 3) Se recrea la FK para restaurar integridad referencial.
             */
            $table->foreignId('producto_id')->nullable(false)->change();
            $table->dropColumn('es_grupo');
        });

        Schema::table('catalogo_pagina_productos', function (Blueprint $table) {
            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
