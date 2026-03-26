<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PremioLiderCierre extends Model
{
    use HasFactory;

    protected $table = 'premio_lider_cierre';

    protected $fillable = [
        'lider_id',
        'cierre_campana_id',
        'rango_lider_id',
        'metrica_lider_campana_id',
        'premio_actividad',
        'premio_retencion',
        'premio_altas',
        'premio_cobranza',
        'premio_crecimiento',
        'premio_reparto',
        'premio_plus_crecimiento',
        'premio_unidades',
        'premio_total',
        'detalle',
    ];

    protected $casts = [
        'lider_id' => 'integer',
        'cierre_campana_id' => 'integer',
        'rango_lider_id' => 'integer',
        'metrica_lider_campana_id' => 'integer',
        'premio_actividad' => 'float',
        'premio_retencion' => 'float',
        'premio_altas' => 'float',
        'premio_cobranza' => 'float',
        'premio_crecimiento' => 'float',
        'premio_reparto' => 'float',
        'premio_plus_crecimiento' => 'float',
        'premio_unidades' => 'float',
        'premio_total' => 'float',
        'detalle' => 'array',
    ];

    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    public function cierreCampana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class);
    }

    public function rangoLider(): BelongsTo
    {
        return $this->belongsTo(RangoLider::class);
    }

    public function metricaLiderCampana(): BelongsTo
    {
        return $this->belongsTo(MetricaLiderCampana::class);
    }
}
