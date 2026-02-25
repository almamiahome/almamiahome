<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosBultoSeeder extends Seeder
{
    public function run(): void
    {
        // Setear bulto = 1.5 en todos los productos
        DB::table('productos')->update([
            'bulto' => 1.5,
        ]);
    }
}
