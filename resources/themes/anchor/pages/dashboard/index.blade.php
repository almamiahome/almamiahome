<?php

use function Laravel\Folio\{middleware, name};

middleware('auth');
name('dashboard');
?>

<x-layouts.app>
    @include('themes.anchor.pages.partials.panel-dashboard-unificado')
</x-layouts.app>
