<?php

namespace Wave\Plugins\UnifiedVcardEditor;

use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Wave\Plugins\Plugin;

class UnifiedVcardEditorPlugin extends Plugin
{
    protected $name = 'Editor unificado de vCard';

    protected $description = 'Formulario general para tiendas, vCard y perfiles de negocio con redes sociales dinámicas.';

    public function register()
    {
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'unified-vcard-editor');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        Livewire::component(
            'unified-vcard-editor',
            \Wave\Plugins\UnifiedVcardEditor\Components\UnifiedVcardEditor::class
        );
    }

    public function getPluginInfo(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->getPluginVersion(),
        ];
    }

    public function getPluginVersion(): array
    {
        return File::json(__DIR__.'/version.json');
    }
}
