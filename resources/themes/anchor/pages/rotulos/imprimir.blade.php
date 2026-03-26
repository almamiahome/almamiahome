<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rótulos {{ $mes }}/{{ $anio }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border: 1px solid #d1d5db;
            font-size: 10px;
        }

        .cell {
            border: 1px solid #d1d5db;
            padding: 4px 6px;
            height: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .page-break {
            page-break-before: always;
        }

        .label-title {
            font-weight: 600;
        }
    </style>
</head>
<body>

@php
    $capacidadPagina = $capacidadPagina ?? 52;
    $pages = array_chunk($rotulos, $capacidadPagina); /* 4 columnas × 13 filas */
@endphp

@foreach($pages as $pageIndex => $pageRotulos)
    <div class="{{ $pageIndex > 0 ? 'page-break' : '' }}">
        <div class="grid">
            @foreach($pageRotulos as $rotulo)
                <div class="cell">
                    <div>
                        <div><span class="label-title">Revendedora:</span> {{ $rotulo['vendedora'] }}</div>
                        <div><span class="label-title">Líder:</span> {{ $rotulo['lider'] }}</div>
                    </div>
                    <div><span class="label-title">Bulto N°:</span> {{ $rotulo['numero'] }}</div>
                </div>
            @endforeach

            @for ($empty = count($pageRotulos); $empty < $capacidadPagina; $empty++)
                <div class="cell"></div>
            @endfor
        </div>
    </div>
@endforeach

</body>
</html>
