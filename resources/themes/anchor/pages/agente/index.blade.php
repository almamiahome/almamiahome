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

@volt('agente')
<div class="w-full"><!-- ÚNICO ROOT ELEMENT PARA LIVEWIRE/VOLT -->
    <x-layouts.app>
        <x-app.container class="space-y-8">
            {{-- Hero / Intro --}}
            

            {{-- Chat principal --}}
            <section class="flex flex-col gap-6 lg:min-h-[760px]">
                <div class="flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 sm:px-6">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-indigo-700">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100">
                                <x-phosphor-chats-teardrop-duotone class="h-4 w-4" />
                            </span>
                            Conversación activa
                        </div>
                        <div class="flex items-center gap-2 text-[11px] text-slate-500">
                            <span class="hidden sm:inline">Historial de {{ $maxTurnosUi }} turnos</span>
                            <span @class([
                                'inline-flex items-center gap-2 rounded-full px-3 py-1 font-semibold ring-1',
                                'bg-emerald-50 text-emerald-700 ring-emerald-100' => $conexionOk,
                                'bg-rose-50 text-rose-700 ring-rose-100' => ! $conexionOk,
                            ])>
                                <span @class([
                                    'h-2 w-2 rounded-full',
                                    'bg-emerald-500' => $conexionOk,
                                    'bg-rose-500' => ! $conexionOk,
                                ])></span>
                                {{ $conexionOk ? 'Conexión a la base activa' : 'Sin conexión a la base' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col bg-gradient-to-b from-slate-50 via-white to-slate-50">
                        <div
                            x-data="{ scroll() { const el = this.$refs.panel; if (el) { el.scrollTop = el.scrollHeight; } }}"
                            x-init="scroll()"
                            x-on:livewire:load.window="scroll()"
                            x-on:refresh-chat.window="scroll()"
                            class="flex-1 overflow-y-auto px-4 py-6 sm:px-6"
                            x-ref="panel">
                            <div class="space-y-4 pb-4">
                                @forelse ($conversacion as $turno)
                                    @if ($turno['rol'] === 'usuario')
                                        <div class="flex justify-end">
                                            <div class="max-w-3xl space-y-2 text-right">
                                                <div class="flex items-center justify-end gap-2 text-[11px] text-slate-500">
                                                    <span>{{ $turno['hora'] ?? '--:--' }}</span>
                                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-600 text-sm font-semibold text-white shadow-sm ring-2 ring-indigo-200">
                                                        <x-phosphor-user-duotone class="h-5 w-5" />
                                                    </div>
                                                </div>
                                                <div class="inline-flex max-w-3xl flex-col gap-2 rounded-2xl bg-indigo-600 px-4 py-3 text-left text-sm text-white shadow-sm ring-1 ring-indigo-500/30">
                                                    <div class="leading-relaxed">{!! nl2br(e($turno['texto'])) !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-white shadow-sm ring-2 ring-slate-300">
                                                <x-phosphor-robot-duotone class="h-5 w-5" />
                                            </div>
                                            <div class="max-w-3xl space-y-2">
                                                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                                    <span class="font-semibold text-slate-700">Agente</span>
                                                    <span>{{ $turno['hora'] ?? '--:--' }}</span>
                                                    @if (! empty($turno['latencia']))
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                                            <x-phosphor-timer-duotone class="h-4 w-4" /> {{ $turno['latencia'] }} ms
                                                        </span>
                                                    @endif
                                                    @if (! empty($turno['modelo']))
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">
                                                            {{ $turno['modelo'] }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div @class([
                                                    'rounded-2xl px-4 py-3 text-sm shadow-sm ring-1',
                                                    'bg-amber-50 text-amber-900 ring-amber-200' => $turno['alerta'] ?? false,
                                                    'bg-slate-100/80 text-slate-900 ring-slate-200' => ! ($turno['alerta'] ?? false),
                                                ])>
                                                    <div class="prose prose-sm max-w-none leading-relaxed text-slate-800">
                                                        {!! \Illuminate\Support\Str::markdown($turno['texto'] ?? '') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="flex h-full items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white/60 p-6 text-sm text-slate-500">
                                        Iniciá la conversación con una consulta en lenguaje natural.
                                    </div>
                                @endforelse

                                <div
                                    wire:loading.flex
                                    wire:target="consultar"
                                    class="flex items-center gap-3 rounded-2xl bg-white px-4 py-3 text-sm text-slate-700 shadow-sm ring-1 ring-slate-200">
                                    <x-phosphor-dots-three-outline-vertical-duotone class="h-5 w-5 animate-pulse text-indigo-500" />
                                    <span>El agente está pensando...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Formulario de entrada --}}
                    <div class="border-t border-slate-100 bg-white/95 px-4 py-3 backdrop-blur sm:px-6">
                        <form wire:submit.prevent="consultar" class="space-y-4">
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span>Preguntá en lenguaje natural. El agente responde con el contexto de datos cargado.</span>
                                <span class="hidden sm:inline">Tiempo máximo 20s</span>
                            </div>

                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col gap-3">
                                    <label class="block">
                                        <span class="sr-only">Pregunta</span>
                                        <textarea
                                            id="pregunta"
                                            wire:model.defer="pregunta"
                                            rows="4"
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200"
                                            placeholder="Escribí tu consulta para que el agente responda con los datos ya extraídos de los modelos."></textarea>
                                        @error('pregunta')
                                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </label>

                                    <div class="flex flex-col gap-2 text-sm font-semibold sm:flex-row sm:items-center sm:justify-start">
                                        <button
                                            type="submit"
                                            @class([
                                                'inline-flex items-center justify-center gap-2 rounded-full px-4 py-2 text-white shadow-sm transition focus:outline-none focus:ring-2',
                                                'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-300' => $conexionOk,
                                                'cursor-not-allowed bg-slate-200 text-slate-500 ring-1 ring-slate-300' => ! $conexionOk,
                                            ])
                                            wire:loading.attr="disabled"
                                            @disabled(! $conexionOk)>
                                            <x-phosphor-sparkle-duotone class="h-5 w-5" />
                                            <span wire:loading.remove>Enviar</span>
                                            <span wire:loading>Consultando…</span>
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-4 py-2 text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50"
                                            wire:click="limpiar">
                                            <x-phosphor-eraser-duotone class="h-5 w-5" />
                                            Limpiar campos
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-white shadow-sm ring-1 ring-slate-700 transition hover:bg-slate-800"
                                            wire:click="limpiarChat">
                                            <x-phosphor-chat-circle-duotone class="h-5 w-5" />
                                            Limpiar chat
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-2 text-sm font-semibold text-slate-700">
                                    @foreach ($ejemplos as $ejemplo)
                                        <button
                                            type="button"
                                            class="ejemplo flex w-full items-start gap-2 rounded-xl bg-slate-100 px-3 py-2 text-left ring-1 ring-slate-200 transition hover:bg-slate-200"
                                            wire:click="usarEjemplo(@js($ejemplo))">
                                            <x-phosphor-magic-wand-duotone class="mt-0.5 h-4 w-4" />
                                            <span>{{ $ejemplo }}</span>
                                        </button>
                                    @endforeach
                                </div>

                                @if ($estado)
                                    <p class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-700">
                                        {{ $estado }}
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            
            <section class="flex flex-col gap-6 rounded-3xl bg-gradient-to-r from-indigo-50 via-white to-sky-50 p-8 shadow-sm ring-1 ring-indigo-100">
                <div class="space-y-3">
                    <p class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-indigo-700 ring-1 ring-indigo-200">
                        Agente inteligente
                    </p>
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-semibold text-slate-900">
                                Consultá con Gemini usando el contexto de datos actual
                            </h1>
                            <p class="max-w-3xl text-slate-700">
                                El asistente resume la información existente en la base (productos, pedidos, campañas y personas)
                                y responde tus preguntas solo con esos datos. Ideal para validar hipótesis rápidas sobre ventas,
                                campañas y desempeño sin ejecutar SQL.
                            </p>
                            <div class="flex flex-wrap gap-3 text-sm text-indigo-800">
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 ring-1 ring-indigo-100">
                                    🔒 Acceso seguro: requiere sesión iniciada
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 ring-1 ring-indigo-100">
                                    ⚙️ Motor: Gemini
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 ring-1 ring-indigo-100">
                                    🔑 Rol actual: {{ $rolActual ?: 'Sin rol asignado' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 rounded-2xl bg-white/80 p-4 shadow-sm ring-1 ring-indigo-100">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="text-sm font-semibold text-slate-800">Pautas de seguridad</h2>
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm ring-1 ring-indigo-300 transition hover:bg-indigo-700"
                                    wire:click="probarConexionGemini"
                                    wire:loading.attr="disabled">
                                    <x-phosphor-plug-charging-duotone class="h-4 w-4" />
                                    <span wire:loading.remove>Probar conexión</span>
                                    <span wire:loading>Probando…</span>
                                </button>
                            </div>
                            <ul class="space-y-2 text-sm text-slate-700">
                                <li class="flex gap-2">
                                    <span class="text-indigo-500">•</span>
                                    Gemini no ejecuta SQL: responde solo con los datos ya cargados desde los modelos.
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-indigo-500">•</span>
                                    Agregá contexto de negocio (fechas, roles, zonas) para guiar la respuesta.
                                </li>
                                <li class="flex gap-2">
                                    <span class="text-indigo-500">•</span>
                                    Si el dato no está disponible, el agente avisará la limitación en vez de inventar resultados.
                                </li>
                            </ul>
                            @if ($estadoGemini)
                                <p class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                                    {{ $estadoGemini }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            {{-- Paneles colapsables de contexto y tips --}}
            <section class="space-y-4">
                <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100" @if(false) open @endif>
                    <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold text-slate-900">
                        <span>Datos cargados desde los modelos</span>
                        <x-phosphor-caret-down-duotone class="h-5 w-5 transition group-open:rotate-180" />
                    </summary>
                    <p class="mt-2 text-sm text-slate-600">
                        Gemini recibe un resumen fresco de las colecciones principales (máx. {{ $maxMuestras }} ítems por modelo).
                    </p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-800">
                        @foreach ($resumenDatos as $clave => $detalle)
                            @php($muestras = collect($detalle['muestras'] ?? $detalle['recientes'] ?? []))
                            <li class="rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200">
                                <p class="font-semibold text-slate-900">
                                    {{ \Illuminate\Support\Str::headline($clave) }}
                                </p>
                                <p class="text-slate-600">
                                    Total: {{ $detalle['total'] ?? 0 }} registros
                                </p>
                                @if ($muestras->isNotEmpty())
                                    <p class="mt-2 text-xs text-slate-500">Ejemplos utilizados en el contexto:</p>
                                    <pre class="mt-1 max-h-40 overflow-y-auto rounded-xl bg-white px-3 py-2 text-[11px] leading-relaxed text-slate-800 ring-1 ring-slate-200">{{ json_encode($muestras->take(2), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </details>

                <details class="group rounded-2xl bg-slate-900 p-6 text-white shadow-sm ring-1 ring-slate-800" @if(false) open @endif>
                    <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold">
                        <span>Tips para mejores respuestas</span>
                        <x-phosphor-caret-down-duotone class="h-5 w-5 transition group-open:rotate-180" />
                    </summary>
                    <ul class="mt-3 space-y-2 text-sm text-slate-100">
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Indicá períodos o campañas específicas para acotar el análisis.
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Pedí KPIs clave (totales, promedios, variaciones) que te interesen.
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Si segmentás, mencioná rol, zona o estado para filtrar el contexto cargado.
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sky-300">•</span>
                            Si falta un dato, el agente lo indicará: evitá pedir operaciones fuera del contexto.
                        </li>
                    </ul>
                    <p class="mt-4 text-xs text-slate-200">
                        El agente no ejecuta consultas directas: solo usa la fotografía de datos recopilada antes de cada respuesta.
                    </p>
                </details>
            </section>
        </x-app.container>
    </x-layouts.app>
</div>
@endvolt
