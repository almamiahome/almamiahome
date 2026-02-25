<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'codigo_pedido',
        'vendedora_id',
        'lider_id',
        'coordinadora_id',
        'responsable_id',
        'fecha',
        'mes',
        'catalogo_nro',
        'total_precio_catalogo',
        'total_gastos',
        'total_ganancias',
        'total_a_pagar',
        'total_puntos',
        'cantidad_unidades',
        'estado',
        'observaciones',
        'datos_pedido'
    ];

    protected $casts = [
        'datos_pedido' => 'array',
        'coordinadora_id' => 'integer',
    ];

    public function articulos()
    {
        return $this->hasMany(PedidoArticulo::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function cobros()
    {
        return $this->hasMany(Cobro::class);
    }

    public function vendedora()
    {
        return $this->belongsTo(User::class, 'vendedora_id');
    }

    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    public function coordinadora()
    {
        return $this->belongsTo(User::class, 'coordinadora_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
