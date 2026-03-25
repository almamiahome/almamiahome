<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'catalogo_id',
        'cierre_id',
        'total_precio_catalogo',
        'total_gastos',
        'total_ganancias',
        'total_a_pagar',
        'total_puntos',
        'cantidad_unidades',
        'unidades_facturables',
        'unidades_auxiliares',
        'estado',
        'estado_pago',
        'comprobante_pago_path',
        'comprobante_pago_subido_en',
        'observaciones',
        'datos_pedido'
    ];

    protected $casts = [
        'datos_pedido' => 'array',
        'catalogo_id' => 'integer',
        'cierre_id' => 'integer',
        'coordinadora_id' => 'integer',
        'comprobante_pago_subido_en' => 'datetime',
        'unidades_facturables' => 'integer',
        'unidades_auxiliares' => 'integer',
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

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class, 'catalogo_id');
    }

    public function cierreCampana(): BelongsTo
    {
        return $this->belongsTo(CierreCampana::class, 'cierre_id');
    }
}

