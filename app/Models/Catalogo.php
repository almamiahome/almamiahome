<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catalogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen_portada',
        'descripcion',
        'anio',
        'numero',
    ];

    protected $casts = [
        'anio' => 'integer',
        'numero' => 'integer',
    ];

    public function paginas(): HasMany
    {
        return $this->hasMany(CatalogoPagina::class);
    }

    public function cierres(): HasMany
    {
        return $this->hasMany(CierreCampana::class);
    }
}
