<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            if (! Schema::hasColumn('catalogos', 'anio')) {
                $table->unsignedSmallInteger('anio')->nullable()->after('descripcion');
            }

            if (! Schema::hasColumn('catalogos', 'numero')) {
                $table->unsignedTinyInteger('numero')->nullable()->after('anio');
            }
        });

        $catalogos = DB::table('catalogos')->orderBy('id')->get(['id']);

        foreach ($catalogos as $index => $catalogo) {
            $anio = 2026 + intdiv($index, 4);
            $numero = ($index % 4) + 1;

            DB::table('catalogos')->where('id', $catalogo->id)->update([
                'anio' => $anio,
                'numero' => $numero,
            ]);
        }

        Schema::table('catalogos', function (Blueprint $table) {
            $table->unique(['anio', 'numero'], 'catalogos_anio_numero_unique');
        });

        Schema::table('cierres_campana', function (Blueprint $table) {
            if (! Schema::hasColumn('cierres_campana', 'catalogo_id')) {
                $table->foreignId('catalogo_id')
                    ->nullable()
                    ->after('codigo')
                    ->constrained('catalogos')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('cierres_campana', 'numero_cierre')) {
                $table->unsignedTinyInteger('numero_cierre')->default(1)->after('catalogo_id');
            }

            if (! Schema::hasColumn('cierres_campana', 'fecha_liquidacion')) {
                $table->date('fecha_liquidacion')->nullable()->after('fecha_cierre');
            }
        });

        DB::table('cierres_campana')
            ->whereIn('estado', ['borrador', 'configurada'])
            ->update(['estado' => 'planificado']);

        DB::table('cierres_campana')
            ->where('estado', 'cerrada')
            ->update(['estado' => 'cerrado']);

        $catalogosPorAnio = DB::table('catalogos')
            ->orderBy('anio')
            ->orderBy('numero')
            ->pluck('id')
            ->all();

        $cierres = DB::table('cierres_campana')->orderBy('id')->get(['id', 'catalogo_id']);
        $conteoPorCatalogo = [];

        foreach ($cierres as $index => $cierre) {
            $catalogoId = $cierre->catalogo_id ?? ($catalogosPorAnio[$index % max(count($catalogosPorAnio), 1)] ?? null);

            if (! $catalogoId) {
                continue;
            }

            $conteoPorCatalogo[$catalogoId] = ($conteoPorCatalogo[$catalogoId] ?? 0) + 1;
            $numeroCierre = (($conteoPorCatalogo[$catalogoId] - 1) % 3) + 1;

            DB::table('cierres_campana')->where('id', $cierre->id)->update([
                'catalogo_id' => $catalogoId,
                'numero_cierre' => $numeroCierre,
            ]);
        }

        Schema::table('cierres_campana', function (Blueprint $table) {
            $table->index(['catalogo_id', 'numero_cierre'], 'cierres_catalogo_numero_idx');
            $table->index(['estado', 'fecha_inicio', 'fecha_cierre'], 'cierres_estado_fechas_idx');
        });
    }

    public function down(): void
    {
        Schema::table('cierres_campana', function (Blueprint $table) {
            $table->dropIndex('cierres_catalogo_numero_idx');
            $table->dropIndex('cierres_estado_fechas_idx');

            if (Schema::hasColumn('cierres_campana', 'catalogo_id')) {
                $table->dropConstrainedForeignId('catalogo_id');
            }

            if (Schema::hasColumn('cierres_campana', 'numero_cierre')) {
                $table->dropColumn('numero_cierre');
            }

            if (Schema::hasColumn('cierres_campana', 'fecha_liquidacion')) {
                $table->dropColumn('fecha_liquidacion');
            }
        });

        Schema::table('catalogos', function (Blueprint $table) {
            $table->dropUnique('catalogos_anio_numero_unique');

            if (Schema::hasColumn('catalogos', 'numero')) {
                $table->dropColumn('numero');
            }

            if (Schema::hasColumn('catalogos', 'anio')) {
                $table->dropColumn('anio');
            }
        });
    }
};
