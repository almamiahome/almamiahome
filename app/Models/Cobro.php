<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'lider_id',
        'coordinadora_id',
        'mes_campana',
        'mes_pago_programado',
        'monto',
        'estado',
        'concepto',
        'fecha_pago',
    ];

    protected $casts = [
        'monto' => 'float',
        'fecha_pago' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Cobro $cobro) {
            if (! filled($cobro->estado)) {
                $cobro->estado = 'pendiente';
            }

            if (filled($cobro->mes_campana) && blank($cobro->mes_pago_programado)) {
                $cobro->mes_pago_programado = static::calcularMesPago($cobro->mes_campana);
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

    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    public function coordinadora()
    {
        return $this->belongsTo(User::class, 'coordinadora_id');
    }
}
