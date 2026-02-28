<?php

use App\Models\Catalogo;
use App\Models\CatalogoPagina;
use App\Models\CatalogoPaginaProducto;
use App\Models\Categoria;
use App\Models\Category;
use App\Models\CierreCampana;
use App\Models\Cobro;
use App\Models\Forms;
use App\Models\GastoAdministrativo;
use App\Models\MetricaLiderCampana;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\PedidoArticulo;
use App\Models\Post;
use App\Models\Producto;
use App\Models\PuntajeRegla;
use App\Models\PremioRegla;
use App\Models\RangoLider;
use App\Models\RepartoCompra;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};

middleware('auth'); // Solo requiere sesión iniciada
name('agente');

new class extends Component {
    public string $pregunta = '';
    public string $contexto = '';

    public ?string $mensaje = null;
    public ?string $estado = null;
    public ?int $latencia = null;
    public ?string $modelo = null;
    public ?string $estadoGemini = null;

    /** Estado de conexión al esquema */
    public bool $conexionOk = true;
    public ?string $conexionError = null;

    /** Resumen de datos recopilados desde los modelos */
    public array $resumenDatos = [];
    public int $maxMuestras = 8;
    public string $contextoPreparado = '';

    /** Rol actual (vía Spatie) */
    public ?string $rolActual = null;

    /** Límite de turnos a usar en el prompt y en la UI */
    public int $maxTurnosPrompt = 8;
    public int $maxTurnosUi     = 20;

    /** Ejemplos de preguntas sugeridas */
    public array $ejemplos = [
        '¿Cuántos pedidos facturados se registraron por campaña en los últimos 30 días?',
        'Listá las asesoras con menor cobranzas confirmadas y sus líderes asociadas.',
        'Mostrá el top 10 de productos por unidades vendidas y ticket promedio.',
        'Detectá campañas con pedidos pendientes de facturación y estimá el impacto.',
    ];

    /** Historial de conversación (turnos de usuaria y agente) */
    public array $conversacion = [];

    protected array $rules = [
        'pregunta' => ['required', 'string', 'min:10'],
        'contexto' => ['nullable', 'string', 'max:3000'],
    ];

    public function mount(): void
    {
        // Rol actual vía Spatie
        $this->rolActual = auth()->user()?->getRoleNames()->implode(', ');

        $this->conexionOk     = true;
        $this->conexionError  = null;
        $this->estado         = null;

        $this->conversacion = session('agente_conversacion', []);

        $this->limitarConversacion();
        $this->contextoPreparado = $this->cargarContextoDesdeModelos();

        if (! $this->conexionOk && $this->conexionError) {
            $this->estado = $this->conexionError;
            $this->conversacion[] = [
                'rol'     => 'agente',
                'texto'   => $this->estado,
                'hora'    => now()->format('H:i'),
                'alerta'  => true,
            ];

            $this->limitarConversacion();
        }
    }

    public function usarEjemplo(string $texto): void
    {
        $this->pregunta = $texto;
        $this->estado   = null;
    }

    public function limpiar(): void
    {
        $this->pregunta   = '';
        $this->contexto   = '';
        $this->estado     = null;
        $this->mensaje    = null;
        $this->latencia   = null;
        $this->modelo     = null;
        $this->estadoGemini = null;
        $this->contextoPreparado = $this->cargarContextoDesdeModelos();
    }

    public function limpiarChat(): void
    {
        $this->conversacion = [];
        $this->mensaje      = null;
        $this->estado       = null;
        $this->latencia     = null;
        $this->modelo       = null;

        session()->forget('agente_conversacion');

        $this->dispatch('refresh-chat');
    }

    public function consultar(): void
    {
        $this->contextoPreparado = $this->cargarContextoDesdeModelos();

        if (! $this->conexionOk) {
            $this->estado = $this->conexionError ?: 'No hay conexión con la base de datos en este momento.';
            $this->conversacion[] = [
                'rol'     => 'agente',
                'texto'   => $this->estado,
                'hora'    => now()->format('H:i'),
                'alerta'  => true,
            ];
            $this->limitarConversacion();

            return;
        }

        $this->validate();

        $this->estado     = null;
        $this->mensaje    = null;
        $this->latencia   = null;
        $this->modelo     = null;
        $this->estadoGemini = null;

        // Turno de usuaria
        $this->conversacion[] = [
            'rol'   => 'usuario',
            'texto' => $this->pregunta,
            'hora'  => now()->format('H:i'),
        ];

        $this->limitarConversacion();
        $this->generarVariablesOcultas();

        if ($this->contextoPreparado === '') {
            $this->estado = 'No se pudo preparar el contexto de datos de los modelos.';
            $this->conversacion[] = [
                'rol'     => 'agente',
                'texto'   => $this->estado,
                'hora'    => now()->format('H:i'),
                'alerta'  => true,
            ];
            $this->limitarConversacion();
            return;
        }

        $apiKey = config('services.gemini.key');

        if (! $apiKey) {
            $this->estado = 'Configurá la variable GEMINI_API_KEY para habilitar el agente.';
            $this->conversacion[] = [
                'rol'     => 'agente',
                'texto'   => $this->estado,
                'hora'    => now()->format('H:i'),
                'alerta'  => true,
            ];
            $this->limitarConversacion();
            return;
        }

        $modelo   = config('services.gemini.model', 'gemini-1.5-flash');
        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $modelo,
            $apiKey,
        );

        $prefijoSeguridad = <<<'TEXTO'
Actuás como analista de datos de Alma Mia Fragancias. Debes devolver hallazgos accionables en español claro y responder solo con la información ya preparada desde los modelos.

No propongas consultas SQL ni pidas nuevos datos. Si el dato no está en el contexto, explicá la limitación.
TEXTO;

        $historialReciente = array_slice($this->conversacion, -$this->maxTurnosPrompt);
        $historialTexto = collect($historialReciente)
            ->map(fn ($turno) => sprintf(
                '%s: %s',
                Str::ucfirst($turno['rol'] ?? ''),
                Str::limit((string) ($turno['texto'] ?? ''), 400)
            ))
            ->join("\n");

        $prompt = $prefijoSeguridad
            . "\n\nDatos recopilados desde modelos Eloquent (json):\n"
            . $this->contextoPreparado
            . "\n\nContexto de negocio adicional (opcional):\n"
            . ($this->contexto ?: 'Pedidos, productos, campañas, usuarias y métricas de ventas.')
            . "\n\nHistorial reciente (últimos {$this->maxTurnosPrompt} turnos):\n"
            . ($historialTexto ?: 'Sin historial previo: se inicia conversación en este turno.')
            . "\n\nConsulta del equipo:\n"
            . $this->pregunta
            . "\n\nResponde usando únicamente los datos provistos, con tablas Markdown y recomendaciones accionables cuando apliquen.";

        $inicio = microtime(true);

        $respuestaGemini = Http::timeout(20)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint, [
                'contents' => [
                    [
                        'role'  => 'user',
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature'     => 0.35,
                    'maxOutputTokens' => 1024,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH',      'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ],
            ]);

        if (! $respuestaGemini->successful()) {
            $this->estado = $respuestaGemini->json('error.message')
                ?: 'No pudimos obtener respuesta de Gemini en este momento.';
            $this->conversacion[] = [
                'rol'     => 'agente',
                'texto'   => $this->estado,
                'hora'    => now()->format('H:i'),
                'alerta'  => true,
            ];
            $this->limitarConversacion();
            return;
        }

        $texto = data_get($respuestaGemini->json(), 'candidates.0.content.parts.0.text');

        if (! $texto) {
            $this->estado = 'La respuesta de Gemini llegó vacía. Reintentá con más contexto.';
            $this->conversacion[] = [
                'rol'     => 'agente',
                'texto'   => $this->estado,
                'hora'    => now()->format('H:i'),
                'alerta'  => true,
            ];
            $this->limitarConversacion();
            return;
        }

        $this->latencia = (int) ((microtime(true) - $inicio) * 1000);
        $this->modelo   = $modelo;
        $this->mensaje  = $texto;
        $this->estado   = null;

        // Turno del agente
        $this->conversacion[] = [
            'rol'      => 'agente',
            'texto'    => $texto,
            'hora'     => now()->format('H:i'),
            'latencia' => $this->latencia,
            'modelo'   => $this->modelo,
        ];
        $this->limitarConversacion();
        $this->generarVariablesOcultas();
    }

    public function probarConexionGemini(): void
    {
        $this->estadoGemini = null;

        $apiKey = config('services.gemini.key');

        if (! $apiKey) {
            $this->estadoGemini = 'Configurá la variable GEMINI_API_KEY para probar la conexión.';

            return;
        }

        $modelo   = config('services.gemini.model', 'gemini-1.5-flash');
        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $modelo,
            $apiKey,
        );

        try {
            $inicio = microtime(true);
            $respuesta = Http::timeout(10)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($endpoint, [
                    'contents' => [
                        [
                            'role'  => 'user',
                            'parts' => [
                                ['text' => 'Responde únicamente: "Conexión OK".'],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0,
                        'maxOutputTokens' => 10,
                    ],
                ]);

            if (! $respuesta->successful()) {
                $this->estadoGemini = $respuesta->json('error.message')
                    ?: 'No se pudo contactar a Gemini en este momento.';

                return;
            }

            $texto = data_get($respuesta->json(), 'candidates.0.content.parts.0.text');
            $latencia = (int) ((microtime(true) - $inicio) * 1000);

            $this->estadoGemini = $texto
                ? sprintf('Gemini respondió "%s" en %d ms con el modelo %s.', trim($texto), $latencia, $modelo)
                : 'La respuesta de Gemini llegó vacía durante la prueba.';
        } catch (\Throwable $e) {
            $this->estadoGemini = 'Error al probar la conexión: ' . $e->getMessage();
        }
    }

    protected function limitarConversacion(): void
    {
        $this->conversacion = array_slice($this->conversacion, -$this->maxTurnosUi);

        session(['agente_conversacion' => $this->conversacion]);

        $this->dispatch('refresh-chat');
    }

    protected function cargarContextoDesdeModelos(): string
    {
        $this->conexionOk    = true;
        $this->conexionError = null;

        try {
            $this->resumenDatos = [
                'usuarios' => [
                    'total'     => User::count(),
                    'recientes' => User::query()
                        ->select('id', 'name', 'email')
                        ->latest('id')
                        ->take($this->maxMuestras)
                        ->get()
                        ->map(fn (User $user) => [
                            'id'     => $user->id,
                            'nombre' => $user->name,
                            'email'  => $user->email,
                            'roles'  => $user->getRoleNames()->implode(', '),
                        ])
                        ->toArray(),
                ],
                'productos' => [
                    'total'    => Producto::count(),
                    'muestras' => $this->muestrasDe(Producto::class, ['id', 'nombre', 'precio', 'stock_actual', 'activo', 'sku']),
                ],
                'categorias' => [
                    'total'    => Categoria::count(),
                    'muestras' => $this->muestrasDe(Categoria::class, ['id', 'nombre', 'slug']),
                ],
                'catalogos' => [
                    'total'    => Catalogo::count(),
                    'muestras' => $this->muestrasDe(Catalogo::class, ['id', 'nombre', 'descripcion']),
                ],
                'paginas_catalogo' => [
                    'total'    => CatalogoPagina::count(),
                    'muestras' => $this->muestrasDe(CatalogoPagina::class, ['id', 'catalogo_id', 'numero']),
                ],
                'productos_pagina' => [
                    'total'    => CatalogoPaginaProducto::count(),
                    'muestras' => $this->muestrasDe(CatalogoPaginaProducto::class, ['id', 'catalogo_pagina_id', 'producto_id', 'pos_x', 'pos_y']),
                ],
                'cierres_campana' => [
                    'total'    => CierreCampana::count(),
                    'muestras' => $this->muestrasDe(CierreCampana::class, ['id', 'codigo', 'nombre', 'estado', 'fecha_inicio', 'fecha_cierre']),
                ],
                'pedidos' => [
                    'total'    => Pedido::count(),
                    'muestras' => $this->muestrasDe(Pedido::class, ['id', 'codigo_pedido', 'vendedora_id', 'lider_id', 'coordinadora_id', 'fecha', 'estado', 'total_a_pagar', 'total_puntos', 'cantidad_unidades']),
                ],
                'articulos_pedido' => [
                    'total'    => PedidoArticulo::count(),
                    'muestras' => $this->muestrasDe(PedidoArticulo::class, ['pedido_id', 'producto', 'cantidad', 'ganancia', 'subtotal', 'puntos']),
                ],
                'pagos' => [
                    'total'    => Pago::count(),
                    'muestras' => $this->muestrasDe(Pago::class, ['id', 'pedido_id', 'vendedora_id', 'mes_campana', 'mes_pago_programado', 'monto', 'estado', 'fecha_pago']),
                ],
                'cobros' => [
                    'total'    => Cobro::count(),
                    'muestras' => $this->muestrasDe(Cobro::class, ['id', 'pedido_id', 'lider_id', 'coordinadora_id', 'mes_campana', 'mes_pago_programado', 'monto', 'estado', 'fecha_pago']),
                ],
                'metricas_lider_campana' => [
                    'total'    => MetricaLiderCampana::count(),
                    'muestras' => $this->muestrasDe(MetricaLiderCampana::class, ['id', 'lider_id', 'revendedora_id', 'cierre_campana_id', 'rango_lider_id', 'monto_reparto_total', 'premio_total', 'actividad_ok', 'altas_ok']),
                ],
                'rangos_lider' => [
                    'total'    => RangoLider::count(),
                    'muestras' => $this->muestrasDe(RangoLider::class, ['id', 'nombre', 'revendedoras_minimas', 'revendedoras_maximas', 'unidades_minimas', 'premio_actividad', 'premio_unidades', 'premio_cobranzas', 'reparto_referencia']),
                ],
                'premios' => [
                    'total'    => PremioRegla::count(),
                    'muestras' => $this->muestrasDe(PremioRegla::class, ['id', 'rango_lider_id', 'campana_id', 'tipo', 'umbral_minimo', 'umbral_maximo', 'monto']),
                ],
                'reglas_puntaje' => [
                    'total'    => PuntajeRegla::count(),
                    'muestras' => $this->muestrasDe(PuntajeRegla::class, ['id', 'descripcion', 'bonificacion', 'porcentaje', 'puntaje_minimo', 'puntos_mensuales', 'puntos_por_campania']),
                ],
                'repartos_compra' => [
                    'total'    => RepartoCompra::count(),
                    'muestras' => $this->muestrasDe(RepartoCompra::class, ['id', 'tipo_compra', 'monto_por_revendedora', 'porcentaje_lider', 'porcentaje_revendedora']),
                ],
                'gastos_administrativos' => [
                    'total'    => GastoAdministrativo::count(),
                    'muestras' => $this->muestrasDe(GastoAdministrativo::class, ['id', 'concepto', 'monto', 'tipo']),
                ],
                'formularios' => [
                    'total'    => Forms::count(),
                    'muestras' => $this->muestrasDe(Forms::class, ['id', 'name', 'slug', 'is_active']),
                ],
                'posts' => [
                    'total'    => Post::count(),
                    'muestras' => $this->muestrasDe(Post::class, ['id', 'title', 'slug']),
                ],
                'categorias_wave' => [
                    'total' => Category::count(),
                ],
            ];
        } catch (\Throwable $e) {
            $this->conexionOk    = false;
            $this->conexionError = 'No se pudo leer los datos de los modelos: ' . $e->getMessage();

            return '';
        }

        return json_encode($this->resumenDatos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    protected function muestrasDe(string $modelo, array $campos): array
    {
        return $modelo::query()
            ->select($campos)
            ->latest('id')
            ->take($this->maxMuestras)
            ->get()
            ->map(fn ($item) => collect($item->toArray())->only($campos)->all())
            ->toArray();
    }

    /**
     * Hook para calcular variables internas/ocultas si más adelante las necesitás.
     * Por ahora es un stub para evitar errores de método inexistente.
     */
    protected function generarVariablesOcultas(): void
    {
        // Podés usar este método para derivar KPIs internos,
        // cachear resúmenes, etc., sin afectar la respuesta.
    }
};
?>

<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("volt-anonymous-fragment-eyJuYW1lIjoiYWdlbnRlIiwicGF0aCI6InJlc291cmNlc1wvdGhlbWVzXC9hbmNob3JcL3BhZ2VzXC9hZ2VudGVcL2luZGV4LmJsYWRlLnBocCJ9", Livewire\Volt\Precompilers\ExtractFragments::componentArguments([...get_defined_vars(), ...array (
)]));

$__html = app('livewire')->mount($__name, $__params, 'lw-2272620151-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/agente/index.blade.php ENDPATH**/ ?>