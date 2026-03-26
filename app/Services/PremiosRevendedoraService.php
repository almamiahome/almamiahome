<?php

namespace App\Services;

use App\Models\CanjePremio;
use App\Models\Catalogo;
use App\Models\CierreCampana;
use App\Models\RevendedoraPunto;
use App\Models\RevendedoraRacha;
use App\Models\TiendaPremio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PremiosRevendedoraService
{
    public function procesarRacha(User $revendedora, CierreCampana $cierre, bool $pedidoConfirmado, array $contexto = []): RevendedoraRacha
    {
        $catalogoId = $cierre->catalogo_id;

        $rachaAnterior = RevendedoraRacha::query()
            ->where('user_id', $revendedora->id)
            ->where('catalogo_id', $catalogoId)
            ->whereHas('cierre', fn ($query) => $query->where('numero_cierre', '<', $cierre->numero_cierre))
            ->orderByDesc('cierre_id')
            ->first();

        $rachaActual = $pedidoConfirmado ? (($rachaAnterior?->racha_actual ?? 0) + 1) : 0;
        $mejorRacha = max((int) ($rachaAnterior?->mejor_racha ?? 0), $rachaActual);
        $estado = ! $pedidoConfirmado ? 'reiniciada' : ($rachaActual >= 3 ? 'premiada' : 'activa');

        return RevendedoraRacha::query()->updateOrCreate(
            [
                'user_id' => $revendedora->id,
                'catalogo_id' => $catalogoId,
                'cierre_id' => $cierre->id,
            ],
            [
                'estado' => $estado,
                'racha_actual' => $rachaActual,
                'mejor_racha' => $mejorRacha,
                'fecha_inicio' => $rachaAnterior?->fecha_inicio ?? $cierre->fecha_inicio,
                'fecha_ultimo_movimiento' => $cierre->fecha_cierre,
                'origen' => 'servicio',
                'motivo' => $pedidoConfirmado ? 'Cierre con pedido confirmado' : 'Cierre sin pedido confirmado',
                'saldo_posterior' => $rachaActual,
                'fecha_entrega' => $estado === 'premiada' ? now() : null,
                'datos' => [
                    'idempotencia' => [
                        'catalogo_id' => $catalogoId,
                        'cierre_id' => $cierre->id,
                    ],
                    'contexto' => $contexto,
                ],
            ],
        );
    }

    public function registrarMovimientoPuntos(
        User $revendedora,
        CierreCampana $cierre,
        int $puntos,
        string $estado,
        string $origen,
        ?string $motivo = null,
        ?string $idempotenciaClave = null,
        array $datos = []
    ): RevendedoraPunto {
        if ($idempotenciaClave) {
            $movimientoExistente = RevendedoraPunto::query()
                ->where('user_id', $revendedora->id)
                ->where('catalogo_id', $cierre->catalogo_id)
                ->where('cierre_id', $cierre->id)
                ->where('datos->idempotencia_clave', $idempotenciaClave)
                ->first();

            if ($movimientoExistente) {
                return $movimientoExistente;
            }
        }

        $saldoActual = $this->saldoPuntos($revendedora, Catalogo::find($cierre->catalogo_id));

        $movimiento = RevendedoraPunto::query()->create([
            'user_id' => $revendedora->id,
            'catalogo_id' => $cierre->catalogo_id,
            'cierre_id' => $cierre->id,
            'estado' => $estado,
            'puntos' => $puntos,
            'origen' => $origen,
            'motivo' => $motivo,
            'saldo_posterior' => $saldoActual + $puntos,
            'datos' => array_merge($datos, [
                'idempotencia_clave' => $idempotenciaClave,
            ]),
        ]);

        app(BilleteraService::class)->sincronizarDesdeMovimientoPuntos($movimiento);

        return $movimiento;
    }

    public function saldoPuntos(User $revendedora, ?Catalogo $catalogo = null): int
    {
        return (int) RevendedoraPunto::query()
            ->where('user_id', $revendedora->id)
            ->when($catalogo, fn ($query) => $query->where('catalogo_id', $catalogo->id))
            ->sum('puntos');
    }

    public function validarCanje(User $revendedora, TiendaPremio $premio, CierreCampana $cierre): array
    {
        $saldo = $this->saldoPuntos($revendedora, $cierre->catalogo);

        return [
            'valido' => $saldo >= $premio->puntos_requeridos && $premio->stock > 0,
            'saldo' => $saldo,
            'stock' => $premio->stock,
            'errores' => array_values(array_filter([
                $saldo < $premio->puntos_requeridos ? 'Saldo insuficiente' : null,
                $premio->stock <= 0 ? 'Sin stock disponible' : null,
            ])),
        ];
    }

    public function ejecutarCanje(User $revendedora, TiendaPremio $premio, CierreCampana $cierre, array $datosCanje = []): CanjePremio
    {
        return DB::transaction(function () use ($revendedora, $premio, $cierre, $datosCanje) {
            $premio = TiendaPremio::query()->lockForUpdate()->findOrFail($premio->id);
            $validacion = $this->validarCanje($revendedora, $premio, $cierre);

            if (! $validacion['valido']) {
                throw new InvalidArgumentException(implode('; ', $validacion['errores']));
            }

            $premio->decrement('stock');

            $this->registrarMovimientoPuntos(
                revendedora: $revendedora,
                cierre: $cierre,
                puntos: -$premio->puntos_requeridos,
                estado: 'canjeado',
                origen: 'canje',
                motivo: 'Canje aprobado desde tienda de premios',
                idempotenciaClave: sprintf('canje:%d:%d:%d', $revendedora->id, $premio->id, $cierre->id),
                datos: ['tienda_premio_id' => $premio->id],
            );

            $saldo = $this->saldoPuntos($revendedora, $cierre->catalogo);

            return CanjePremio::query()->create([
                'user_id' => $revendedora->id,
                'tienda_premio_id' => $premio->id,
                'catalogo_id' => $cierre->catalogo_id,
                'cierre_id' => $cierre->id,
                'estado' => 'aprobado',
                'puntos_canjeados' => $premio->puntos_requeridos,
                'saldo_posterior' => $saldo,
                'origen' => 'servicio',
                'motivo' => 'Canje aprobado con validación de saldo y stock',
                'fecha_canje' => now(),
                'datos' => array_merge([
                    'tienda_premio_id' => $premio->id,
                    'catalogo_id' => $cierre->catalogo_id,
                    'cierre_id' => $cierre->id,
                ], $datosCanje),
            ]);
        });
    }
}
