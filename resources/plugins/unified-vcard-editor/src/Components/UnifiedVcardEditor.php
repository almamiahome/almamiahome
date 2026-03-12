<?php

namespace Wave\Plugins\UnifiedVcardEditor\Components;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class UnifiedVcardEditor extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tipo_tienda' => 'tienda',
            'redes_sociales' => [
                [
                    'plataforma' => 'instagram',
                    'tipo_visualizacion' => 'icono_label',
                    'label' => 'Instagram',
                    'enlace' => '',
                ],
            ],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Formulario general')
                    ->description('Campos unificados para tipo tienda, vCard, business y custom.')
                    ->schema([
                        Forms\Components\Select::make('tipo_tienda')
                            ->label('Tipo de perfil')
                            ->options([
                                'tienda' => 'Tienda',
                                'vcard' => 'vCard',
                                'business' => 'Business',
                                'custom' => 'Custom',
                            ])
                            ->default('tienda')
                            ->live()
                            ->required(),

                        Forms\Components\TextInput::make('nombre_publico')
                            ->label('Nombre público')
                            ->required()
                            ->maxLength(120),

                        Forms\Components\TextInput::make('eslogan')
                            ->label('Eslogan')
                            ->maxLength(150),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción general')
                            ->rows(3)
                            ->maxLength(1000),

                        Forms\Components\TextInput::make('telefono_principal')
                            ->label('Teléfono principal')
                            ->tel(),

                        Forms\Components\TextInput::make('email_principal')
                            ->label('Correo principal')
                            ->email(),

                        Forms\Components\TextInput::make('sitio_web')
                            ->label('Sitio web')
                            ->url(),

                        Forms\Components\Fieldset::make('Campos de tienda')
                            ->visible(fn (Forms\Get $get): bool => in_array($get('tipo_tienda'), ['tienda', 'business']))
                            ->schema([
                                Forms\Components\TextInput::make('nombre_tienda')
                                    ->label('Nombre de la tienda')
                                    ->maxLength(120),
                                Forms\Components\TextInput::make('horario_atencion')
                                    ->label('Horario de atención')
                                    ->maxLength(120),
                                Forms\Components\TextInput::make('direccion_tienda')
                                    ->label('Dirección')
                                    ->maxLength(255),
                            ])
                            ->columns(3),

                        Forms\Components\Fieldset::make('Campos de vCard')
                            ->visible(fn (Forms\Get $get): bool => in_array($get('tipo_tienda'), ['vcard', 'business']))
                            ->schema([
                                Forms\Components\TextInput::make('nombre_contacto')
                                    ->label('Nombre de contacto')
                                    ->maxLength(80),
                                Forms\Components\TextInput::make('apellido_contacto')
                                    ->label('Apellido de contacto')
                                    ->maxLength(80),
                                Forms\Components\TextInput::make('cargo_contacto')
                                    ->label('Cargo o rol')
                                    ->maxLength(80),
                                Forms\Components\TextInput::make('empresa_contacto')
                                    ->label('Empresa')
                                    ->maxLength(120),
                            ])
                            ->columns(2),

                        Forms\Components\Fieldset::make('Campos business')
                            ->visible(fn (Forms\Get $get): bool => $get('tipo_tienda') === 'business')
                            ->schema([
                                Forms\Components\TextInput::make('identificador_fiscal')
                                    ->label('CUIT / RUC / RFC')
                                    ->maxLength(30),
                                Forms\Components\TextInput::make('whatsapp_comercial')
                                    ->label('WhatsApp comercial')
                                    ->tel(),
                                Forms\Components\Toggle::make('mostrar_mapa')
                                    ->label('Mostrar mapa en perfil')
                                    ->default(true),
                            ])
                            ->columns(3),

                        Forms\Components\Repeater::make('campos_custom')
                            ->label('Campos personalizados (Custom)')
                            ->visible(fn (Forms\Get $get): bool => $get('tipo_tienda') === 'custom')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->required(),
                                Forms\Components\TextInput::make('valor')
                                    ->label('Valor')
                                    ->required(),
                                Forms\Components\Toggle::make('visible')
                                    ->label('Visible')
                                    ->default(true),
                            ])
                            ->defaultItems(1)
                            ->collapsible()
                            ->columns(3),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Redes sociales')
                    ->description('Permite administrar las redes existentes y agregar nuevas reutilizando la misma interfaz de configuración.')
                    ->schema([
                        Forms\Components\Repeater::make('redes_sociales')
                            ->label('Redes configuradas')
                            ->schema($this->redSocialSchema())
                            ->collapsible()
                            ->defaultItems(1)
                            ->columns(4),

                        Forms\Components\Repeater::make('redes_sociales_personalizadas')
                            ->label('Agregar nuevas redes')
                            ->schema($this->redSocialSchema())
                            ->collapsible()
                            ->addActionLabel('Agregar red social')
                            ->columns(4),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * @return array<int, Forms\Components\Field>
     */
    protected function redSocialSchema(): array
    {
        return [
            Forms\Components\Select::make('plataforma')
                ->label('Plataforma')
                ->options([
                    'instagram' => 'Instagram',
                    'facebook' => 'Facebook',
                    'tiktok' => 'TikTok',
                    'youtube' => 'YouTube',
                    'linkedin' => 'LinkedIn',
                    'x' => 'X',
                    'whatsapp' => 'WhatsApp',
                    'otra' => 'Otra',
                ])
                ->required(),
            Forms\Components\Select::make('tipo_visualizacion')
                ->label('Tipo de visualización')
                ->options([
                    'icono' => 'Solo ícono',
                    'label' => 'Solo label',
                    'icono_label' => 'Ícono + label',
                    'boton' => 'Botón',
                ])
                ->default('icono_label')
                ->required(),
            Forms\Components\TextInput::make('label')
                ->label('Label')
                ->maxLength(80),
            Forms\Components\TextInput::make('enlace')
                ->label('Enlace')
                ->url()
                ->required(),
        ];
    }

    public function guardar(): void
    {
        $this->form->validate();

        session()->flash('status', 'Configuración guardada correctamente.');
    }

    public function render()
    {
        return view('unified-vcard-editor::livewire.unified-vcard-editor')
            ->layout('theme::components.layouts.app');
    }
}
