<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CierreCampana extends Model
{
    use HasFactory;

    public const ESTADO_PLANIFICADO = 'planificado';
    public const ESTADO_ABIERTO = 'abierto';
    public const ESTADO_LIQUIDACION = 'en_liquidacion';
    public const ESTADO_CERRADO = 'cerrado';

    public const ESTADOS_VALIDOS = [
        self::ESTADO_PLANIFICADO,
        self::ESTADO_ABIERTO,
        self::ESTADO_LIQUIDACION,
        self::ESTADO_CERRADO,
    ];

    protected $table = 'cierres_campana';

    protected $fillable = [
        'nombre',
        'codigo',
        'catalogo_id',
        'numero_cierre',
        'fecha_inicio',
        'fecha_cierre',
        'fecha_liquidacion',
        'estado',
        'datos',
    ];

    protected $casts = [
        'catalogo_id' => 'integer',
        'numero_cierre' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_cierre' => 'date',
        'fecha_liquidacion' => 'date',
        'datos' => 'array',
    ];

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function premioReglas(): HasMany
    {
        return $this->hasMany(PremioRegla::class, 'campana_id');
    }

    public function metricas(): HasMany
    {
        return $this->hasMany(MetricaLiderCampana::class);
    }
}
