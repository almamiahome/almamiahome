<?php

namespace App\Services;

use App\Models\CierreCampana;
use App\Models\MetricaLiderCampana;
use App\Models\PremioRegla;
use App\Models\RangoLider;
use App\Models\RepartoCompra;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class PremiosLiderCalculator
{
    public const VERSION_CALCULO = 'v2_retencion_plus_crecimiento';

    public function calcular(CierreCampana $campana, User $lider, array $datos): MetricaLiderCampana
    {
        $rango = $this->resolverRango($datos);
        $revendedorasActivas = (int) ($datos['revendedoras_activas'] ?? 0);
        $unidades = (int) ($datos['unidades'] ?? 0);
        $fechaPagoEquipo = $this->resolverFechaPago($datos['fecha_pago_equipo'] ?? null);
        $altas = (int) ($datos['altas'] ?? 0);
        $cantidad1c = (int) ($datos['cantidad_1c'] ?? 0);
        $cantidad2c = (int) ($datos['cantidad_2c'] ?? 0);
        $cantidad3c = (int) ($datos['cantidad_3c'] ?? 0);
        $crecimientoLogrado = (bool) ($datos['crecimiento_logrado'] ?? false);
        $retencionLograda = (bool) ($datos['retencion_lograda'] ?? false);
        $plusCrecimientoLogrado = (bool) ($datos['plus_crecimiento_logrado'] ?? false);
        $objetivoProximoCierre = $this->resolverEnteroNullable($datos['objetivo_proximo_cierre'] ?? null);
        $actividadCierreAnterior = $this->resolverEnteroNullable($datos['actividad_cierre_anterior'] ?? null);

        $actividadOk = $rango ? $this->validarActividad($rango, $revendedorasActivas) : false;
        $altasOk = $altas >= 3;
        $unidadesOk = $rango ? $unidades >= $rango->unidades_minimas : false;
        $cobranzasOk = $this->validarCobranzas($campana, $fechaPagoEquipo);
        $crecimientoOk = $crecimientoLogrado;
        $retencionOk = $retencionLograda;
        $plusCrecimientoOk = $plusCrecimientoLogrado;

        $altasPagadas = $this->resolverAltasPagadas($datos, $campana, $altas);
        $montoRepartoTotal = $this->calcularRepartoTotal($cantidad1c, $cantidad2c, $cantidad3c);

        $premioActividad = $actividadOk && $rango ? (float) $rango->premio_actividad : 0.0;
        $premioUnidades = $unidadesOk && $rango ? (float) $rango->premio_unidades : 0.0;
        $premioCobranzas = $cobranzasOk && $rango ? (float) $rango->premio_cobranzas : 0.0;
        $premioAltas = $altasOk ? $this->calcularPremioAltas($altasPagadas, $altas) : 0.0;
        $premioCrecimiento = $crecimientoOk ? $this->resolverPremioCrecimiento($rango, $campana) : 0.0;
        $premioRetencion = $retencionOk ? $this->resolverPremioPorTipo($rango, $campana, 'retencion') : 0.0;
        $premioPlusCrecimiento = $plusCrecimientoOk ? $this->resolverPremioPorTipo($rango, $campana, 'plus_crecimiento') : 0.0;

        $premioTotal = $premioActividad + $premioUnidades + $premioCobranzas
            + $premioAltas + $premioCrecimiento + $premioRetencion + $premioPlusCrecimiento + $montoRepartoTotal;

        $payload = [
            'actividad_ok' => $actividadOk,
            'altas_ok' => $altasOk,
            'unidades_ok' => $unidadesOk,
            'cobranzas_ok' => $cobranzasOk,
            'crecimiento_ok' => $crecimientoOk,
            'retencion_ok' => $retencionOk,
            'plus_crecimiento_ok' => $plusCrecimientoOk,
            'altas_pagadas_en_cierre' => $altasPagadas,
            'cantidad_1c' => $cantidad1c,
            'cantidad_2c' => $cantidad2c,
            'cantidad_3c' => $cantidad3c,
            'monto_reparto_total' => $montoRepartoTotal,
            'premio_actividad' => $premioActividad,
            'premio_unidades' => $premioUnidades,
            'premio_cobranzas' => $premioCobranzas,
            'premio_altas' => $premioAltas,
            'premio_crecimiento' => $premioCrecimiento,
            'premio_retencion' => $premioRetencion,
            'premio_plus_crecimiento' => $premioPlusCrecimiento,
            'premio_total' => $premioTotal,
            'fecha_pago_equipo' => $fechaPagoEquipo,
            'objetivo_proximo_cierre' => $objetivoProximoCierre,
            'actividad_cierre_anterior' => $actividadCierreAnterior,
            'datos' => [
                'revendedoras_activas' => $revendedorasActivas,
                'unidades' => $unidades,
                'altas_mes' => $altas,
                'rango_nombre' => $rango?->nombre,
                'rango_id' => $rango?->id,
                'cierre_codigo' => $campana->codigo,
                'cierre_nombre' => $campana->nombre,
                'objetivo_proximo_cierre' => $objetivoProximoCierre,
                'actividad_cierre_anterior' => $actividadCierreAnterior,
                'evidencia' => [
                    'version_calculo' => self::VERSION_CALCULO,
                    'reglas_aplicadas' => [
                        [
                            'codigo' => 'actividad',
                            'cumple' => $actividadOk,
                            'premio' => $premioActividad,
                        ],
                        [
                            'codigo' => 'unidades',
                            'cumple' => $unidadesOk,
                            'premio' => $premioUnidades,
                        ],
                        [
                            'codigo' => 'cobranzas',
                            'cumple' => $cobranzasOk,
                            'premio' => $premioCobranzas,
                        ],
                        [
                            'codigo' => 'altas',
                            'cumple' => $altasOk,
                            'premio' => $premioAltas,
                        ],
                        [
                            'codigo' => 'crecimiento',
                            'cumple' => $crecimientoOk,
                            'premio' => $premioCrecimiento,
                        ],
                        [
                            'codigo' => 'retencion',
                            'cumple' => $retencionOk,
                            'premio' => $premioRetencion,
                        ],
                        [
                            'codigo' => 'plus_crecimiento',
                            'cumple' => $plusCrecimientoOk,
                            'premio' => $premioPlusCrecimiento,
                        ],
                    ],
                    'insumos' => [
                        'crecimiento_logrado' => $crecimientoLogrado,
                        'retencion_lograda' => $retencionLograda,
                        'plus_crecimiento_logrado' => $plusCrecimientoLogrado,
                        'objetivo_proximo_cierre' => $objetivoProximoCierre,
                        'actividad_cierre_anterior' => $actividadCierreAnterior,
                    ],
                ],
            ],
        ];

        $metrica = MetricaLiderCampana::updateOrCreate([
            'lider_id' => $lider->id,
            'cierre_campana_id' => $campana->id,
            'rango_lider_id' => $rango?->id,
        ], $payload);

        return $metrica->refresh();
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
