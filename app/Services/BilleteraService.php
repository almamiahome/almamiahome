<?php

namespace App\Services;

use App\Models\BilleteraMovimiento;
use App\Models\CierreCampana;
use App\Models\LiquidacionCierre;
use App\Models\MetricaLiderCampana;
use App\Models\PuntajeRegla;
use App\Models\RangoLider;
use App\Models\RevendedoraPunto;
use App\Models\User;

class BilleteraService
{
    public function registrarMovimiento(array $payload): BilleteraMovimiento
    {
        $idempotenciaClave = $payload['idempotencia_clave'] ?? null;

        if ($idempotenciaClave) {
            return BilleteraMovimiento::query()->updateOrCreate(
                [
                    'user_id' => $payload['user_id'],
                    'idempotencia_clave' => $idempotenciaClave,
                ],
                $payload,
            );
        }

        return BilleteraMovimiento::query()->create($payload);
    }

    public function sincronizarDesdeMovimientoPuntos(RevendedoraPunto $movimiento): BilleteraMovimiento
    {
        return $this->registrarMovimiento([
            'user_id' => $movimiento->user_id,
            'catalogo_id' => $movimiento->catalogo_id,
            'cierre_id' => $movimiento->cierre_id,
            'tipo_saldo' => 'puntos',
            'naturaleza' => $movimiento->puntos >= 0 ? 'credito' : 'debito',
            'monto' => (float) $movimiento->puntos,
            'puntos' => $movimiento->puntos,
            'origen' => $movimiento->origen,
            'estado' => $movimiento->estado,
            'detalle' => $movimiento->motivo,
            'fecha_movimiento' => $movimiento->created_at,
            'idempotencia_clave' => 'revendedora_punto:' . $movimiento->id,
            'referencia_type' => RevendedoraPunto::class,
            'referencia_id' => $movimiento->id,
            'datos' => [
                'origen_modelo' => 'RevendedoraPunto',
                'saldo_posterior' => $movimiento->saldo_posterior,
                'datos' => $movimiento->datos,
            ],
        ]);
    }

    public function sincronizarDesdeLiquidacion(LiquidacionCierre $liquidacion): BilleteraMovimiento
    {
        return $this->registrarMovimiento([
            'user_id' => $liquidacion->lider_id,
            'catalogo_id' => $liquidacion->cierreCampana?->catalogo_id,
            'cierre_id' => $liquidacion->cierre_campana_id,
            'liquidacion_cierre_id' => $liquidacion->id,
            'tipo_saldo' => 'dinero',
            'naturaleza' => $liquidacion->balance_neto >= 0 ? 'credito' : 'debito',
            'monto' => (float) $liquidacion->balance_neto,
            'origen' => 'liquidacion_cierre',
            'estado' => $liquidacion->estado,
            'detalle' => 'Liquidación de cierre ' . ($liquidacion->cierreCampana?->codigo ?? $liquidacion->cierre_campana_id),
            'fecha_movimiento' => $liquidacion->updated_at,
            'idempotencia_clave' => 'liquidacion_cierre:' . $liquidacion->id,
            'referencia_type' => LiquidacionCierre::class,
            'referencia_id' => $liquidacion->id,
            'datos' => [
                'saldo_a_cobrar' => $liquidacion->saldo_a_cobrar,
                'saldo_a_pagar' => $liquidacion->saldo_a_pagar,
                'deuda_arrastrada' => $liquidacion->deuda_arrastrada,
                'descuento_aplicado' => $liquidacion->descuento_aplicado,
            ],
        ]);
    }

