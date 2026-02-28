<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'puntos_por_unidad',
        'sku',
        'descripcion',
        'stock_actual',
        'activo',
        'altura',
        'anchura',
        'profundidad',
        'bulto',
        'imagen',
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_producto');
    }
}
