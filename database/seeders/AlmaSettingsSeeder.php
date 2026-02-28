<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlmaSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'almamia.info.mision' => [
                'display_name' => 'Misión de Almamia',
                'value' => 'Inspirar a la red de embajadoras con fragancias artesanales y oportunidades de negocio sustentables.',
                'type' => 'text',
                'order' => 1,
            ],
            'almamia.info.vision' => [
                'display_name' => 'Visión de Almamia',
                'value' => 'Ser la comunidad de cosmética mendocina más confiable de Argentina.',
                'type' => 'text',
                'order' => 2,
            ],
            'almamia.info.valores' => [
                'display_name' => 'Valores institucionales',
                'value' => json_encode([
                    'transparencia',
                    'cuidado del ambiente',
                    'innovación en fragancias',
                    'acompañamiento comercial',
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 3,
            ],
            'almamia.contacto.correo' => [
                'display_name' => 'Correo de contacto general',
                'value' => 'hola@almamia.com.ar',
                'type' => 'text',
                'order' => 4,
            ],
            'almamia.contacto.whatsapp' => [
                'display_name' => 'WhatsApp oficial',
                'value' => '+54 9 261 555 4321',
                'type' => 'text',
                'order' => 5,
            ],
            'almamia.logistica.horarios' => [
                'display_name' => 'Horarios del centro logístico',
                'value' => json_encode([
                    'lunes' => '09:00 - 18:00',
                    'martes' => '09:00 - 18:00',
                    'miercoles' => '09:00 - 18:00',
                    'jueves' => '09:00 - 18:00',
                    'viernes' => '09:00 - 18:00',
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 6,
            ],
            'almamia.logistica.tiempos_estimados' => [
                'display_name' => 'Tiempos estimados de entrega',
                'value' => json_encode([
                    'mendoza' => '24 a 48 hs',
                    'cuyo' => '3 a 5 días',
                    'resto_del_pais' => '5 a 7 días',
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 7,
            ],
            'almamia.campanias.vigente' => [
                'display_name' => 'Campaña vigente',
                'value' => 'Campaña Primavera Aromática',
                'type' => 'text',
                'order' => 8,
            ],
            'almamia.campanias.objetivos' => [
                'display_name' => 'Objetivos comerciales',
                'value' => json_encode([
                    'capacitaciones_virtuales' => '2 sesiones mensuales para nuevas embajadoras',
                    'ventas_minimas' => '12 unidades promedio por embajadora',
                    'nuevas_lideres' => 5,
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 9,
            ],
            'almamia.localidades.mendoza' => [
                'display_name' => 'Localidades de Mendoza',
                'value' => json_encode([
                    'Ciudad de Mendoza',
                    'Godoy Cruz',
                    'Guaymallén',
                    'Las Heras',
                    'Luján de Cuyo',
                    'Maipú',
                    'San Martín',
                    'Rivadavia',
                    'Junín',
                    'Santa Rosa',
                    'La Paz',
                    'Lavalle',
                    'Tunuyán',
                    'Tupungato',
                    'San Carlos',
                    'General Alvear',
                    'San Rafael',
                    'Malargüe',
                    'Uspallata',
                    'Potrerillos',
                    'Chacras de Coria',
                    'Vista Flores',
                    'La Consulta',
                    'Bowen',
                    'Monte Comán',
                    'El Nihuil',
                    'Real del Padre',
                    'La Dormida',
                    'Las Catitas',
                    'Costa de Araujo',
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 10,
            ],
            'almamia.departamentos.mendoza' => [
                'display_name' => 'Departamentos de Mendoza',
                'value' => json_encode([
                    'Capital',
                    'Godoy Cruz',
                    'Guaymallén',
                    'Las Heras',
                    'Lavalle',
                    'Maipú',
                    'Luján de Cuyo',
                    'Tunuyán',
                    'Tupungato',
                    'San Carlos',
                    'San Martín',
                    'Rivadavia',
                    'Junín',
                    'Santa Rosa',
                    'La Paz',
                    'San Rafael',
                    'General Alvear',
                    'Malargüe',
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 11,
            ],
            'almamia.soporte.documentos' => [
                'display_name' => 'Documentación clave',
                'value' => json_encode([
                    'politica_de_cambios' => 'https://almamia.com.ar/politica-de-cambios.pdf',
                    'condiciones_comerciales' => 'https://almamia.com.ar/condiciones-comerciales.pdf',
                    'manual_embajadoras' => 'https://almamia.com.ar/manual-embajadoras.pdf',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 12,
            ],
            'almamia.soporte.canales' => [
                'display_name' => 'Canales de soporte',
                'value' => json_encode([
                    'email' => 'soporte@almamia.com.ar',
                    'telefono' => '0800-123-4567',
                    'slack' => '#almamia-soporte',
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 13,
            ],
            'almamia.eventos.recurrentes' => [
                'display_name' => 'Eventos recurrentes',
                'value' => json_encode([
                    'lanzamiento_catalogo' => 'Primer lunes de cada mes',
                    'cierres_pedidos' => 'Viernes 12:00 hs',
                    'capacitacion_lideres' => 'Miércoles 18:30 hs',
                ], JSON_UNESCAPED_UNICODE),
                'type' => 'code',
                'order' => 14,
            ],
        ];

        foreach ($settings as $key => $data) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'display_name' => $data['display_name'],
                    'value' => $data['value'],
                    'details' => $data['details'] ?? null,
                    'type' => $data['type'],
                    'order' => $data['order'],
                    'group' => $data['group'] ?? 'Almamia',
                ]
            );
        }
    }
}