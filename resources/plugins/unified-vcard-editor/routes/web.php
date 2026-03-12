<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('unified-vcard-editor', \Wave\Plugins\UnifiedVcardEditor\Components\UnifiedVcardEditor::class)
        ->name('unified-vcard-editor');
});
