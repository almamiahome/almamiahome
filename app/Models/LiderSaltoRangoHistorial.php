<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiderSaltoRangoHistorial extends Model
{
    use HasFactory;

    protected $table = 'lider_saltos_rango_historial';

    protected $fillable = [
        'lider_id',
        'cierre_campana_id',
        'rango_anterior_id',
        'rango_nuevo_id',
        'estado',
        'motivo',
        'datos',
    ];

    protected $casts = [
        'lider_id' => 'integer',
        'cierre_campana_id' => 'integer',
        'rango_anterior_id' => 'integer',
        'rango_nuevo_id' => 'integer',
        'datos' => 'array',
    ];

    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    public function cierreCampana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class, 'cierre_campana_id');
    }

    public function rangoAnterior(): BelongsTo
    {
        return $this->belongsTo(RangoLider::class, 'rango_anterior_id');
    }

    public function rangoNuevo(): BelongsTo
    {
        return $this->belongsTo(RangoLider::class, 'rango_nuevo_id');
    }
}
