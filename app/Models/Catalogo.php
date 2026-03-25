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

    public function rachasRevendedoras(): HasMany
    {
        return $this->hasMany(RevendedoraRacha::class);
    }

    public function puntosRevendedoras(): HasMany
    {
        return $this->hasMany(RevendedoraPunto::class);
    }

    public function premiosTienda(): HasMany
    {
        return $this->hasMany(TiendaPremio::class);
    }

    public function canjesPremios(): HasMany
    {
        return $this->hasMany(CanjePremio::class);
    }
}
