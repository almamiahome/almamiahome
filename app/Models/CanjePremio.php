<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CanjePremio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tienda_premio_id',
        'catalogo_id',
        'cierre_id',
        'estado',
        'puntos_canjeados',
        'origen',
        'motivo',
        'saldo_posterior',
        'fecha_entrega',
        'fecha_canje',
        'datos',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'tienda_premio_id' => 'integer',
        'catalogo_id' => 'integer',
        'cierre_id' => 'integer',
        'puntos_canjeados' => 'integer',
        'saldo_posterior' => 'integer',
        'fecha_entrega' => 'datetime',
        'fecha_canje' => 'datetime',
        'datos' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tiendaPremio(): BelongsTo
    {
        return $this->belongsTo(TiendaPremio::class);
    }

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function cierre(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class, 'cierre_id');
    }
}
