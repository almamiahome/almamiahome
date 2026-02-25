<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Agrega columnas necesarias para la gestión de rangos.
     */
    public function up(): void
    {
        Schema::table('rangos_lideres', function (Blueprint $table) {
            // Posición del rango en la lista (1,2,3...)
            $table->unsignedInteger('posicion')->nullable()->after('id');

            // Slug interno para identificar el rango (ej: "rango_bronce")
            $table->string('slug')->nullable()->after('nombre');

            // Color de referencia para la UI
            $table->string('color', 20)->nullable()->after('reparto_referencia');

            // Descripción opcional
            $table->text('descripcion')->nullable()->after('color');
        });

        // Rellenamos datos básicos para los registros existentes
        $rangos = DB::table('rangos_lideres')
            ->orderBy('id')
            ->get();

        $posicion = 1;

        foreach ($rangos as $rango) {
            DB::table('rangos_lideres')
                ->where('id', $rango->id)
                ->update([
                    'posicion'    => $rango->posicion ?? $posicion,
                    'slug'        => $rango->slug
                        ?? Str::slug($rango->nombre ?: ('rango_'.$rango->id), '_'),
                    'color'       => $rango->color ?? '#f0f5ff',
                    // dejamos descripcion como está (null por defecto)
                ]);

            $posicion++;
        }

        // Ahora que todos tienen slug, agregamos UNIQUE sin borrar nada
        Schema::table('rangos_lideres', function (Blueprint $table) {
            $table->unique('slug', 'rangos_lideres_slug_unique');
        });
    }

    /**
     * No eliminamos nada para no perder datos.
     * Si alguna vez quisieras revertir estos cambios,
     * deberías crear otra migración específica.
     */
    public function down(): void
    {
        // Intencionalmente vacío: no se elimina ninguna columna ni índice.
    }
};
