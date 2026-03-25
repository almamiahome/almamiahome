<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevendedoraPunto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catalogo_id',
        'cierre_id',
        'estado',
        'puntos',
        'origen',
        'motivo',
        'saldo_posterior',
        'fecha_entrega',
        'datos',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'catalogo_id' => 'integer',
        'cierre_id' => 'integer',
        'puntos' => 'integer',
        'saldo_posterior' => 'integer',
        'fecha_entrega' => 'datetime',
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
}
