<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('premio_reglas', function (Blueprint $table) {
            if (! Schema::hasColumn('premio_reglas', 'campana_id')) {
                $table->foreignId('campana_id')
                    ->nullable()
                    ->after('rango_lider_id')
                    ->constrained('cierres_campana')
                    ->nullOnDelete();
            }
        });

        if (Schema::hasColumn('premio_reglas', 'cierre_campana_id')) {
            DB::table('premio_reglas')->update([
                'campana_id' => DB::raw('cierre_campana_id'),
            ]);

            Schema::table('premio_reglas', function (Blueprint $table) {
                $table->dropConstrainedForeignId('cierre_campana_id');
            });
        }

        Schema::table('premio_reglas', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                Schema::hasColumn('premio_reglas', 'nombre') ? 'nombre' : null,
                Schema::hasColumn('premio_reglas', 'descripcion') ? 'descripcion' : null,
                Schema::hasColumn('premio_reglas', 'cuotas') ? 'cuotas' : null,
                Schema::hasColumn('premio_reglas', 'compra_orden') ? 'compra_orden' : null,
            ]);

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('premio_reglas', function (Blueprint $table) {
            if (! Schema::hasColumn('premio_reglas', 'cierre_campana_id')) {
                $table->foreignId('cierre_campana_id')
                    ->nullable()
                    ->after('rango_lider_id')
                    ->constrained('cierres_campana')
                    ->nullOnDelete();
            }
        });

        DB::table('premio_reglas')->update([
            'cierre_campana_id' => DB::raw('campana_id'),
        ]);

        Schema::table('premio_reglas', function (Blueprint $table) {
            if (Schema::hasColumn('premio_reglas', 'campana_id')) {
                $table->dropConstrainedForeignId('campana_id');
            }

            if (! Schema::hasColumn('premio_reglas', 'nombre')) {
                $table->string('nombre')->default('')->after('tipo');
            }

            if (! Schema::hasColumn('premio_reglas', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('nombre');
            }

            if (! Schema::hasColumn('premio_reglas', 'cuotas')) {
                $table->unsignedTinyInteger('cuotas')->default(1)->after('monto');
            }

            if (! Schema::hasColumn('premio_reglas', 'compra_orden')) {
                $table->unsignedTinyInteger('compra_orden')->nullable()->after('cuotas');
            }
        });
    }
};
