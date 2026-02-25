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
        Schema::create('catalogo_pagina_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogo_pagina_id')
                ->constrained('catalogo_paginas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            // Las coordenadas se guardan en porcentaje respecto del ancho y alto de la imagen de la página.
            $table->decimal('pos_x', 5, 2);
            $table->decimal('pos_y', 5, 2);
            $table->timestamps();

            $table->unique(['catalogo_pagina_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_pagina_productos');
    }
};
