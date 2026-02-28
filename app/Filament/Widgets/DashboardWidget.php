<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardWidget extends Widget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public string $filtro = 'mes';

    public ?string $mes = null;

    public ?string $fechaInicio = null;

    public ?string $fechaFin = null;

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.dashboard-widget';

    public function mount(): void
    {
        $this->mes ??= now()->format('Y-m');
    }

    public function render(): View
    {
        $queryBase = $this->pedidosFiltrados();

        $resumen = [
            'unidades_vendidas' => (int) (clone $queryBase)->sum('cantidad_unidades'),
            'total_catalogo_vendido' => (float) (clone $queryBase)->sum('total_precio_catalogo'),
            'total_facturado' => (float) (clone $queryBase)->sum('total_a_pagar'),
        ];

        $ultimosPedidos = (clone $queryBase)
            ->with(['vendedora:id,name', 'lider:id,name'])
            ->latest('fecha')
            ->latest('created_at')
            ->limit(10)
            ->get();

        $ultimosRegistros = User::query()
            ->latest('created_at')
            ->limit(10)
            ->get(['id', 'name', 'email', 'created_at']);

        $topVendedoras = $this->topPorRol('vendedora', $queryBase, 'vendedora_id');
        $topLideres = $this->topPorRol('lider', $queryBase, 'lider_id');

        return view($this->view, [
            'resumen' => $resumen,
            'ultimosPedidos' => $ultimosPedidos,
            'ultimosRegistros' => $ultimosRegistros,
            'topVendedoras' => $topVendedoras,
            'topLideres' => $topLideres,
        ]);
    }

    private function pedidosFiltrados(): Builder
    {
        $query = Pedido::query();

        if ($this->filtro === 'rango') {
            if ($this->fechaInicio) {
                $query->whereDate('fecha', '>=', $this->fechaInicio);
            }

            if ($this->fechaFin) {
                $query->whereDate('fecha', '<=', $this->fechaFin);
            }

            return $query;
        }

        if (blank($this->mes)) {
            return $query;
        }

        if (! preg_match('/^\\d{4}-\\d{2}$/', (string) $this->mes)) {
            return $query;
        }

        $fechaMes = Carbon::createFromFormat('Y-m', $this->mes)->startOfMonth();

        return $query->whereBetween('fecha', [$fechaMes->toDateString(), $fechaMes->copy()->endOfMonth()->toDateString()]);
    }

    private function topPorRol(string $rol, Builder $queryBase, string $columnaUsuario): Collection
    {
        return User::query()
            ->select('users.id', 'users.name')
            ->selectSub(
                (clone $queryBase)
                    ->selectRaw('COALESCE(SUM(total_a_pagar), 0)')
                    ->whereColumn("pedidos.{$columnaUsuario}", 'users.id'),
                'total_ventas'
            )
            ->role($rol)
            ->orderByDesc('total_ventas')
            ->limit(5)
            ->get()
            ->map(fn (User $usuario) => [
                'nombre' => $usuario->name,
                'total_ventas' => (float) ($usuario->total_ventas ?? 0),
            ]);
    }

    public function limpiarRango(): void
    {
        $this->fechaInicio = null;
        $this->fechaFin = null;
    }
}