    public function sincronizarDesdeMetricaLider(MetricaLiderCampana $metrica): BilleteraMovimiento
    {
        return $this->registrarMovimiento([
            'user_id' => $metrica->lider_id,
            'catalogo_id' => $metrica->cierreCampana?->catalogo_id,
            'cierre_id' => $metrica->cierre_campana_id,
            'tipo_saldo' => 'dinero',
            'naturaleza' => ((float) $metrica->premio_total) >= 0 ? 'credito' : 'debito',
            'monto' => (float) $metrica->premio_total,
            'origen' => 'premios_lider',
            'estado' => 'calculada',
            'detalle' => 'Premio de liderazgo ' . ($metrica->cierreCampana?->codigo ?? $metrica->cierre_campana_id),
            'fecha_movimiento' => $metrica->updated_at,
            'idempotencia_clave' => 'metrica_lider:' . $metrica->id,
            'referencia_type' => MetricaLiderCampana::class,
            'referencia_id' => $metrica->id,
            'datos' => [
                'rango_lider_id' => $metrica->rango_lider_id,
                'premio_total' => $metrica->premio_total,
            ],
        ]);
    }

    public function construirResumen(User $user, int $limiteMovimientos = 30): array
    {
        $cierreVigente = $this->resolverCierreVigente();
        $cierreSiguiente = $this->resolverCierreSiguiente($cierreVigente);

        $saldoPuntosActual = (int) RevendedoraPunto::query()
            ->where('user_id', $user->id)
            ->sum('puntos');

        $saldoDineroActual = round((float) BilleteraMovimiento::query()
            ->where('user_id', $user->id)
            ->where('tipo_saldo', 'dinero')
            ->sum('monto'), 2);

        $saldoCobrarMes = (float) LiquidacionCierre::query()
            ->where('lider_id', $user->id)
            ->when($cierreVigente, fn ($query) => $query->where('cierre_campana_id', $cierreVigente->id))
            ->sum('saldo_a_cobrar');

        $proyeccionSiguienteCierre = $this->proyectarSiguienteCierre($user, $cierreSiguiente, $saldoCobrarMes);

        $puntajePeriodo = RevendedoraPunto::query()
            ->selectRaw('cierre_id, SUM(puntos) as total_puntos')
            ->where('user_id', $user->id)
            ->groupBy('cierre_id')
            ->with('cierre:id,codigo,numero_cierre')
            ->orderByDesc('cierre_id')
            ->limit(6)
            ->get()
            ->map(fn (RevendedoraPunto $mov) => [
                'periodo' => $mov->cierre?->codigo ?? 'Sin cierre',
                'cierre' => $mov->cierre?->numero_cierre,
                'puntos' => (int) $mov->total_puntos,
            ])
            ->values()
            ->all();

        $resumenRangos = $this->resolverRangos($user, $saldoPuntosActual);
        $clasificacion = $this->resolverClasificacionPremios($saldoPuntosActual);

        $movimientos = BilleteraMovimiento::query()
            ->with('cierre:id,codigo,numero_cierre')
            ->where('user_id', $user->id)
            ->orderByDesc('fecha_movimiento')
            ->orderByDesc('id')
            ->limit($limiteMovimientos)
            ->get()
            ->map(fn (BilleteraMovimiento $movimiento) => [
                'fecha' => optional($movimiento->fecha_movimiento)->format('d/m/Y H:i') ?? optional($movimiento->created_at)->format('d/m/Y H:i'),
                'origen' => $movimiento->origen,
                'campana_cierre' => $movimiento->cierre
                    ? ($movimiento->cierre->codigo . ' · Cierre ' . $movimiento->cierre->numero_cierre)
                    : 'Sin cierre asociado',
                'detalle' => $movimiento->detalle ?? 'Sin detalle',
                'naturaleza' => $movimiento->naturaleza,
                'tipo_saldo' => $movimiento->tipo_saldo,
                'monto' => (float) $movimiento->monto,
                'puntos' => $movimiento->puntos,
                'estado' => $movimiento->estado,
            ])
            ->values()
            ->all();

        return [
            'saldo_actual' => $saldoDineroActual,
            'saldo_puntos_actual' => $saldoPuntosActual,
            'saldo_a_cobrar_mes_vigente' => round($saldoCobrarMes, 2),
            'saldo_proyectado_siguiente_cierre' => round($proyeccionSiguienteCierre, 2),
            'puntaje_ganado_por_periodo' => $puntajePeriodo,
            'rango_actual' => $resumenRangos['rango_actual'],
            'faltante_proximo_rango' => $resumenRangos['faltante'],
            'clasificacion_premios' => $clasificacion,
            'movimientos' => $movimientos,
            'cierre_vigente' => $cierreVigente?->codigo,
            'cierre_siguiente' => $cierreSiguiente?->codigo,
        ];
    }

