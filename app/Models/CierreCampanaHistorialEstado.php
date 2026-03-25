<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierreCampanaHistorialEstado extends Model
{
    use HasFactory;

    protected $table = 'cierre_campana_historial_estados';

    protected $fillable = [
        'cierre_campana_id',
        'estado_anterior',
        'estado_nuevo',
        'usuario_id',
        'motivo',
        'datos',
        'fecha_cambio',
    ];

    protected $casts = [
        'datos' => 'array',
        'fecha_cambio' => 'datetime',
    ];

    public function cierreCampana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
