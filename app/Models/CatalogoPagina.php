<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoPagina extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalogo_id',
        'numero',
        'imagen',
    ];

    protected $casts = [
        'numero' => 'integer',
    ];

    /**
     * Las posiciones de los productos en esta página se interpretan en porcentaje
     * sobre el ancho y alto de la imagen para permitir un render flotante.
     */
    public function productos()
    {
        return $this->hasMany(CatalogoPaginaProducto::class);
    }

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }
}
