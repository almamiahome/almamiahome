<?php

namespace App\Services;

use App\Models\CierreCampana;
use App\Models\LiquidacionCierre;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ReporteriaFinancieraService
{
    public function resumenPorLider(array $filtros = []): Collection
    {
        return $this->baseQuery($filtros)
            ->selectRaw('lider_id, MAX(coordinadora_id) as coordinadora_id')
            ->selectRaw('SUM(saldo_a_cobrar) as actividad_total')
            ->selectRaw('SUM(saldo_a_pagar) as premios_total')
            ->selectRaw('SUM(deuda_arrastrada) as deuda_total')
            ->selectRaw('SUM(balance_neto) as balance_total')
            ->groupBy('lider_id')
            ->get();
    }

    public function resumenPorCoordinadora(array $filtros = []): Collection
    {
        return $this->baseQuery($filtros)
            ->selectRaw('coordinadora_id')
            ->selectRaw('SUM(saldo_a_cobrar) as actividad_total')
            ->selectRaw('SUM(saldo_a_pagar) as premios_total')
            ->selectRaw('SUM(deuda_arrastrada) as deuda_total')
            ->selectRaw('SUM(balance_neto) as balance_total')
            ->groupBy('coordinadora_id')
            ->get();
    }

    public function resumenPorCierre(array $filtros = []): Collection
    {
        return $this->baseQuery($filtros)
            ->selectRaw('cierre_campana_id')
            ->selectRaw('SUM(saldo_a_cobrar) as actividad_total')
            ->selectRaw('SUM(saldo_a_pagar) as premios_total')
            ->selectRaw('SUM(deuda_arrastrada) as deuda_total')
            ->selectRaw('SUM(balance_neto) as balance_total')
            ->groupBy('cierre_campana_id')
            ->get();
    }

    public function timelineIndividual(int $liderId, ?int $cierreId = null): Collection
    {
        $query = LiquidacionCierre::query()
            ->with('cierreCampana:id,nombre,codigo,numero_cierre')
            ->where('lider_id', $liderId)
            ->orderBy('cierre_campana_id');

        if ($cierreId) {
            $query->where('cierre_campana_id', $cierreId);
        }

        return $query->get();
    }

    protected function baseQuery(array $filtros): Builder
    {
        $query = LiquidacionCierre::query();

        if (! empty($filtros['cierre_id'])) {
            $query->where('cierre_campana_id', (int) $filtros['cierre_id']);
        }

        if (! empty($filtros['estado'])) {
            $query->where('estado', (string) $filtros['estado']);
        }

        if (! empty($filtros['zona_id'])) {
            $query->whereHas('lider', fn (Builder $q) => $q->where('zona_id', (int) $filtros['zona_id']));
        }

        if (! empty($filtros['departamento_id'])) {
            $query->whereHas('lider', fn (Builder $q) => $q->where('departamento_id', (int) $filtros['departamento_id']));
        }

        if (! empty($filtros['catalogo_id'])) {
            $query->whereHas('cierreCampana', fn (Builder $q) => $q->where('catalogo_id', (int) $filtros['catalogo_id']));
        }

        return $query;
    }

    public function exportarCsv(Collection $filas, array $columnas): string
    {
        $stream = fopen('php://temp', 'r+');
        fputcsv($stream, $columnas);

        foreach ($filas as $fila) {
            $registro = [];
            foreach ($columnas as $columna) {
                $registro[] = data_get($fila, $columna);
            }
            fputcsv($stream, $registro);
        }

        rewind($stream);
        return (string) stream_get_contents($stream);
    }

    public function exportarXlsxCompat(Collection $filas, array $columnas): string
    {
        $xml = '<?xml version="1.0"?><Workbook><Worksheet name="reportes"><Table>';
        $xml .= '<Row>';
        foreach ($columnas as $columna) {
            $xml .= '<Cell><Data>'.htmlspecialchars($columna).'</Data></Cell>';
        }
        $xml .= '</Row>';

        foreach ($filas as $fila) {
            $xml .= '<Row>';
            foreach ($columnas as $columna) {
                $xml .= '<Cell><Data>'.htmlspecialchars((string) data_get($fila, $columna, '')).'</Data></Cell>';
            }
            $xml .= '</Row>';
        }

        return $xml.'</Table></Worksheet></Workbook>';
    }
}
