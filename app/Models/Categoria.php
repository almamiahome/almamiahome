<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'categoria_producto');
    }

    public function puntajeReglas()
    {
        return $this->belongsToMany(
            PuntajeRegla::class,
            'categoria_puntaje_regla',
            'categoria_id',
            'puntaje_regla_id'
        );
    }

    public function puntajeRegla()
    {
        return $this->puntajeReglas();
    }

}
