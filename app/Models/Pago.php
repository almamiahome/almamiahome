<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'vendedora_id',
        'mes_campana',
        'mes_pago_programado',
        'monto',
        'estado',
        'fecha_pago',
        'detalle',
    ];

    protected $casts = [
        'monto' => 'float',
        'fecha_pago' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pago $pago) {
            if (! filled($pago->estado)) {
                $pago->estado = 'pendiente';
            }

            if (filled($pago->mes_campana) && blank($pago->mes_pago_programado)) {
                $pago->mes_pago_programado = static::calcularMesPago($pago->mes_campana);
            }
        });
    }

    public static function calcularMesPago(string $mesCampana): string
    {
        return Carbon::parse($mesCampana.'-01')->addMonth()->format('Y-m');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function vendedora()
    {
        return $this->belongsTo(User::class, 'vendedora_id');
    }
}
