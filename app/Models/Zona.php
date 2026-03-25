<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zona extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'codigo', 'activa'];

    protected $casts = ['activa' => 'boolean'];

    public function departamentos(): HasMany
    {
        return $this->hasMany(Departamento::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
