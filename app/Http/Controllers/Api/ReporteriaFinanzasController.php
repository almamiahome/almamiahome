<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CierreCampana;
use App\Models\LiquidacionCierre;
use App\Services\LiquidacionCierreService;
use App\Services\ReporteriaFinancieraService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReporteriaFinanzasController extends Controller
{
    public function __construct(
        protected ReporteriaFinancieraService $reporteria,
        protected LiquidacionCierreService $liquidaciones,
    ) {
    }

    public function resumenLideres(Request $request)
    {
        return response()->json($this->reporteria->resumenPorLider($request->all()));
    }

    public function resumenCoordinadoras(Request $request)
    {
        return response()->json($this->reporteria->resumenPorCoordinadora($request->all()));
    }

    public function resumenCierres(Request $request)
    {
        return response()->json($this->reporteria->resumenPorCierre($request->all()));
    }

    public function timelineIndividual(Request $request, int $liderId)
    {
        return response()->json($this->reporteria->timelineIndividual($liderId, $request->integer('cierre_id')));
    }

    public function exportarComparativa(Request $request)
    {
        $columnas = ['lider_id', 'coordinadora_id', 'actividad_total', 'premios_total', 'deuda_total', 'balance_total'];
        $datos = $this->reporteria->resumenPorLider($request->all());
        $formato = strtolower((string) $request->input('formato', 'csv'));

        if ($formato === 'xlsx') {
            return response($this->reporteria->exportarXlsxCompat($datos, $columnas), Response::HTTP_OK, [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="reporte-comparativo.xls"',
            ]);
        }

        return response($this->reporteria->exportarCsv($datos, $columnas), Response::HTTP_OK, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reporte-comparativo.csv"',
        ]);
    }

    public function aplicarDescuentos(CierreCampana $cierre)
    {
        $procesados = $this->liquidaciones->aplicarDescuentosFuturosAlCierre($cierre);

        return response()->json([
            'cierre_id' => $cierre->id,
            'descuentos_aplicados' => $procesados,
        ]);
    }

    public function liquidacionDetalle(LiquidacionCierre $liquidacion)
    {
        return response()->json($liquidacion->load(['lider:id,name', 'coordinadora:id,name', 'cierreCampana:id,nombre,codigo']));
    }
}
