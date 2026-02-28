<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoAdministrativo extends Model
{
    use HasFactory;

    protected $table = 'gastos_administrativos';

    protected $fillable = [
        'concepto',
        'monto',
        'tipo',
    ];
}
