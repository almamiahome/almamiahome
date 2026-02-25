<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catalogo_paginas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogo_id')
                ->constrained('catalogos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedInteger('numero');
            $table->string('imagen')->nullable();
            $table->timestamps();

            $table->unique(['catalogo_id', 'numero']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_paginas');
    }
};
