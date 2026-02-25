<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlmamiaCantidadBultoSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'almamia.cantidad.bulto'],
            [
                'display_name' => 'Cantidad máxima de bulto por rótulo',
                'value'        => '9',
                'type'         => 'text',
                'group'        => 'Almamia',
                'order'        => 21,
                'details'      => null,
            ]
        );
    }
}