    protected function resolverCierreVigente(): ?CierreCampana
    {
        $hoy = now()->toDateString();

        return CierreCampana::query()
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_cierre', '>=', $hoy)
            ->orderByDesc('fecha_inicio')
            ->first()
            ?: CierreCampana::query()->orderByDesc('fecha_inicio')->first();
    }

    protected function resolverCierreSiguiente(?CierreCampana $cierreVigente): ?CierreCampana
    {
        if (! $cierreVigente) {
            return null;
        }

        return CierreCampana::query()
            ->where('catalogo_id', $cierreVigente->catalogo_id)
            ->where('numero_cierre', '>', (int) $cierreVigente->numero_cierre)
            ->orderBy('numero_cierre')
            ->first();
    }

    protected function proyectarSiguienteCierre(User $user, ?CierreCampana $siguienteCierre, float $saldoCobrarMes): float
    {
        $ultimoPremioLider = (float) MetricaLiderCampana::query()
            ->where('lider_id', $user->id)
            ->latest('id')
            ->value('premio_total');

        if (! $siguienteCierre) {
            return round($saldoCobrarMes + $ultimoPremioLider, 2);
        }

        $liquidacionSiguiente = (float) LiquidacionCierre::query()
            ->where('lider_id', $user->id)
            ->where('cierre_campana_id', $siguienteCierre->id)
            ->value('saldo_a_cobrar');

        return round($saldoCobrarMes + $liquidacionSiguiente + $ultimoPremioLider, 2);
    }

    protected function resolverRangos(User $user, int $saldoPuntosActual): array
    {
        $metricaLider = MetricaLiderCampana::query()
            ->with('rangoLider:id,nombre,revendedoras_minimas')
            ->where('lider_id', $user->id)
            ->latest('id')
            ->first();

        if ($metricaLider && $metricaLider->rangoLider) {
            $revendedorasActivas = (int) ($metricaLider->datos['revendedoras_activas'] ?? 0);
            $siguienteRango = RangoLider::query()
                ->where('revendedoras_minimas', '>', (int) $metricaLider->rangoLider->revendedoras_minimas)
                ->orderBy('revendedoras_minimas')
                ->first();

            return [
                'rango_actual' => $metricaLider->rangoLider->nombre,
                'faltante' => max(($siguienteRango?->revendedoras_minimas ?? $revendedorasActivas) - $revendedorasActivas, 0),
            ];
        }

        $reglaActual = PuntajeRegla::query()
            ->whereNotNull('puntaje_minimo')
            ->where('puntaje_minimo', '<=', $saldoPuntosActual)
            ->orderByDesc('puntaje_minimo')
            ->first();

        $siguienteRegla = PuntajeRegla::query()
            ->whereNotNull('puntaje_minimo')
            ->where('puntaje_minimo', '>', $saldoPuntosActual)
            ->orderBy('puntaje_minimo')
            ->first();

        return [
            'rango_actual' => $reglaActual?->descripcion ?? 'Inicial',
            'faltante' => max(((int) ($siguienteRegla?->puntaje_minimo ?? $saldoPuntosActual)) - $saldoPuntosActual, 0),
        ];
    }

    protected function resolverClasificacionPremios(int $saldoPuntosActual): array
    {
        $regla = PuntajeRegla::query()
            ->whereNotNull('puntaje_minimo')
            ->where('puntaje_minimo', '<=', $saldoPuntosActual)
            ->orderByDesc('puntaje_minimo')
            ->first();

        return [
            'clasifica' => (bool) $regla,
            'regla' => $regla
                ? "Cumple puntaje mínimo de {$regla->puntaje_minimo} puntos ({$regla->puntaje_minimo_descripcion})."
                : 'No cumple aún el puntaje mínimo para premios vigentes.',
        ];
    }
}
