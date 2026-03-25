<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetricaLiderCampana extends Model
{
    use HasFactory;

    protected $table = 'metricas_lider_campana';

    protected $fillable = [
        'lider_id',
        'revendedora_id',
        'cierre_campana_id',
        'rango_lider_id',
        'actividad_ok',
        'altas_ok',
        'unidades_ok',
        'cobranzas_ok',
        'crecimiento_ok',
        'retencion_ok',
        'plus_crecimiento_ok',
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
        'premio_retencion',
        'premio_plus_crecimiento',
        'premio_total',
        'fecha_pago_equipo',
        'objetivo_proximo_cierre',
        'actividad_cierre_anterior',
        'datos',
    ];

    protected $casts = [
        'lider_id' => 'integer',
        'revendedora_id' => 'integer',
        'cierre_campana_id' => 'integer',
        'rango_lider_id' => 'integer',
        'actividad_ok' => 'boolean',
        'altas_ok' => 'boolean',
        'unidades_ok' => 'boolean',
        'cobranzas_ok' => 'boolean',
        'crecimiento_ok' => 'boolean',
        'retencion_ok' => 'boolean',
        'plus_crecimiento_ok' => 'boolean',
        'altas_pagadas_en_cierre' => 'array',
        'cantidad_1c' => 'integer',
        'cantidad_2c' => 'integer',
        'cantidad_3c' => 'integer',
        'monto_reparto_total' => 'float',
        'premio_actividad' => 'float',
        'premio_unidades' => 'float',
        'premio_cobranzas' => 'float',
        'premio_altas' => 'float',
        'premio_crecimiento' => 'float',
        'premio_retencion' => 'float',
        'premio_plus_crecimiento' => 'float',
        'premio_total' => 'float',
        'fecha_pago_equipo' => 'datetime',
        'objetivo_proximo_cierre' => 'integer',
        'actividad_cierre_anterior' => 'integer',
        'datos' => 'array',
    ];

    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    public function revendedora(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revendedora_id');
    }

    public function cierreCampana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class);
    }

    public function rangoLider(): BelongsTo
    {
        return $this->belongsTo(RangoLider::class);
    }
}
