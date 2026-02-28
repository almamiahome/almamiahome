<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntajeRegla extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_unidades',
        'max_unidades',
        'descripcion',
        'bonificacion',
        'porcentaje',
        'beneficios',
        'puntaje_minimo',
        'puntaje_minimo_descripcion',
        'puntos_mensuales',
        'puntos_por_campania',
        'datos',
    ];

    protected $casts = [
        'min_unidades' => 'integer',
        'max_unidades' => 'integer',
        'bonificacion' => 'float',
        'porcentaje' => 'float',
        'puntaje_minimo' => 'integer',
        'puntos_mensuales' => 'integer',
        'puntos_por_campania' => 'integer',
        'datos' => 'array',
    ];

    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'categoria_puntaje_regla',
            'puntaje_regla_id',
            'categoria_id'
        );
    }
}
