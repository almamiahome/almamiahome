<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extensiones preparatorias para catálogo versión 2.
     */
    public function up(): void
    {
        if (Schema::hasTable('catalogo_hotspot_producto')) {
            Schema::table('catalogo_hotspot_producto', function (Blueprint $table) {
                $table->unsignedSmallInteger('orden')->default(1)->after('producto_id');
                $table->json('datos')->nullable()->after('orden');
                $table->index(['catalogo_pagina_producto_id', 'orden'], 'catalogo_hotspot_orden_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('catalogo_hotspot_producto')) {
            Schema::table('catalogo_hotspot_producto', function (Blueprint $table) {
                $table->dropIndex('catalogo_hotspot_orden_idx');
                $table->dropColumn(['orden', 'datos']);
            });
        }
    }
};
