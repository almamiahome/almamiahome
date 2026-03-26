<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturas masivas</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        * { box-sizing: border-box; font-family: DejaVu Sans, sans-serif; }
        body { font-size: 10px; color: #111827; }
        .encabezado { margin-bottom: 8px; }
        .titulo { font-size: 14px; font-weight: bold; margin-bottom: 2px; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .tabla th, .tabla td { border: 1px solid #d1d5db; padding: 4px; }
        .tabla th { background: #f3f4f6; text-align: left; }
        .texto-derecha { text-align: right; }
        .separador { border-top: 1px solid #e5e7eb; margin: 10px 0; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
@forelse($pedidos as $pedido)
    @php
        $bloquesArticulos = $pedido->articulos->chunk(18);
        $totalBloques = max($bloquesArticulos->count(), 1);
    @endphp

    @for($indiceBloque = 0; $indiceBloque < $totalBloques; $indiceBloque++)
        @php
            $articulosPagina = $bloquesArticulos[$indiceBloque] ?? collect();
            $esUltimaPaginaPedido = $indiceBloque === ($totalBloques - 1);
        @endphp

        <div class="encabezado">
            <div class="titulo">Factura de pedido #{{ $pedido->codigo_pedido }}</div>
            <div>Fecha: {{ optional($pedido->fecha)->format('d/m/Y') ?? $pedido->fecha }}</div>
            <div>Vendedora: {{ $pedido->vendedora?->name ?? '—' }}</div>
            <div>Líder: {{ $pedido->lider?->name ?? '—' }}</div>
            <div>Zona: {{ $pedido->lider?->zona?->nombre ?? ($pedido->lider?->profile('zona') ?? '—') }}</div>
            <div>Departamento: {{ $pedido->lider?->departamento?->nombre ?? ($pedido->lider?->profile('departamento') ?? '—') }}</div>
            <div>Página interna del pedido: {{ $indiceBloque + 1 }} / {{ $totalBloques }}</div>
        </div>

        <table class="tabla">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Producto</th>
                    <th class="texto-derecha">Cant.</th>
                    <th class="texto-derecha">Precio</th>
                    <th class="texto-derecha">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articulosPagina as $articulo)
                    <tr>
                        <td>{{ $articulo->sku ?? '—' }}</td>
                        <td>{{ $articulo->producto }}</td>
                        <td class="texto-derecha">{{ number_format((float) $articulo->cantidad, 0, ',', '.') }}</td>
                        <td class="texto-derecha">${{ number_format((float) $articulo->precio_unitario, 2, ',', '.') }}</td>
                        <td class="texto-derecha">${{ number_format((float) $articulo->subtotal, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Sin artículos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($esUltimaPaginaPedido)
            <div class="separador"></div>
            <div class="texto-derecha"><strong>Total pedido:</strong> ${{ number_format((float) $pedido->total_a_pagar, 2, ',', '.') }}</div>
        @endif

        @if(!($loop->last && $esUltimaPaginaPedido))
            <div class="page-break"></div>
        @endif
    @endfor
@empty
    <p>No se encontraron pedidos con los filtros seleccionados.</p>
@endforelse
</body>
</html>
