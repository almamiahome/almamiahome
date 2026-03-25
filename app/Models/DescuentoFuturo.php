<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DescuentoFuturo extends Model
{
    use HasFactory;

    protected $table = 'descuentos_futuros';

    protected $fillable = [
        'origen_liquidacion_id',
        'cierre_destino_id',
        'lider_id',
        'coordinadora_id',
        'monto',
        'motivo',
        'detalle_json',
        'estado',
        'auditado_en',
        'auditado_por',
        'motivo_auditoria',
    ];

    protected $casts = [
        'origen_liquidacion_id' => 'integer',
        'cierre_destino_id' => 'integer',
        'lider_id' => 'integer',
        'coordinadora_id' => 'integer',
        'monto' => 'float',
        'detalle_json' => 'array',
        'auditado_en' => 'datetime',
        'auditado_por' => 'integer',
    ];

    public function origenLiquidacion(): BelongsTo
    {
        return $this->belongsTo(LiquidacionCierre::class, 'origen_liquidacion_id');
    }

    public function cierreDestino(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class, 'cierre_destino_id');
    }

    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    public function coordinadora(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinadora_id');
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditado_por');
    }

    public function scopePorCierre($query, int $cierreId)
    {
        return $query->where('cierre_destino_id', $cierreId);
    }

    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }
}
