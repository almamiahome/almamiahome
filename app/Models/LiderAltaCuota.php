<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiderAltaCuota extends Model
{
    use HasFactory;

    protected $table = 'lider_altas_cuotas';

    protected $fillable = [
        'metrica_lider_campana_id',
        'lider_id',
        'cierre_campana_id',
        'numero_cuota',
        'altas_reportadas',
        'monto_pagado',
        'estado',
        'datos',
    ];

    protected $casts = [
        'metrica_lider_campana_id' => 'integer',
        'lider_id' => 'integer',
        'cierre_campana_id' => 'integer',
        'numero_cuota' => 'integer',
        'altas_reportadas' => 'integer',
        'monto_pagado' => 'float',
        'datos' => 'array',
    ];

    public function metrica(): BelongsTo
    {
        return $this->belongsTo(MetricaLiderCampana::class, 'metrica_lider_campana_id');
    }

    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    public function cierreCampana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class, 'cierre_campana_id');
    }
}
