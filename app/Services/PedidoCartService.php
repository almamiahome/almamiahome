<?php

namespace App\Services;

use App\Models\{
    Catalogo,
    CatalogoPagina,
    CatalogoPaginaProducto,
    Categoria,
    GastoAdministrativo,
    Pedido,
    PedidoArticulo,
    Producto,
    User
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PedidoCartService
{
    public function generarCodigoPedido(): string
    {
        return 'PED-' . now()->format('YmdHis');
    }

    public function obtenerCategorias()
    {
        return Categoria::orderBy('nombre')->get();
    }

    public function obtenerProductosConReglas(): array
    {
        $reglasPorCategoriaId = DB::table('categoria_puntaje_regla')
            ->join('puntaje_reglas', 'categoria_puntaje_regla.puntaje_regla_id', '=', 'puntaje_reglas.id')
            ->select(
                'categoria_puntaje_regla.categoria_id',
                'puntaje_reglas.id',
                'puntaje_reglas.min_unidades',
                'puntaje_reglas.max_unidades',
                'puntaje_reglas.descripcion',
                'puntaje_reglas.porcentaje',
                'puntaje_reglas.puntaje_minimo'
            )
            ->get()
            ->groupBy('categoria_id')
            ->map(function ($rows) {
                return $rows->map(function ($r) {
                    return [
                        'id'             => $r->id,
                        'min_unidades'   => $r->min_unidades,
                        'max_unidades'   => $r->max_unidades,
                        'descripcion'    => $r->descripcion,
                        'porcentaje'     => (float) ($r->porcentaje ?? 0),
                        'puntaje_minimo' => $r->puntaje_minimo,
                    ];
                })->values()->all();
            })->toArray();

        return Producto::with('categorias')
        ->where('activo', true)
        ->orderBy('nombre')
        ->get()
        ->map(function ($p) use ($reglasPorCategoriaId) {
            $categorias = $p->categorias;

            $reglas = collect($categorias)
                ->flatMap(function ($cat) use ($reglasPorCategoriaId) {
                    return $reglasPorCategoriaId[$cat->id] ?? [];
                })
                ->unique('id')
                ->values()
                ->all();

            return [
                'id'                => $p->id,
                'sku'               => $p->sku, // <--- AGREGA ESTA LÍNEA
                'nombre'            => $p->nombre,
                'precio'            => (float) $p->precio,
                'puntos_por_unidad' => (int) ($p->puntos_por_unidad ?? 0),
                'stock_actual'      => is_null($p->stock_actual) ? null : (int) $p->stock_actual,
                'categorias'        => $categorias->pluck('nombre')->toArray(),
                'imagen'            => $p->imagen ? asset('storage/'.$p->imagen) : null,
                'reglas'            => $reglas,
                'bulto'             => (float) ($p->bulto ?? 0),
            ];
        })->toArray();
}

    public function obtenerGastosAdministrativos(): array
    {
        return GastoAdministrativo::orderBy('concepto')
            ->get()
            ->map(function (GastoAdministrativo $g) {
                return [
                    'id'       => $g->id,
                    'concepto' => $this->nullableString($g->concepto),
                    'monto'    => round((float) $g->monto, 2),
                    'tipo'     => $this->nullableString($g->tipo),
                ];
            })->toArray();
    }

    public function obtenerContextoUsuarios(?User $usuario): array
    {
        $esLiderAutenticado     = $usuario?->hasRole('lider') ?? false;
        $esVendedoraAutenticada = ($usuario?->hasRole('vendedora') ?? false) && ! $esLiderAutenticado;

        $vendedoras = [];
        $lideres = [];
        $vendedoraSeleccionadaId = null;
        $liderSeleccionadoId = null;

        if ($esVendedoraAutenticada) {
            $vendedoras = [$this->userProfileData($usuario)];
            $vendedoraSeleccionadaId = $usuario?->id;

            $lider = $usuario?->lider_id ? User::find($usuario->lider_id) : null;
            $lideres = $lider ? [$this->userProfileData($lider)] : [];
            $liderSeleccionadoId = $lider?->id;
        } elseif ($esLiderAutenticado) {
            $lideres = [$this->userProfileData($usuario)];
            $liderSeleccionadoId = $usuario?->id;

            $vendedoras = User::role('vendedora')
                ->where(function ($query) use ($usuario) {
                    $query->where('lider_id', $usuario?->id);

                    if ($usuario?->hasRole('vendedora')) {
                        $query->orWhere('id', $usuario->id);
                    }
                })
                ->orderBy('name')
                ->get()
                ->map(fn (User $user) => $this->userProfileData($user))
                ->toArray();
        } else {
            $vendedoras = User::role('vendedora')
                ->orderBy('name')
                ->get()
                ->map(fn (User $user) => $this->userProfileData($user))
                ->toArray();

            $lideres = User::role('lider')
                ->orderBy('name')
                ->get()
                ->map(fn (User $user) => $this->userProfileData($user))
                ->toArray();
        }

        return [
            'esLiderAutenticado'     => $esLiderAutenticado,
            'esVendedoraAutenticada' => $esVendedoraAutenticada,
            'vendedoras'             => $vendedoras,
            'lideres'                => $lideres,
            'vendedoraSeleccionadaId'=> $vendedoraSeleccionadaId,
            'liderSeleccionadoId'    => $liderSeleccionadoId,
            'responsable'            => $this->userProfileData($usuario),
        ];
    }

    public function obtenerPaginasCatalogo(): array
    {
        $catalogo = Catalogo::with([
            'paginas.productos.producto.categorias',
            'paginas.productos.productosGrupo.categorias',
        ])->latest('id')->first();

        if (! $catalogo) {
            return [];
        }

        return $catalogo->paginas
            ->sortBy('numero')
            ->map(function (CatalogoPagina $pagina) {
                return [
                    'id'          => $pagina->id,
                    'numero'      => $pagina->numero,
                    'imagen'      => $pagina->imagen,
                    'imagen_path' => $pagina->imagen ? asset('storage/'.$pagina->imagen) : null,
                    'productos'   => $pagina->productos->map(function (CatalogoPaginaProducto $pivot) {
                        $productoPrincipal = $pivot->producto;

                        return [
                            'id'              => $pivot->id,
                            'producto_id'     => $pivot->producto_id,
                            'es_grupo'        => (bool) $pivot->es_grupo,
                            'pos_x'           => (float) $pivot->pos_x,
                            'pos_y'           => (float) $pivot->pos_y,
                            'producto'        => $productoPrincipal ? [
                                'id'                => $productoPrincipal->id,
                                'nombre'            => $productoPrincipal->nombre,
                                'precio'            => (float) $productoPrincipal->precio,
                                'puntos_por_unidad' => (int) ($productoPrincipal->puntos_por_unidad ?? 0),
                                'stock_actual'      => is_null($productoPrincipal->stock_actual)
                                    ? null
                                    : (int) $productoPrincipal->stock_actual,
                                'categorias'        => $productoPrincipal->categorias->pluck('nombre')->toArray(),
                                'imagen'            => $productoPrincipal->imagen
                                    ? asset('storage/'.$productoPrincipal->imagen)
                                    : null,
                            ] : null,
                            'productos_grupo' => $pivot->productosGrupo
                                ->map(function ($productoGrupo) {
                                    return [
                                        'id'                => $productoGrupo->id,
                                        'nombre'            => $productoGrupo->nombre,
                                        'sku'               => $productoGrupo->sku,
                                        'precio'            => (float) $productoGrupo->precio,
                                        'puntos_por_unidad' => (int) ($productoGrupo->puntos_por_unidad ?? 0),
                                        'stock_actual'      => is_null($productoGrupo->stock_actual)
                                            ? null
                                            : (int) $productoGrupo->stock_actual,
                                        'categorias'        => $productoGrupo->categorias->pluck('nombre')->toArray(),
                                        'imagen'            => $productoGrupo->imagen
                                            ? asset('storage/'.$productoGrupo->imagen)
                                            : null,
                                    ];
                                })
                                ->values()
                                ->toArray(),
                        ];
                    })->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();
    }

    public function storePedido(array $payload, string $codigoPedido, ?User $usuarioActual = null): array
    {
        $vendedora_id = $payload['vendedora_id'] ?? null;
        $lider_id = $payload['lider_id'] ?? null;

        $usuarioActual = $usuarioActual ?: auth()->user();

        $esLider = $usuarioActual?->hasRole('lider') ?? false;
        $esVendedora = $usuarioActual?->hasRole('vendedora') ?? false;

        if ($esVendedora && ! $esLider) {
            $vendedora_id = $usuarioActual->id;
            $lider_id     = $usuarioActual->lider_id ?: null;
        }

        if ($esLider) {
            $lider_id = $usuarioActual?->id;

            $vendedorasPermitidas = User::role('vendedora')
                ->where(function ($query) use ($usuarioActual) {
                    $query->where('lider_id', $usuarioActual?->id);

                    if ($usuarioActual?->hasRole('vendedora')) {
                        $query->orWhere('id', $usuarioActual->id);
                    }
                });

            if ($vendedora_id && ! $vendedorasPermitidas->where('id', $vendedora_id)->exists()) {
                return ['error' => 'La vendedora seleccionada no pertenece a tu red.'];
            }
        }

        $payload = array_merge($payload, [
            'codigo_pedido' => $codigoPedido,
            'vendedora_id'  => $vendedora_id,
            'lider_id'      => $lider_id,
        ]);

        $validator = Validator::make($payload, [
            'codigo_pedido'                    => ['required', 'string', 'max:255', Rule::unique('pedidos', 'codigo_pedido')],
            'cart'                             => ['required', 'array', 'min:1'],
            'cart.*.producto_id'               => ['required', 'integer', 'exists:productos,id'],
            'cart.*.sku'                       => ['nullable', 'string', 'max:255'],
            'cart.*.nombre'                    => ['required', 'string', 'max:255'],
            'cart.*.cantidad'                  => ['required', 'integer', 'min:1'],
            'cart.*.precio_unitario'           => ['required', 'numeric', 'min:0'],
            'cart.*.porcentaje_descuento'      => ['nullable', 'numeric', 'min:0'],
            'cart.*.precio_unitario_descuento' => ['nullable', 'numeric', 'min:0'],
            'cart.*.subtotal'                  => ['nullable', 'numeric', 'min:0'],
            'cart.*.puntos'                    => ['nullable', 'integer', 'min:0'],
            'gastos'                           => ['nullable', 'array'],
            'gastos.*'                         => ['integer', 'exists:gastos_administrativos,id'],
            'observaciones'                    => ['nullable', 'string', 'max:1000'],
            'vendedora_id'                     => ['nullable', 'exists:users,id'],
            'lider_id'                         => ['nullable', 'exists:users,id'],
        ], [
            'cart.min' => 'El carrito está vacío.',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()->first()];
        }

        $data              = $validator->validated();
        $cartItems         = [];
        $subtotalCatalogo  = 0;
        $subtotal          = 0;
        $totalGanancias    = 0;
        $totalPuntos       = 0;
        $totalUnidades     = 0;

        foreach ($data['cart'] as $item) {
            $producto = Producto::find($item['producto_id']);

            if (! $producto) {
                return ['error' => 'Producto no encontrado.'];
            }

            if (! is_null($producto->stock_actual) && $item['cantidad'] > $producto->stock_actual) {
                return ['error' => "Cantidad supera stock actual del producto {$producto->nombre}."];
            }

            $categoriaNombre = $producto->categorias()
                ->orderBy('nombre')
                ->value('nombre');

            $cantidad = (int) $item['cantidad'];
            $precioUnitarioCatalogo = round((float) $item['precio_unitario'], 2);
            $porcentajeDesc = isset($item['porcentaje_descuento'])
                ? (float) $item['porcentaje_descuento']
                : 0;

            if (isset($item['precio_unitario_descuento'])) {
                $precioDesc = round((float) $item['precio_unitario_descuento'], 2);
            } else {
                $precioDesc = round($precioUnitarioCatalogo * (1 - $porcentajeDesc / 100), 2);
            }

            $subtotalCatItem = round($precioUnitarioCatalogo * $cantidad, 2);
            $subtotalDesc    = round($precioDesc * $cantidad, 2);
            $puntos          = (int) ($item['puntos'] ?? 0);
            $gananciaItem    = $subtotalCatItem - $subtotalDesc;
            $bultoPorUnidad  = (float) ($producto->bulto ?? 0);
            $bultoTotalItem  = round($bultoPorUnidad * $cantidad, 2);

            $subtotalCatalogo  += $subtotalCatItem;
            $subtotal          += $subtotalDesc;
            $totalGanancias    += $gananciaItem;
            $totalPuntos       += $puntos * $cantidad;
            $totalUnidades     += $cantidad;

            $cartItems[] = [
                'sku'                  => $producto->sku,
                'producto'             => $producto->nombre,
                'descripcion'          => $categoriaNombre,
                'cantidad'             => $cantidad,
                'precio_catalogo'      => $precioUnitarioCatalogo,
                'porcentaje_descuento' => $porcentajeDesc,
                'ganancia'             => $gananciaItem,
                'precio_unitario'      => $precioDesc,
                'subtotal'             => $subtotalDesc,
                'puntos'               => $puntos,
                'bulto'                => $bultoTotalItem,
            ];
        }

        $gastosCollection = collect();

        if (! empty($data['gastos'])) {
            $gastosCollection = GastoAdministrativo::whereIn('id', $data['gastos'])->get();
        }

        $totalGastos = round(
            $gastosCollection->sum(fn (GastoAdministrativo $gasto) => (float) $gasto->monto),
            2
        );

        $totalAPagar = round($subtotal + $totalGastos, 2);

        $vendedora = $data['vendedora_id'] ? User::find($data['vendedora_id']) : null;
        $lider     = $data['lider_id'] ? User::find($data['lider_id']) : null;

        if ($vendedora && ! $vendedora->hasRole('vendedora')) {
            return ['error' => 'El usuario seleccionado no tiene rol vendedora.'];
        }

        if ($lider && ! $lider->hasRole('lider')) {
            return ['error' => 'El usuario seleccionado no tiene rol lider.'];
        }

        DB::beginTransaction();

        try {
            $datosPedido = $this->datosPedidoPayload($lider, $vendedora, $usuarioActual);

            $datosPedido['gastos'] = $gastosCollection->map(function (GastoAdministrativo $gasto) {
                return [
                    'id'       => $gasto->id,
                    'concepto' => $gasto->concepto,
                    'monto'    => round((float) $gasto->monto, 2),
                    'tipo'     => $gasto->tipo,
                ];
            })->values()->toArray();

            $pedido = Pedido::create([
                'codigo_pedido'         => $codigoPedido,
                'vendedora_id'          => $vendedora?->id,
                'lider_id'              => $lider?->id,
                'responsable_id'        => $usuarioActual?->id,
                'fecha'                 => now()->toDateString(),
                'mes'                   => now()->format('Y-m'),
                'total_precio_catalogo' => $subtotalCatalogo,
                'total_gastos'          => $totalGastos,
                'total_ganancias'       => $totalGanancias,
                'total_a_pagar'         => $totalAPagar,
                'total_puntos'          => $totalPuntos,
                'cantidad_unidades'     => $totalUnidades,
                'estado'                => 'Nuevo',
                'observaciones'         => $data['observaciones'] ?? null,
                'datos_pedido'          => $datosPedido,
            ]);

            foreach ($cartItems as $item) {
                PedidoArticulo::create(array_merge($item, [
                    'pedido_id' => $pedido->id,
                ]));
            }

            DB::commit();

            return [
                'success'       => 'Pedido creado correctamente.',
                'codigo_pedido' => $this->generarCodigoPedido(),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return ['error' => 'Error al guardar pedido: ' . $e->getMessage()];
        }
    }

    private function userProfileData(?User $user): array
    {
        return [
            'id'        => $user?->id,
            'name'      => $this->nullableString($user?->name),
            'lider_id'  => $user?->lider_id,
            'zona'      => $this->profileValue($user, 'zona'),
            'direccion' => $this->profileValue($user, 'direccion'),
        ];
    }

    private function nullableString(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function profileValue(?User $user, string $key): ?string
{
    if (! $user) {
        return null;
    }

    try {
        // Wave usa profile('clave')
        $value = $user->profile($key);
    } catch (\Throwable $e) {
        return null;
    }

    return $this->nullableString($value);
}


    private function datosPedidoPayload(?User $lider, ?User $vendedora, ?User $responsable): array
    {
        return [
            'lider' => [
                'nombre'    => $this->nullableString($lider?->name),
                'direccion' => $this->profileValue($lider, 'direccion'),
                'zona'      => $this->profileValue($lider, 'zona'),
            ],
            'vendedora' => [
                'nombre'    => $this->nullableString($vendedora?->name),
                'direccion' => $this->profileValue($vendedora, 'direccion'),
                'zona'      => $this->profileValue($vendedora, 'zona'),
            ],
            'responsable' => [
                'nombre'    => $this->nullableString($responsable?->name),
                'direccion' => $this->profileValue($responsable, 'direccion'),
                'zona'      => $this->profileValue($responsable, 'zona'),
            ],
        ];
    }
}
