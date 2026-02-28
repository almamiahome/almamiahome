<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Crear tabla pivot
        Schema::create('categoria_puntaje_regla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->cascadeOnDelete();

            $table->foreignId('puntaje_regla_id')
                ->constrained('puntaje_reglas')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['categoria_id', 'puntaje_regla_id']);
        });

        // Migrar datos existentes desde categorias.puntaje_regla_id al pivot
        if (Schema::hasColumn('categorias', 'puntaje_regla_id')) {
            DB::table('categorias')
                ->whereNotNull('puntaje_regla_id')
                ->orderBy('id')
                ->chunkById(100, function ($categorias) {
                    $now = now();

                    $rows = [];
                    foreach ($categorias as $categoria) {
                        $rows[] = [
                            'categoria_id' => $categoria->id,
                            'puntaje_regla_id' => $categoria->puntaje_regla_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if (! empty($rows)) {
                        DB::table('categoria_puntaje_regla')->insert($rows);
                    }
                });

            // Soltar la FK y la columna vieja
            Schema::table('categorias', function (Blueprint $table) {
                $table->dropForeign(['puntaje_regla_id']);
                $table->dropColumn('puntaje_regla_id');
            });
        }
    }

    public function down(): void
    {
        // Volver a agregar la columna simple (una regla por categoria)
        Schema::table('categorias', function (Blueprint $table) {
            $table->foreignId('puntaje_regla_id')
                ->nullable()
                ->constrained('puntaje_reglas')
                ->nullOnDelete();
        });

        // Restaurar un valor basico desde el pivot (por ejemplo, la primera regla que encuentre)
        if (Schema::hasTable('categoria_puntaje_regla')) {
            DB::table('categoria_puntaje_regla')
                ->orderBy('id')
                ->chunkById(100, function ($rows) {
                    foreach ($rows as $row) {
                        // Solo setear si la categoria todavia no tiene valor
                        DB::table('categorias')
                            ->where('id', $row->categoria_id)
                            ->whereNull('puntaje_regla_id')
                            ->update(['puntaje_regla_id' => $row->puntaje_regla_id]);
                    }
                });
        }

        Schema::dropIfExists('categoria_puntaje_regla');
    }
};
