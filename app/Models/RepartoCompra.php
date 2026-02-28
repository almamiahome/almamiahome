<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepartoCompra extends Model
{
    use HasFactory;

    protected $table = 'repartos_compras';

    protected $fillable = [
        'tipo_compra',
        'monto_por_revendedora',
        'porcentaje_lider',
        'porcentaje_revendedora',
        'descripcion',
        'datos',
    ];

    protected $casts = [
        'tipo_compra' => 'string',
        'monto_por_revendedora' => 'float',
        'porcentaje_lider' => 'float',
        'porcentaje_revendedora' => 'float',
        'datos' => 'array',
    ];
}
