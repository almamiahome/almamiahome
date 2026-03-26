<?php

namespace App\Models;

use App\Models\Cobro;
use App\Models\MetricaLiderCampana;
use App\Models\Pago;
use App\Models\PremioLiderCierre;
use App\Models\Producto;
use App\Models\RangoLider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Wave\Traits\HasProfileKeyValues;
use Wave\User as WaveUser;

class User extends WaveUser
{
    use HasFactory, HasProfileKeyValues, Notifiable;

    public $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'password',
        'role_id',
        'verification_code',
        'verified',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        // Listen for the creating event of the model
        static::creating(function ($user) {
            // Check if the username attribute is empty
            if (empty($user->username)) {
                // Use the name to generate a slugified username
                $username = Str::slug($user->name, '');
                $i = 1;
                while (self::where('username', $username)->exists()) {
                    $username = Str::slug($user->name, '').$i;
                    $i++;
                }
                $user->username = $username;
            }
        });

        // Listen for the created event of the model
        static::created(function ($user) {
            // Remove all roles
            $user->syncRoles([]);
            // Assign the default role
            $user->assignRole(config('wave.default_user_role', 'registered'));
        });
    }
    
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function lideres(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'coordinadora_lider',
            'coordinadora_id',
            'lider_id'
        )->withTimestamps();
    }

    public function coordinadoras(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'coordinadora_lider',
            'lider_id',
            'coordinadora_id'
        )->withTimestamps();
    }

    public function metricasCampanas(): HasMany
    {
        return $this->hasMany(MetricaLiderCampana::class, 'lider_id');
    }

    public function metricasComoRevendedora(): HasMany
    {
        return $this->hasMany(MetricaLiderCampana::class, 'revendedora_id');
    }

    public function rangosLiderados(): BelongsToMany
    {
        return $this->belongsToMany(RangoLider::class, 'metricas_lider_campana', 'lider_id', 'rango_lider_id')
            ->withPivot([
                'cierre_campana_id',
                'actividad_ok',
                'altas_ok',
                'unidades_ok',
                'cobranzas_ok',
                'crecimiento_ok',
                'altas_pagadas_en_cierre',
                'cantidad_1c',
                'cantidad_2c',
                'cantidad_3c',
                'monto_reparto_total',
                'premio_actividad',
                'premio_unidades',
                'premio_cobranzas',
                'premio_altas',
                'premio_crecimiento',
                'premio_total',
                'fecha_pago_equipo',
            ])
            ->withTimestamps();
    }

    public function pagosRegistrados(): HasMany
    {
        return $this->hasMany(Pago::class, 'vendedora_id');
    }

    public function cobrosComoLider(): HasMany
    {
        return $this->hasMany(Cobro::class, 'lider_id');
    }

    public function cobrosComoCoordinadora(): HasMany
    {
        return $this->hasMany(Cobro::class, 'coordinadora_id');
    }

    public function premiosLiderCierre(): HasMany
    {
        return $this->hasMany(PremioLiderCierre::class, 'lider_id');
    }
    
    
    public function needsOnboarding(): bool
    {
        $dni = (string) $this->profile('dni');
        $zona = (string) $this->profile('zona');
        if ($this->hasAnyRole(['admin', 'coordinadora'])) {
            return false;
        }
        $currentRole = (string) $this->roles()->pluck('name')->first();
        $role = in_array($currentRole, ['vendedora', 'lider'], true) ? $currentRole : '';

        $missingLider = $role === 'vendedora' && blank($this->lider_id);
        $missingCoordinadora = $role === 'lider' && blank($this->coordinadora_id);

        return blank($this->name)
            || blank($dni)
            || blank($zona)
            || blank($role)
            || $missingLider
            || $missingCoordinadora;
    }
    
}
