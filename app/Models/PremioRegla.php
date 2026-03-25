<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PremioRegla extends Model
{
    use HasFactory;

    protected $fillable = [
        'rango_lider_id',
        'campana_id',
        'tipo',
        'umbral_minimo',
        'umbral_maximo',
        'monto',
        'datos',
    ];

    protected $casts = [
        'rango_lider_id' => 'integer',
        'campana_id' => 'integer',
        'umbral_minimo' => 'integer',
        'umbral_maximo' => 'integer',
        'monto' => 'float',
        'datos' => 'array',
    ];

    public function rangoLider(): BelongsTo
    {
        return $this->belongsTo(RangoLider::class);
    }

    public function campana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class, 'campana_id');
    }
}
