<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lider_altas_cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metrica_lider_campana_id')->constrained('metricas_lider_campana')->cascadeOnDelete();
            $table->foreignId('lider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cierre_campana_id')->constrained('cierres_campana')->cascadeOnDelete();
            $table->unsignedTinyInteger('numero_cuota');
            $table->unsignedInteger('altas_reportadas')->default(0);
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->string('estado')->default('pendiente');
            $table->json('datos')->nullable();
            $table->timestamps();

            $table->unique(['metrica_lider_campana_id', 'numero_cuota'], 'lider_altas_cuota_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lider_altas_cuotas');
    }
};
