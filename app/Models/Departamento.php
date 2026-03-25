<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = ['zona_id', 'nombre', 'codigo', 'activo'];

    protected $casts = ['zona_id' => 'integer', 'activo' => 'boolean'];

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
