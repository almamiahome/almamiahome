<?php

namespace App\Http\Controllers\Crecimiento;

use App\Http\Controllers\Controller;
use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\User;
use App\Services\CierreCampanaStateMachine;
use App\Services\PremiosLiderCalculator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class CierreCampanaController extends Controller
{
    public function __construct(private readonly CierreCampanaStateMachine $stateMachine)
    {
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

        $this->stateMachine->registrarEstadoInicial(
            cierre: $cierre,
            usuario: $usuario,
            motivo: 'Campaña registrada desde cierre general.'
        );

        return $cierre;
    }

    public function cerrarCampana(CierreCampana $cierre, Authenticatable $usuario): CierreCampana
    {
        if (! $usuario->can('crecimiento.cerrar_campana')) {
            abort(403, 'No tiene permiso para cerrar campañas.');
        }

        return $this->stateMachine->transicionar(
            cierre: $cierre,
            estadoNuevo: CierreCampana::ESTADO_CERRADO,
            usuario: $usuario,
            motivo: 'Cierre ejecutado desde cierre general.',
            datosAdicionales: [
                'cerrada_por' => $usuario->id,
                'cerrada_en' => now()->toDateTimeString(),
            ]
        );
    }
}
