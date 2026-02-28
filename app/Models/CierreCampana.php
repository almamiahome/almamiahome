<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CierreCampana extends Model
{
    use HasFactory;

    protected $table = 'cierres_campana';

    protected $fillable = [
        'nombre',
        'codigo',
        'fecha_inicio',
        'fecha_cierre',
        'estado',
        'datos',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_cierre' => 'date',
        'datos' => 'array',
    ];

    public function premioReglas(): HasMany
    {
        return $this->hasMany(PremioRegla::class);
    }

    public function metricas(): HasMany
    {
        return $this->hasMany(MetricaLiderCampana::class);
    }
}
