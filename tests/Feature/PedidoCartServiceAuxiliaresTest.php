<?php

use App\Models\Catalogo;
use App\Models\Categoria;
use App\Models\CierreCampana;
use App\Models\Pedido;
use App\Models\Producto;
use App\Services\PedidoCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('excluye unidades auxiliares del calculo de puntos y unidades comerciales', function () {
    $catalogo = Catalogo::query()->create([
        'nombre' => 'Catálogo vigente',
    ]);

    $cierre = CierreCampana::query()->create([
        'nombre' => 'Campaña vigente',
        'codigo' => 'CAMP-TEST-01',
        'catalogo_id' => $catalogo->id,
        'numero_cierre' => 1,
        'fecha_inicio' => now()->subDays(2)->toDateString(),
        'fecha_cierre' => now()->addDays(2)->toDateString(),
        'estado' => CierreCampana::ESTADO_ABIERTO,
    ]);

    $categoria = Categoria::query()->create([
        'nombre' => 'General',
        'slug' => 'general',
    ]);

    $productoFacturable = Producto::query()->create([
        'nombre' => 'Producto principal',
        'precio' => 1000,
        'puntos_por_unidad' => 5,
        'sku' => 'PROD-001',
        'activo' => true,
    ]);

    $productoAuxiliar = Producto::query()->create([
        'nombre' => 'Producto auxiliar',
        'precio' => 800,
        'puntos_por_unidad' => 7,
        'sku' => 'AUX-001',
        'activo' => true,
    ]);

    $productoFacturable->categorias()->attach($categoria->id);
    $productoAuxiliar->categorias()->attach($categoria->id);

    $service = app(PedidoCartService::class);

    $resultado = $service->storePedido([
        'cart' => [
            [
                'producto_id' => $productoFacturable->id,
                'nombre' => $productoFacturable->nombre,
                'cantidad' => 3,
                'precio_unitario' => 1000,
                'puntos' => 5,
                'es_auxiliar' => false,
            ],
            [
                'producto_id' => $productoAuxiliar->id,
                'nombre' => $productoAuxiliar->nombre,
                'cantidad' => 2,
                'precio_unitario' => 800,
                'puntos' => 7,
                'es_auxiliar' => true,
            ],
        ],
    ], 'PED-AUX-0001');

    expect($resultado)->toHaveKey('success');

    $pedido = Pedido::query()->where('codigo_pedido', 'PED-AUX-0001')->firstOrFail();

    expect($pedido->catalogo_id)->toBe($catalogo->id)
        ->and($pedido->cierre_id)->toBe($cierre->id)
        ->and($pedido->unidades_facturables)->toBe(3)
        ->and($pedido->unidades_auxiliares)->toBe(2)
        ->and($pedido->cantidad_unidades)->toBe(3)
        ->and($pedido->total_puntos)->toBe(15);
});

it('mantiene unidades comerciales en cero cuando todo el carrito es auxiliar', function () {
    $catalogo = Catalogo::query()->create([
        'nombre' => 'Catálogo auxiliar',
    ]);

    CierreCampana::query()->create([
        'nombre' => 'Campaña auxiliar',
        'codigo' => 'CAMP-TEST-AUX',
        'catalogo_id' => $catalogo->id,
        'numero_cierre' => 2,
        'fecha_inicio' => now()->subDay()->toDateString(),
        'fecha_cierre' => now()->addDay()->toDateString(),
        'estado' => CierreCampana::ESTADO_ABIERTO,
    ]);

    $categoria = Categoria::query()->create([
        'nombre' => 'Auxiliares',
        'slug' => 'auxiliares',
    ]);

    $productoAuxiliar = Producto::query()->create([
        'nombre' => 'Bolsa auxiliar',
        'precio' => 500,
        'puntos_por_unidad' => 4,
        'sku' => 'AUX-002',
        'activo' => true,
    ]);

    $productoAuxiliar->categorias()->attach($categoria->id);

    $service = app(PedidoCartService::class);

    $service->storePedido([
        'cart' => [
            [
                'producto_id' => $productoAuxiliar->id,
                'nombre' => $productoAuxiliar->nombre,
                'cantidad' => 5,
                'precio_unitario' => 500,
                'puntos' => 4,
                'es_auxiliar' => true,
            ],
        ],
    ], 'PED-AUX-0002');

    $pedido = Pedido::query()->where('codigo_pedido', 'PED-AUX-0002')->firstOrFail();

    expect($pedido->unidades_facturables)->toBe(0)
        ->and($pedido->cantidad_unidades)->toBe(0)
        ->and($pedido->unidades_auxiliares)->toBe(5)
        ->and($pedido->total_puntos)->toBe(0);
});
