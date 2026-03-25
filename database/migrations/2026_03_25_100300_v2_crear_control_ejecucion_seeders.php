<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Control de ejecución de seeders para despliegues repetibles en versión 2.
     */
    public function up(): void
    {
        Schema::create('control_ejecucion_seeders', function (Blueprint $table) {
            $table->id();
            $table->string('seeder', 150)->unique();
            $table->string('version', 30)->default('v2');
            $table->timestamp('ejecutado_en')->useCurrent();
            $table->json('datos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('control_ejecucion_seeders');
    }
};
