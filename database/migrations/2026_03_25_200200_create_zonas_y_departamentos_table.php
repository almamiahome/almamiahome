<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained('zonas')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('codigo');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['zona_id', 'codigo']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('zona_id')->nullable()->after('coordinadora_id')->constrained('zonas')->nullOnDelete();
            $table->foreignId('departamento_id')->nullable()->after('zona_id')->constrained('departamentos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['zona_id']);
            $table->dropForeign(['departamento_id']);
            $table->dropColumn(['zona_id', 'departamento_id']);
        });

        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('zonas');
    }
};
