<?php

namespace App\Http\Controllers\Crecimiento;

use App\Http\Controllers\Controller;
use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\Pedido;
use App\Models\User;
use App\Services\CierreCampanaStateMachine;
use App\Services\PremiosLiderCalculator;
use App\Services\PremiosRevendedoraService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CierreCampanaController extends Controller
{
    public function __construct(
        protected CierreCampanaStateMachine $stateMachine,
        protected PremiosRevendedoraService $premiosRevendedoraService,
    ) {
    }

    public function totalesPorLider(CierreCampana $cierre, Authenticatable $usuario): Collection
    {
        if (! $usuario->can('crecimiento.ver_metricas_liderazgo')) {
            abort(403, 'No tiene permiso para ver las métricas de liderazgo.');
        }

        $metricas = MetricaLiderCampana::with(['lider', 'rangoLider'])
            ->where('cierre_campana_id', $cierre->id)
            ->get();

        return $metricas->map(function (MetricaLiderCampana $metrica) {
            return [
                'lider' => optional($metrica->lider)->name,
                'rango' => optional($metrica->rangoLider)->nombre,
                'revendedoras_activas' => (int) data_get($metrica->datos, 'revendedoras_activas', 0),
                'actividad_ok' => (bool) $metrica->actividad_ok,
                'unidades' => (int) data_get($metrica->datos, 'unidades', 0),
                'unidades_ok' => (bool) $metrica->unidades_ok,
                'cobranzas_ok' => (bool) $metrica->cobranzas_ok,
                'fecha_pago_equipo' => $metrica->fecha_pago_equipo?->format('Y-m-d'),
                'altas_mes' => (int) data_get($metrica->datos, 'altas_mes', 0),
                'altas_pagadas_en_cierre' => $metrica->altas_pagadas_en_cierre ?? [],
                'cantidad_1c' => (int) $metrica->cantidad_1c,
                'cantidad_2c' => (int) $metrica->cantidad_2c,
                'cantidad_3c' => (int) $metrica->cantidad_3c,
                'monto_reparto_total' => (float) $metrica->monto_reparto_total,
                'premio_actividad' => (float) $metrica->premio_actividad,
                'premio_unidades' => (float) $metrica->premio_unidades,
                'premio_cobranzas' => (float) $metrica->premio_cobranzas,
                'premio_altas' => (float) $metrica->premio_altas,
                'premio_crecimiento' => (float) $metrica->premio_crecimiento,
                'premio_total' => (float) $metrica->premio_total,
            ];
        })->values();
    }

    public function planResumen(CierreCampana $cierre, Authenticatable $usuario): array
    {
        if (! $usuario->can('crecimiento.ver_cierres_campana')) {
            abort(403, 'No tiene permiso para ver el resumen del cierre.');
        }

        $totales = $this->totalesPorLider($cierre, $usuario);

        return [
            'estado' => $cierre->estado,
            'nota' => data_get($cierre->datos, 'nota'),
            'lideres' => $totales->count(),
            'actividad_promedio' => $totales->avg(fn ($fila) => $fila['actividad_ok'] ? 100 : 0),
            'premio_total' => $totales->sum('premio_total'),
            'actualizado_en' => $cierre->updated_at?->format('d/m/Y H:i'),
        ];
    }

    public function registrarMetricas(CierreCampana $cierre, array $metricas, Authenticatable $usuario): Collection
    {
        if (! $usuario->can('crecimiento.cerrar_campana')) {
            abort(403, 'No tiene permiso para registrar métricas de campañas.');
        }

        $servicio = app(PremiosLiderCalculator::class);

        return collect($metricas)->map(function (array $datos) use ($servicio, $cierre) {
            $lider = User::findOrFail($datos['lider_id']);

            return $servicio->calcular($cierre, $lider, $datos);
        });
    }

    public function registrarCampana(array $datos, Authenticatable $usuario): CierreCampana
    {
        if (! $usuario->can('crecimiento.cerrar_campana')) {
            abort(403, 'No tiene permiso para registrar campañas.');
        }

        $cierre = CierreCampana::create([
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'],
            'catalogo_id' => $datos['catalogo_id'] ?? null,
            'numero_cierre' => $datos['numero_cierre'] ?? 1,
            'fecha_inicio' => $datos['fecha_inicio'] ?? null,
            'fecha_cierre' => $datos['fecha_cierre'] ?? null,
            'fecha_liquidacion' => $datos['fecha_liquidacion'] ?? null,
            'estado' => $datos['estado'] ?? CierreCampana::ESTADO_PLANIFICADO,
            'datos' => $datos['datos'] ?? null,
        ]);

        $this->stateMachine->registrarEstadoInicial($cierre, $usuario);

        return $cierre->refresh();
    }

    public function cambiarEstadoCampana(CierreCampana $cierre, string $estadoNuevo, Authenticatable $usuario, ?string $motivo = null): CierreCampana
    {
        if (! $usuario->can('crecimiento.cerrar_campana')) {
            abort(403, 'No tiene permiso para actualizar el estado de campañas.');
        }

        return $this->stateMachine->transicionar($cierre, $estadoNuevo, $usuario, $motivo);
    }

    public function cerrarCampana(CierreCampana $cierre, Authenticatable $usuario, ?string $motivo = null): CierreCampana
    {
        if (! $usuario->can('crecimiento.cerrar_campana')) {
            abort(403, 'No tiene permiso para cerrar campañas.');
        }

        $cierreActualizado = $this->cambiarEstadoCampana(
            cierre: $cierre,
            estadoNuevo: CierreCampana::ESTADO_CERRADO,
            usuario: $usuario,
            motivo: $motivo ?? 'Cierre operativo de campaña',
        );

        $this->sincronizarPremiosRevendedoras($cierreActualizado);

        return $cierreActualizado;
    }

    protected function sincronizarPremiosRevendedoras(CierreCampana $cierre): void
    {
        $pedidosPorRevendedora = Pedido::query()
            ->where('cierre_id', $cierre->id)
            ->whereNotNull('vendedora_id')
            ->select(
                'vendedora_id',
                DB::raw('GROUP_CONCAT(id) as pedidos_ids'),
                DB::raw("SUM(CASE WHEN estado IN ('Confirmado', 'Facturado', 'Entregado') THEN 1 ELSE 0 END) as pedidos_confirmados"),
                DB::raw("SUM(CASE WHEN estado IN ('Confirmado', 'Facturado', 'Entregado') THEN total_puntos ELSE 0 END) as puntos_confirmados")
            )
            ->groupBy('vendedora_id')
            ->get();

        foreach ($pedidosPorRevendedora as $resumen) {
            $revendedora = User::find($resumen->vendedora_id);

            if (! $revendedora) {
                continue;
            }

            $pedidoConfirmado = (int) $resumen->pedidos_confirmados > 0;
            $pedidosIds = collect(explode(',', (string) $resumen->pedidos_ids))
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            $this->premiosRevendedoraService->procesarRacha(
                revendedora: $revendedora,
                cierre: $cierre,
                pedidoConfirmado: $pedidoConfirmado,
                contexto: [
                    'cierre_id' => $cierre->id,
                    'pedido_ids' => $pedidosIds,
                    'regla' => 'cierre_campana_racha',
                ],
            );

            if (! $pedidoConfirmado) {
                continue;
            }

            $puntosContinuidad = (int) data_get($cierre->datos, 'reglas_revendedora.continuidad_puntos', 30);
            $puntosVentas = (int) $resumen->puntos_confirmados;

            $this->premiosRevendedoraService->registrarMovimientoPuntos(
                revendedora: $revendedora,
                cierre: $cierre,
                puntos: $puntosContinuidad,
                estado: 'confirmado',
                origen: 'cierre_campana',
                motivo: 'Bono por continuidad en cierre',
                idempotenciaClave: sprintf('cierre:%d:revendedora:%d:continuidad', $cierre->id, $revendedora->id),
                datos: [
                    'pedido_origen_ids' => $pedidosIds,
                    'cierre_id' => $cierre->id,
                    'regla_aplicada' => 'continuidad',
                ],
            );

            if ($puntosVentas <= 0) {
                continue;
            }

            $this->premiosRevendedoraService->registrarMovimientoPuntos(
                revendedora: $revendedora,
                cierre: $cierre,
                puntos: $puntosVentas,
                estado: 'confirmado',
                origen: 'cierre_campana',
                motivo: 'Puntos por ventas confirmadas del cierre',
                idempotenciaClave: sprintf('cierre:%d:revendedora:%d:ventas', $cierre->id, $revendedora->id),
                datos: [
                    'pedido_origen_ids' => $pedidosIds,
                    'cierre_id' => $cierre->id,
                    'regla_aplicada' => 'ventas_confirmadas',
                ],
            );
        }
    }
}
