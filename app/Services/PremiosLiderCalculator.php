<?php

namespace App\Services;

use App\Models\CierreCampana;
use App\Models\LiderAltaCuota;
use App\Models\LiderSaltoRangoHistorial;
use App\Models\MetricaLiderCampana;
use App\Models\PremioRegla;
use App\Models\RangoLider;
use App\Models\RepartoCompra;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class PremiosLiderCalculator
{
    public const VERSION_CALCULO = 'v3_modular_etapa_3';

    public function calcular(CierreCampana $campana, User $lider, array $datos): MetricaLiderCampana
    {
        $rango = $this->resolverRango($datos);
        $actividad = $this->moduloActividad($rango, $datos);
        $altas = $this->moduloAltas($campana, $datos);
        $cobranzas = $this->moduloCobranzas($campana, $rango, $datos);
        $crecimiento = $this->moduloCrecimiento($campana, $rango, $datos);
        $retencion = $this->moduloRetencion($campana, $rango, $datos);
        $reparto = $this->moduloReparto($datos);
        $plusUnidades = $this->moduloPlusUnidades($campana, $rango, $datos);

        $premioTotal = $actividad['premio_actividad'] + $altas['premio_altas'] + $cobranzas['premio_cobranzas']
            + $crecimiento['premio_crecimiento'] + $retencion['premio_retencion'] + $reparto['monto_reparto_total']
            + $plusUnidades['premio_plus_crecimiento'] + $plusUnidades['premio_unidades'];

        $payload = [
            'actividad_ok' => $actividad['actividad_ok'],
            'altas_ok' => $altas['altas_ok'],
            'unidades_ok' => $plusUnidades['unidades_ok'],
            'cobranzas_ok' => $cobranzas['cobranzas_ok'],
            'crecimiento_ok' => $crecimiento['crecimiento_ok'],
            'retencion_ok' => $retencion['retencion_ok'],
            'plus_crecimiento_ok' => $plusUnidades['plus_crecimiento_ok'],
            'altas_pagadas_en_cierre' => $altas['altas_pagadas_en_cierre'],
            'cantidad_1c' => $reparto['cantidad_1c'],
            'cantidad_2c' => $reparto['cantidad_2c'],
            'cantidad_3c' => $reparto['cantidad_3c'],
            'monto_reparto_total' => $reparto['monto_reparto_total'],
            'premio_actividad' => $actividad['premio_actividad'],
            'premio_unidades' => $plusUnidades['premio_unidades'],
            'premio_cobranzas' => $cobranzas['premio_cobranzas'],
            'premio_altas' => $altas['premio_altas'],
            'premio_crecimiento' => $crecimiento['premio_crecimiento'],
            'premio_retencion' => $retencion['premio_retencion'],
            'premio_plus_crecimiento' => $plusUnidades['premio_plus_crecimiento'],
            'premio_total' => $premioTotal,
            'fecha_pago_equipo' => $cobranzas['fecha_pago_equipo'],
            'objetivo_proximo_cierre' => $plusUnidades['objetivo_proximo_cierre'],
            'actividad_cierre_anterior' => $retencion['actividad_cierre_anterior'],
            'datos' => [
                'revendedoras_activas' => $actividad['revendedoras_activas'],
                'unidades' => $plusUnidades['unidades'],
                'altas_mes' => $altas['altas_mes'],
                'rango_nombre' => $rango?->nombre,
                'rango_id' => $rango?->id,
                'cierre_codigo' => $campana->codigo,
                'cierre_nombre' => $campana->nombre,
                'objetivo_proximo_cierre' => $plusUnidades['objetivo_proximo_cierre'],
                'actividad_cierre_anterior' => $retencion['actividad_cierre_anterior'],
                'evidencia' => [
                    'version_calculo' => self::VERSION_CALCULO,
                    'reglas_aplicadas' => [
                        ['codigo' => 'actividad', 'cumple' => $actividad['actividad_ok'], 'premio' => $actividad['premio_actividad']],
                        ['codigo' => 'retencion', 'cumple' => $retencion['retencion_ok'], 'premio' => $retencion['premio_retencion']],
                        ['codigo' => 'altas', 'cumple' => $altas['altas_ok'], 'premio' => $altas['premio_altas']],
                        ['codigo' => 'cobranzas', 'cumple' => $cobranzas['cobranzas_ok'], 'premio' => $cobranzas['premio_cobranzas']],
                        ['codigo' => 'crecimiento', 'cumple' => $crecimiento['crecimiento_ok'], 'premio' => $crecimiento['premio_crecimiento']],
                        ['codigo' => 'reparto', 'cumple' => $reparto['monto_reparto_total'] > 0, 'premio' => $reparto['monto_reparto_total']],
                        ['codigo' => 'plus_crecimiento', 'cumple' => $plusUnidades['plus_crecimiento_ok'], 'premio' => $plusUnidades['premio_plus_crecimiento']],
                        ['codigo' => 'unidades', 'cumple' => $plusUnidades['unidades_ok'], 'premio' => $plusUnidades['premio_unidades']],
                    ],
                ],
            ],
        ];

        $metrica = MetricaLiderCampana::updateOrCreate([
            'lider_id' => $lider->id,
            'cierre_campana_id' => $campana->id,
            'rango_lider_id' => $rango?->id,
        ], $payload);

        $this->persistirCuotasAltas($metrica, $lider, $campana, $altas['altas_pagadas_en_cierre']);
        $this->persistirHistorialSaltoRango($lider, $campana, $crecimiento);

        return $metrica->refresh();
    }

    protected function moduloActividad(?RangoLider $rango, array $datos): array
    {
        $revendedorasActivas = (int) ($datos['revendedoras_activas'] ?? 0);
        $actividadOk = $rango ? $this->validarActividad($rango, $revendedorasActivas) : false;

        return [
            'revendedoras_activas' => $revendedorasActivas,
            'actividad_ok' => $actividadOk,
            'premio_actividad' => $actividadOk && $rango ? (float) $rango->premio_actividad : 0.0,
        ];
    }

    protected function moduloRetencion(CierreCampana $campana, ?RangoLider $rango, array $datos): array
    {
        $actividadAnterior = $this->resolverEnteroNullable($datos['actividad_cierre_anterior'] ?? null);
        $actividadActual = (int) ($datos['revendedoras_activas'] ?? 0);
        $retencionLograda = (bool) ($datos['retencion_lograda'] ?? false);

        $retencionOk = $retencionLograda;
        if ($actividadAnterior !== null) {
            $retencionOk = $actividadActual >= $actividadAnterior;
        }

        return [
            'retencion_ok' => $retencionOk,
            'actividad_cierre_anterior' => $actividadAnterior,
            'premio_retencion' => $retencionOk ? $this->resolverPremioPorTipo($rango, $campana, 'retencion') : 0.0,
        ];
    }

    protected function moduloAltas(CierreCampana $campana, array $datos): array
    {
        $altas = (int) ($datos['altas'] ?? 0);
        $altasOk = $altas >= 3;
        $altasPagadas = $this->resolverAltasPagadas($datos, $campana, $altas);

        return [
            'altas_mes' => $altas,
            'altas_ok' => $altasOk,
            'altas_pagadas_en_cierre' => $altasPagadas,
            'premio_altas' => $altasOk ? $this->calcularPremioAltas($altasPagadas, $altas) : 0.0,
        ];
    }

    protected function moduloCobranzas(CierreCampana $campana, ?RangoLider $rango, array $datos): array
    {
        $fechaPagoEquipo = $this->resolverFechaPago($datos['fecha_pago_equipo'] ?? null);
        $cobranzasOk = $this->validarCobranzas($campana, $fechaPagoEquipo);

        return [
            'cobranzas_ok' => $cobranzasOk,
            'fecha_pago_equipo' => $fechaPagoEquipo,
            'premio_cobranzas' => $cobranzasOk && $rango ? (float) $rango->premio_cobranzas : 0.0,
        ];
    }

    protected function moduloCrecimiento(CierreCampana $campana, ?RangoLider $rango, array $datos): array
    {
        $crecimientoOk = (bool) ($datos['crecimiento_logrado'] ?? false);

        return [
            'crecimiento_ok' => $crecimientoOk,
            'rango_anterior_id' => $datos['rango_anterior_id'] ?? null,
            'rango_nuevo_id' => $rango?->id,
            'motivo' => $crecimientoOk ? 'Salto de rango validado' : 'Sin salto de rango válido',
            'premio_crecimiento' => $crecimientoOk ? $this->resolverPremioCrecimiento($rango, $campana) : 0.0,
        ];
    }

    protected function moduloReparto(array $datos): array
    {
        $cantidad1c = (int) ($datos['cantidad_1c'] ?? 0);
        $cantidad2c = (int) ($datos['cantidad_2c'] ?? 0);
        $cantidad3c = (int) ($datos['cantidad_3c'] ?? 0);

        return [
            'cantidad_1c' => $cantidad1c,
            'cantidad_2c' => $cantidad2c,
            'cantidad_3c' => $cantidad3c,
            'monto_reparto_total' => $this->calcularRepartoTotal($cantidad1c, $cantidad2c, $cantidad3c),
        ];
    }

    protected function moduloPlusUnidades(CierreCampana $campana, ?RangoLider $rango, array $datos): array
    {
        $unidades = (int) ($datos['unidades'] ?? 0);
        $objetivoProximoCierre = $this->resolverEnteroNullable($datos['objetivo_proximo_cierre'] ?? null);
        $plusLogrado = (bool) ($datos['plus_crecimiento_logrado'] ?? false);
        $plusCrecimientoOk = $plusLogrado || ($objetivoProximoCierre !== null && $unidades >= $objetivoProximoCierre);
        $unidadesOk = $rango ? $unidades >= $rango->unidades_minimas : false;

        return [
            'unidades' => $unidades,
            'unidades_ok' => $unidadesOk,
            'plus_crecimiento_ok' => $plusCrecimientoOk,
            'objetivo_proximo_cierre' => $objetivoProximoCierre,
            'premio_plus_crecimiento' => $plusCrecimientoOk ? $this->resolverPremioPorTipo($rango, $campana, 'plus_crecimiento') : 0.0,
            'premio_unidades' => $unidadesOk && $rango ? (float) $rango->premio_unidades : 0.0,
        ];
    }

    protected function persistirCuotasAltas(MetricaLiderCampana $metrica, User $lider, CierreCampana $campana, array $altasPagadas): void
    {
        foreach ($altasPagadas as $detalle) {
            LiderAltaCuota::query()->updateOrCreate([
                'metrica_lider_campana_id' => $metrica->id,
                'numero_cuota' => (int) ($detalle['cuota'] ?? 0),
            ], [
                'lider_id' => $lider->id,
                'cierre_campana_id' => $campana->id,
                'altas_reportadas' => (int) ($detalle['altas'] ?? 0),
                'monto_pagado' => (float) ($detalle['monto_pagado'] ?? 0),
                'estado' => 'liquidado',
                'datos' => [
                    'cierre_codigo' => $detalle['cierre_codigo'] ?? $campana->codigo,
                ],
            ]);
        }
    }

    protected function persistirHistorialSaltoRango(User $lider, CierreCampana $campana, array $crecimiento): void
    {
        if (! $crecimiento['crecimiento_ok']) {
            return;
        }

        LiderSaltoRangoHistorial::query()->updateOrCreate([
            'lider_id' => $lider->id,
            'cierre_campana_id' => $campana->id,
        ], [
            'rango_anterior_id' => $crecimiento['rango_anterior_id'],
            'rango_nuevo_id' => $crecimiento['rango_nuevo_id'],
            'estado' => 'registrado',
            'motivo' => $crecimiento['motivo'],
            'datos' => [
                'origen' => 'PremiosLiderCalculator',
            ],
        ]);
    }

    protected function resolverRango(array $datos): ?RangoLider
    {
        if ($datos['rango'] ?? false) {
            return $datos['rango'] instanceof RangoLider
                ? $datos['rango']
                : RangoLider::find($datos['rango']);
        }

        if ($datos['rango_lider_id'] ?? false) {
            return RangoLider::find($datos['rango_lider_id']);
        }

        $revendedorasActivas = (int) ($datos['revendedoras_activas'] ?? 0);

        return RangoLider::query()
            ->where('revendedoras_minimas', '<=', $revendedorasActivas)
            ->where('revendedoras_maximas', '>=', $revendedorasActivas)
            ->orderBy('revendedoras_minimas')
            ->first();
    }

    protected function validarActividad(RangoLider $rango, int $revendedoras): bool
    {
        return $revendedoras >= $rango->revendedoras_minimas
            && $revendedoras <= $rango->revendedoras_maximas;
    }

    protected function validarCobranzas(CierreCampana $campana, ?Carbon $fechaPago): bool
    {
        if (! $fechaPago) {
            return false;
        }

        $fechaCorte = $campana->fecha_cierre ? Carbon::parse($campana->fecha_cierre) : now();

        return $fechaPago->diffInDays($fechaCorte) <= 7;
    }

    protected function resolverFechaPago(null|string|Carbon $valor): ?Carbon
    {
        if (! $valor) {
            return null;
        }

        return $valor instanceof Carbon ? $valor : Carbon::parse($valor);
    }

    protected function resolverEnteroNullable(mixed $valor): ?int
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        return (int) $valor;
    }

    protected function resolverAltasPagadas(array $datos, CierreCampana $campana, int $altas): array
    {
        $pagos = Arr::wrap($datos['altas_pagadas_en_cierre'] ?? []);

        if (! empty($pagos)) {
            return array_values($pagos);
        }

        if ($altas < 1) {
            return [];
        }

        $montoTotal = $altas * 2200;
        $cuota = round($montoTotal / 3, 2);

        return collect(range(1, 3))->map(function (int $numero) use ($altas, $campana, $cuota) {
            return [
                'cierre_codigo' => $campana->codigo . ($numero === 1 ? '' : ' +' . ($numero - 1)),
                'altas' => $altas,
                'cuota' => $numero,
                'monto_pagado' => $cuota,
            ];
        })->all();
    }

    protected function calcularPremioAltas(array $pagos, int $altasMes): float
    {
        if (! empty($pagos)) {
            return (float) collect($pagos)->sum(fn (array $detalle) => (float) ($detalle['monto_pagado'] ?? 0));
        }

        return $altasMes * 2200;
    }

    protected function calcularRepartoTotal(int $cantidad1c, int $cantidad2c, int $cantidad3c): float
    {
        $monto1c = $this->montoRepartoPorTipo('1C', 500);
        $monto2c = $this->montoRepartoPorTipo('2C', 700);
        $monto3c = $this->montoRepartoPorTipo('3C', 1000);

        return ($cantidad1c * $monto1c) + ($cantidad2c * $monto2c) + ($cantidad3c * $monto3c);
    }

    protected function montoRepartoPorTipo(string $tipo, float $fallback): float
    {
        return (float) (RepartoCompra::query()
            ->where('tipo_compra', $tipo)
            ->value('monto_por_revendedora') ?? $fallback);
    }

    protected function resolverPremioCrecimiento(?RangoLider $rango, CierreCampana $campana): float
    {
        return $this->resolverPremioPorTipo($rango, $campana, 'crecimiento');
    }

    protected function resolverPremioPorTipo(?RangoLider $rango, CierreCampana $campana, string $tipo): float
    {
        if (! $rango) {
            return 0.0;
        }

        $reglaCampana = PremioRegla::query()
            ->where('rango_lider_id', $rango->id)
            ->where('tipo', $tipo)
            ->where(function ($query) use ($campana) {
                $query->whereNull('campana_id')
                    ->orWhere('campana_id', $campana->id);
            })
            ->orderByDesc('campana_id')
            ->first();

        return $reglaCampana?->monto ?? 0.0;
    }
}
