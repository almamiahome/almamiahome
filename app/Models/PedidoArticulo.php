<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoArticulo extends Model
{
    protected $fillable = [
        'pedido_id',
        'sku',
        'producto',
        'descripcion',
        'cantidad',
        'precio_catalogo',
        'porcentaje_descuento',
        'ganancia',
        'precio_unitario',
        'subtotal',
        'bulto',
        'puntos',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
