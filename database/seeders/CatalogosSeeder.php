<?php

namespace Database\Seeders;

use App\Models\Catalogo;
use App\Models\CatalogoPagina;
use App\Models\CatalogoPaginaProducto;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        $producto = Producto::first();

        if (! $producto) {
            $producto = Producto::create([
                'nombre' => 'Producto demo catálogo',
                'precio' => 0,
                'puntos_por_unidad' => 0,
                'sku' => 'CATALOGO-DEMO',
                'descripcion' => 'Producto de ejemplo para la vista de catálogo.',
                'activo' => true,
            ]);
        }

        $catalogo = Catalogo::firstOrCreate(
            ['nombre' => 'Catálogo Demo'],
            [
                'descripcion' => 'Catálogo base para pruebas con posiciones relativas.',
                'imagen_portada' => null,
            ]
        );

        $pagina = CatalogoPagina::firstOrCreate(
            [
                'catalogo_id' => $catalogo->id,
                'numero' => 1,
            ],
            [
                'imagen' => 'catalogos/demo/pagina-1.jpg',
            ]
        );

        CatalogoPaginaProducto::firstOrCreate(
            [
                'catalogo_pagina_id' => $pagina->id,
                'producto_id' => $producto->id,
            ],
            [
                'pos_x' => 50,
                'pos_y' => 50,
            ]
        );
    }
}
