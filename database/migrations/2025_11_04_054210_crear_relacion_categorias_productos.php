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
        if (Schema::hasTable('categoria_producto') && ! $this->tieneIndiceUnicoCategoriaProducto()) {
            Schema::table('categoria_producto', function (Blueprint $table) {
                $table->unique(['categoria_id', 'producto_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('categoria_producto') && $this->tieneIndiceUnicoCategoriaProducto()) {
            Schema::table('categoria_producto', function (Blueprint $table) {
                $table->dropUnique(['categoria_id', 'producto_id']);
            });
        }
    }
    
    private function tieneIndiceUnicoCategoriaProducto(): bool
    {
        $connection = DB::connection();

        if ($connection->getDriverName() === 'sqlite') {
            $indices = $connection->select("PRAGMA index_list('categoria_producto')");

            foreach ($indices as $indice) {
                if (! ($indice->unique ?? false)) {
                    continue;
                }

                $columnas = collect($connection->select("PRAGMA index_info('{$indice->name}')"))
                    ->pluck('name')
                    ->implode(',');

                if ($columnas === 'categoria_id,producto_id') {
                    return true;
                }
            }

            return false;
        }

        $indice = DB::table('information_schema.statistics')
            ->selectRaw('index_name, GROUP_CONCAT(column_name ORDER BY seq_in_index) as columnas')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'categoria_producto')
            ->where('non_unique', 0)
            ->groupBy('index_name')
            ->get()
            ->first(fn ($fila) => $fila->columnas === 'categoria_id,producto_id');

        return (bool) $indice;
    }
};
