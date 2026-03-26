<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BilleteraMovimiento extends Model
{
    use HasFactory;

    protected $table = 'billetera_movimientos';

    protected $fillable = [
        'user_id',
        'catalogo_id',
        'cierre_id',
        'liquidacion_cierre_id',
        'tipo_saldo',
        'naturaleza',
        'monto',
        'puntos',
        'origen',
        'estado',
        'detalle',
        'fecha_movimiento',
        'idempotencia_clave',
        'referencia_type',
        'referencia_id',
        'datos',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'catalogo_id' => 'integer',
        'cierre_id' => 'integer',
        'liquidacion_cierre_id' => 'integer',
        'monto' => 'float',
        'puntos' => 'integer',
        'fecha_movimiento' => 'datetime',
        'datos' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function cierre(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class, 'cierre_id');
    }

    public function liquidacionCierre(): BelongsTo
    {
        return $this->belongsTo(LiquidacionCierre::class, 'liquidacion_cierre_id');
    }

    public function referencia(): MorphTo
    {
        return $this->morphTo();
    }
}
