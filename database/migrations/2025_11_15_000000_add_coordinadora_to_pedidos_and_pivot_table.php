<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('coordinadora_id')
                ->nullable()
                ->after('lider_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        Schema::create('coordinadora_lider', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coordinadora_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lider_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['coordinadora_id', 'lider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordinadora_lider');

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coordinadora_id');
        });
    }
};
