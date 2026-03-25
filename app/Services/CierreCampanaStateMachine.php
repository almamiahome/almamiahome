<?php

namespace App\Services;

use App\Models\CierreCampana;
use App\Models\CierreCampanaHistorialEstado;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;

class CierreCampanaStateMachine
{
    /**
     * @var array<string, array<int, string>>
     */
    private const TRANSICIONES_PERMITIDAS = [
        CierreCampana::ESTADO_PLANIFICADO => [
            CierreCampana::ESTADO_ABIERTO,
        ],
        CierreCampana::ESTADO_ABIERTO => [
            CierreCampana::ESTADO_LIQUIDACION,
        ],
        CierreCampana::ESTADO_LIQUIDACION => [
            CierreCampana::ESTADO_CERRADO,
        ],
        CierreCampana::ESTADO_CERRADO => [],
    ];

    public function registrarEstadoInicial(CierreCampana $cierre, Authenticatable $usuario, ?string $motivo = null): void
    {
        $this->registrarHistorial(
            cierre: $cierre,
            usuario: $usuario,
            estadoAnterior: null,
            estadoNuevo: $cierre->estado,
            motivo: $motivo ?? 'Alta inicial de campaña.'
        );
    }

    public function transicionar(
        CierreCampana $cierre,
        string $estadoNuevo,
        Authenticatable $usuario,
        ?string $motivo = null,
        array $datosAdicionales = []
    ): CierreCampana {
        $estadoAnterior = (string) $cierre->estado;

        $this->validarTransicion($cierre, $estadoNuevo);

        $cierre->update([
            'estado' => $estadoNuevo,
            'datos' => array_merge($cierre->datos ?? [], $datosAdicionales),
        ]);

        $this->registrarHistorial($cierre->refresh(), $usuario, $estadoAnterior, $estadoNuevo, $motivo);

        return $cierre->refresh();
    }

    private function validarTransicion(CierreCampana $cierre, string $estadoNuevo): void
    {
        if (! in_array($estadoNuevo, CierreCampana::ESTADOS_VALIDOS, true)) {
            throw ValidationException::withMessages([
                'estado' => 'El estado destino no es válido para la campaña.',
            ]);
        }

        if ($cierre->estado === $estadoNuevo) {
            throw ValidationException::withMessages([
                'estado' => 'La campaña ya se encuentra en el estado solicitado.',
            ]);
        }

        $permitidas = self::TRANSICIONES_PERMITIDAS[$cierre->estado] ?? [];

        if (! in_array($estadoNuevo, $permitidas, true)) {
            throw ValidationException::withMessages([
                'estado' => sprintf('No se permite pasar de "%s" a "%s".', $cierre->estado, $estadoNuevo),
            ]);
        }

        if ($estadoNuevo === CierreCampana::ESTADO_ABIERTO && (! $cierre->fecha_inicio || ! $cierre->fecha_cierre)) {
            throw ValidationException::withMessages([
                'estado' => 'Para abrir la campaña debe definir fecha de inicio y fecha de cierre.',
            ]);
        }

        if ($estadoNuevo === CierreCampana::ESTADO_LIQUIDACION && ! $cierre->fecha_liquidacion) {
            throw ValidationException::withMessages([
                'estado' => 'Para pasar a liquidación debe definir la fecha de liquidación.',
            ]);
        }

        if ($estadoNuevo === CierreCampana::ESTADO_CERRADO && ! $cierre->metricas()->exists()) {
            throw ValidationException::withMessages([
                'estado' => 'No se puede cerrar la campaña sin métricas registradas por líder.',
            ]);
        }
    }

    private function registrarHistorial(
        CierreCampana $cierre,
        Authenticatable $usuario,
        ?string $estadoAnterior,
        string $estadoNuevo,
        ?string $motivo
    ): void {
        CierreCampanaHistorialEstado::query()->create([
            'cierre_campana_id' => $cierre->id,
            'usuario_id' => $usuario->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'motivo' => $motivo,
            'datos' => [
                'origen' => 'state_machine',
            ],
            'fecha_cambio' => now(),
        ]);
    }
}
