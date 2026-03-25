<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidacionCierre extends Model
{
    use HasFactory;

    protected $table = 'liquidaciones_cierre';

    protected $fillable = [
        'cierre_campana_id',
        'lider_id',
        'coordinadora_id',
        'saldo_a_cobrar',
        'saldo_a_pagar',
        'deuda_arrastrada',
        'descuento_aplicado',
        'balance_neto',
        'detalle_json',
        'estado',
        'auditado_en',
        'auditado_por',
        'motivo_auditoria',
    ];

    protected $casts = [
        'cierre_campana_id' => 'integer',
        'lider_id' => 'integer',
        'coordinadora_id' => 'integer',
        'saldo_a_cobrar' => 'float',
        'saldo_a_pagar' => 'float',
        'deuda_arrastrada' => 'float',
        'descuento_aplicado' => 'float',
        'balance_neto' => 'float',
        'detalle_json' => 'array',
        'auditado_en' => 'datetime',
        'auditado_por' => 'integer',
    ];

    public function cierreCampana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class);
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
        return $query->where('cierre_campana_id', $cierreId);
    }

    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }
}
