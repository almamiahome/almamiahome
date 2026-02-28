<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('rangos_lideres', 'slug')) {
            Schema::table('rangos_lideres', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }

        if (Schema::hasColumn('rangos_lideres', 'posicion')) {
            Schema::table('rangos_lideres', function (Blueprint $table) {
                $table->dropColumn('posicion');
            });
        }

        foreach (['color', 'descripcion', 'datos'] as $columna) {
            if (Schema::hasColumn('rangos_lideres', $columna)) {
                Schema::table('rangos_lideres', function (Blueprint $table) use ($columna) {
                    $table->dropColumn($columna);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rangos_lideres', function (Blueprint $table) {
            if (! Schema::hasColumn('rangos_lideres', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('nombre');
            }

            if (! Schema::hasColumn('rangos_lideres', 'posicion')) {
                $table->unsignedTinyInteger('posicion')->default(1)->after('slug');
            }

            if (! Schema::hasColumn('rangos_lideres', 'color')) {
                $table->string('color')->nullable()->after('reparto_referencia');
            }

            if (! Schema::hasColumn('rangos_lideres', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('color');
            }

            if (! Schema::hasColumn('rangos_lideres', 'datos')) {
                $table->json('datos')->nullable()->after('descripcion');
            }
        });
    }
};
