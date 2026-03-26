<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RangoLider extends Model
{
    use HasFactory;

    protected $table = 'rangos_lideres';

    protected $fillable = [
        'nombre',
        'revendedoras_minimas',
        'revendedoras_maximas',
        'unidades_minimas',
        'premio_actividad',
        'premio_unidades',
        'premio_cobranzas',
        'reparto_referencia',
    ];

    protected $casts = [
        'revendedoras_minimas' => 'integer',
        'revendedoras_maximas' => 'integer',
        'unidades_minimas' => 'integer',
        'premio_actividad' => 'float',
        'premio_unidades' => 'float',
        'premio_cobranzas' => 'float',
        'reparto_referencia' => 'float',
    ];

    public function premioReglas(): HasMany
    {
        return $this->hasMany(PremioRegla::class);
    }

    public function metricasCampanas(): HasMany
    {
        return $this->hasMany(MetricaLiderCampana::class);
    }

    public function premiosCierre(): HasMany
    {
        return $this->hasMany(PremioLiderCierre::class);
    }

    public function lideres(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'metricas_lider_campana', 'rango_lider_id', 'lider_id')
            ->withPivot([
                'actividad_ok',
                'altas_ok',
                'unidades_ok',
                'cobranzas_ok',
                'crecimiento_ok',
                'altas_pagadas_en_cierre',
                'cantidad_1c',
                'cantidad_2c',
                'cantidad_3c',
                'monto_reparto_total',
                'premio_actividad',
                'premio_unidades',
                'premio_cobranzas',
                'premio_altas',
                'premio_crecimiento',
                'premio_total',
                'fecha_pago_equipo',
            ])
            ->withTimestamps();
    }
}
