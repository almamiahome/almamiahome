<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GastosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gastos_administrativos')->insert([
            [
                'id' => 1,
                'concepto' => 'POP',
                'monto' => 2000,
                'tipo' => 'Administrativo',
                'created_at' => '2025-11-04 13:49:43',
                'updated_at' => '2025-11-04 13:50:30',
            ],
            [
                'id' => 2,
                'concepto' => 'Envio',
                'monto' => 1190,
                'tipo' => 'Envio',
                'created_at' => '2025-11-04 13:50:18',
                'updated_at' => '2025-11-04 13:50:18',
            ],
        ]);
    }
}
