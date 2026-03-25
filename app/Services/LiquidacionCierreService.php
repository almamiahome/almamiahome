<?php

namespace App\Services;

use App\Models\CierreCampana;
use App\Models\Cobro;
use App\Models\DescuentoFuturo;
use App\Models\LiquidacionCierre;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LiquidacionCierreService
{
    public function liquidarPorLider(CierreCampana $cierre, User $lider, array $meta = []): LiquidacionCierre
    {
        $saldos = $this->calcularSaldos($cierre, $lider);

        $liquidacion = LiquidacionCierre::updateOrCreate(
            [
                'cierre_campana_id' => $cierre->id,
                'lider_id' => $lider->id,
            ],
            [
                'coordinadora_id' => $lider->coordinadora_id,
                'saldo_a_cobrar' => $saldos['saldo_a_cobrar'],
                'saldo_a_pagar' => $saldos['saldo_a_pagar'],
                'deuda_arrastrada' => $saldos['deuda_arrastrada'],
                'descuento_aplicado' => $saldos['descuento_aplicado'],
                'balance_neto' => $saldos['balance_neto'],
                'detalle_json' => [
                    'resumen' => $saldos,
                    'meta' => $meta,
                ],
                'estado' => 'calculada',
            ]
        );

        return $liquidacion->refresh();
    }

    public function calcularSaldos(CierreCampana $cierre, User $lider): array
    {
        $cobros = (float) Cobro::query()
            ->where('lider_id', $lider->id)
            ->where('mes_campana', $cierre->codigo)
            ->whereIn('estado', ['pendiente', 'programado', 'pagado'])
            ->sum('monto');

        $pagos = (float) Pago::query()
            ->whereHas('pedido', fn ($q) => $q->where('lider_id', $lider->id))
            ->where('mes_campana', $cierre->codigo)
            ->whereIn('estado', ['pendiente', 'programado', 'pagado'])
            ->sum('monto');

        $deudaArrastrada = (float) LiquidacionCierre::query()
            ->where('lider_id', $lider->id)
            ->where('cierre_campana_id', '<>', $cierre->id)
            ->sum(DB::raw('GREATEST(saldo_a_pagar - saldo_a_cobrar, 0)'));

        $descuentoAplicado = (float) DescuentoFuturo::query()
            ->where('lider_id', $lider->id)
            ->where('cierre_destino_id', $cierre->id)
            ->where('estado', 'aplicado')
            ->sum('monto');

        $balanceNeto = round($cobros - $pagos - $deudaArrastrada + $descuentoAplicado, 2);

        return [
            'saldo_a_cobrar' => round($cobros, 2),
            'saldo_a_pagar' => round($pagos, 2),
            'deuda_arrastrada' => round($deudaArrastrada, 2),
            'descuento_aplicado' => round($descuentoAplicado, 2),
            'balance_neto' => $balanceNeto,
        ];
    }

    public function registrarDescuentoFuturo(
        LiquidacionCierre $origen,
        CierreCampana $cierreDestino,
        string $motivo,
        float $monto,
        array $detalle = []
    ): DescuentoFuturo {
        return DescuentoFuturo::firstOrCreate(
            [
                'origen_liquidacion_id' => $origen->id,
                'cierre_destino_id' => $cierreDestino->id,
                'motivo' => $motivo,
            ],
            [
                'lider_id' => $origen->lider_id,
                'coordinadora_id' => $origen->coordinadora_id,
                'monto' => round($monto, 2),
                'detalle_json' => $detalle,
                'estado' => 'pendiente',
            ]
        );
    }

    public function aplicarDescuentosFuturosAlCierre(CierreCampana $cierreDestino): int
    {
        $procesados = 0;

        DescuentoFuturo::query()
            ->where('cierre_destino_id', $cierreDestino->id)
            ->where('estado', 'pendiente')
            ->orderBy('id')
            ->each(function (DescuentoFuturo $descuento) use (&$procesados) {
                $liquidacion = LiquidacionCierre::query()->firstOrCreate(
                    [
                        'cierre_campana_id' => $descuento->cierre_destino_id,
                        'lider_id' => $descuento->lider_id,
                    ],
                    [
                        'coordinadora_id' => $descuento->coordinadora_id,
                        'estado' => 'borrador',
                        'detalle_json' => [],
                    ]
                );

                $liquidacion->descuento_aplicado = round((float) $liquidacion->descuento_aplicado + (float) $descuento->monto, 2);
                $liquidacion->balance_neto = round((float) $liquidacion->balance_neto + (float) $descuento->monto, 2);
                $liquidacion->save();

                $descuento->estado = 'aplicado';
                $descuento->save();

                $procesados++;
            });

        return $procesados;
    }
}
