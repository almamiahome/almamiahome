<?php

namespace App\Services;

use App\Models\CierreCampana;
use App\Models\CierreCampanaHistorialEstado;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class CierreCampanaStateMachine
{
    protected const TRANSICIONES_PERMITIDAS = [
        CierreCampana::ESTADO_PLANIFICADO => [CierreCampana::ESTADO_ABIERTO],
        CierreCampana::ESTADO_ABIERTO => [CierreCampana::ESTADO_LIQUIDACION],
        CierreCampana::ESTADO_LIQUIDACION => [CierreCampana::ESTADO_CERRADO],
        CierreCampana::ESTADO_CERRADO => [],
    ];

    public function registrarEstadoInicial(CierreCampana $cierre, Authenticatable $usuario, ?string $motivo = null): CierreCampanaHistorialEstado
    {
        return $this->registrarHistorial(
            cierre: $cierre,
            estadoAnterior: null,
            estadoNuevo: $cierre->estado,
            usuario: $usuario,
            motivo: $motivo ?? 'Registro inicial de campaña',
            datos: [
                'origen' => 'registro_campana',
            ],
        );
    }

    public function transicionar(CierreCampana $cierre, string $estadoNuevo, Authenticatable $usuario, ?string $motivo = null): CierreCampana
    {
        $estadoAnterior = (string) $cierre->estado;

        if ($estadoAnterior === $estadoNuevo) {
            throw new RuntimeException('La campaña ya se encuentra en el estado solicitado.');
        }

        if (! in_array($estadoNuevo, CierreCampana::ESTADOS_VALIDOS, true)) {
            throw new InvalidArgumentException('El estado solicitado no es válido para una campaña.');
        }

        $transiciones = self::TRANSICIONES_PERMITIDAS[$estadoAnterior] ?? [];

        if (! in_array($estadoNuevo, $transiciones, true)) {
            throw new RuntimeException(sprintf(
                'Transición no permitida: de "%s" a "%s".',
                $estadoAnterior,
                $estadoNuevo,
            ));
        }

        $this->validarTransicion($cierre, $estadoNuevo);

        return DB::transaction(function () use ($cierre, $estadoAnterior, $estadoNuevo, $usuario, $motivo) {
            $datosActualizados = array_merge($cierre->datos ?? [], [
                'estado_actualizado_por' => $usuario->id,
                'estado_actualizado_en' => now()->toDateTimeString(),
            ]);

            if ($estadoNuevo === CierreCampana::ESTADO_CERRADO) {
                $datosActualizados = array_merge($datosActualizados, [
                    'cerrada_por' => $usuario->id,
                    'cerrada_en' => now()->toDateTimeString(),
                ]);
            }

            $cierre->update([
                'estado' => $estadoNuevo,
                'datos' => $datosActualizados,
            ]);

            $this->registrarHistorial(
                cierre: $cierre,
                estadoAnterior: $estadoAnterior,
                estadoNuevo: $estadoNuevo,
                usuario: $usuario,
                motivo: $motivo,
                datos: [
                    'origen' => 'state_machine',
                ],
            );

            return $cierre->refresh();
        });
    }

    protected function validarTransicion(CierreCampana $cierre, string $estadoNuevo): void
    {
        if ($estadoNuevo === CierreCampana::ESTADO_LIQUIDACION && ! filled($cierre->fecha_liquidacion)) {
            throw new RuntimeException('No se puede pasar a liquidación sin fecha de liquidación definida.');
        }

        if ($estadoNuevo === CierreCampana::ESTADO_CERRADO && ! filled($cierre->fecha_cierre)) {
            throw new RuntimeException('No se puede cerrar la campaña sin fecha de cierre definida.');
        }
    }

    protected function registrarHistorial(
        CierreCampana $cierre,
        ?string $estadoAnterior,
        string $estadoNuevo,
        Authenticatable $usuario,
        ?string $motivo = null,
        array $datos = []
    ): CierreCampanaHistorialEstado {
        return CierreCampanaHistorialEstado::query()->create([
            'cierre_campana_id' => $cierre->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'usuario_id' => $usuario->id,
            'motivo' => filled($motivo) ? $motivo : 'Cambio de estado operativo',
            'datos' => $datos,
            'fecha_cambio' => now(),
        ]);
    }
}
