<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoPaginaProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalogo_pagina_id',
        'producto_id',
        'pos_x',
        'pos_y',
    ];

    protected $casts = [
        'pos_x' => 'float',
        'pos_y' => 'float',
    ];

    /**
     * pos_x y pos_y representan el porcentaje horizontal y vertical (0 a 100)
     * relativo al ancho y alto de la imagen de página para ubicar el render flotante.
     */
    public function pagina()
    {
        return $this->belongsTo(CatalogoPagina::class, 'catalogo_pagina_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
