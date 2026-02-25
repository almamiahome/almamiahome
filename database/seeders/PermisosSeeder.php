<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        /** 
         * 1) DEFINIMOS TODOS LOS PERMISOS
         *    (podés agregar/quitar sin drama)
         */

        $permisos = [
            // Pedidos
            'pedidos.crear',
            'pedidos.ver_propios',
            'pedidos.ver_equipo',
            'pedidos.ver_todos',
            'pedidos.editar_propios',
            'pedidos.editar_equipo',
            'pedidos.editar_todos',
            'pedidos.eliminar_propios',
            'pedidos.eliminar_equipo',
            'pedidos.eliminar_todos',
            'pedidos.imprimir_factura',

            // Productos y categorías
            'productos.ver',
            'productos.crear',
            'productos.editar',
            'productos.eliminar',
            'categorias.ver',
            'categorias.crear',
            'categorias.editar',
            'categorias.eliminar',

            // Red (vendedoras, líderes, coordinadoras)
            'red.ver_propias',
            'red.ver_equipo',
            'red.ver_todas',
            'red.asignar_vendedoras',
            'red.asignar_lideres',
            'red.asignar_coordinadoras',

            // Crecimiento (tus páginas: puntaje, rangos, bonos, cierres, etc.)
            'crecimiento.ver_menu',
            'crecimiento.ver_reglas_puntaje',
            'crecimiento.crear_reglas_puntaje',
            'crecimiento.editar_reglas_puntaje',
            'crecimiento.eliminar_reglas_puntaje',

            'crecimiento.ver_rangos',
            'crecimiento.crear_rangos',
            'crecimiento.editar_rangos',
            'crecimiento.eliminar_rangos',

            'crecimiento.ver_bonos_lideres',
            'crecimiento.configurar_bonos_lideres',

            'crecimiento.ver_bonos_coordinadoras',
            'crecimiento.configurar_bonos_coordinadoras',

            'crecimiento.ver_cierres_campana',
            'crecimiento.cerrar_campana',

            'crecimiento.ver_premios_liderazgo',
            'crecimiento.configurar_premios_liderazgo',
            'crecimiento.ver_metricas_liderazgo',
            'crecimiento.editar_metricas_liderazgo',
            'crecimiento.ver_repartos_compras',

            // Reportes
            'reportes.ver_ventas_propias',
            'reportes.ver_ventas_equipo',
            'reportes.ver_ventas_global',
            'reportes.ver_crecimiento_equipo',
            'reportes.ver_crecimiento_global',

            // Administración / Sistema
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'roles.gestionar',
            'sistema.configurar',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        /**
         * 2) ROLES
         */
        $vendedora    = Role::firstOrCreate(['name' => 'vendedora']);
        $lider        = Role::firstOrCreate(['name' => 'lider']);
        $coordinadora = Role::firstOrCreate(['name' => 'coordinadora']);
        $admin        = Role::firstOrCreate(['name' => 'admin']);

        /**
         * 3) ASIGNAMOS PERMISOS A CADA ROL
         */

        // VENDEDORA: solo su trabajo y lo mínimo para moverse
        $vendedora->syncPermissions([
            'pedidos.crear',
            'pedidos.ver_propios',
            'pedidos.editar_propios',
            'pedidos.eliminar_propios',
            'pedidos.imprimir_factura',

            'productos.ver',
            'categorias.ver',

            'red.ver_propias',

            'reportes.ver_ventas_propias',
        ]);

        // LÍDER: todo lo de vendedora + equipo
        $lider->syncPermissions(array_unique([
            ...$vendedora->permissions->pluck('name')->toArray(),

            'pedidos.ver_equipo',
            'pedidos.editar_equipo',
            'pedidos.eliminar_equipo',

            'red.ver_equipo',
            'red.asignar_vendedoras',

            'crecimiento.ver_menu',
            'crecimiento.ver_reglas_puntaje',
            'crecimiento.ver_rangos',
            'crecimiento.ver_premios_liderazgo',
            'crecimiento.ver_metricas_liderazgo',
            'crecimiento.ver_repartos_compras',

            'reportes.ver_ventas_equipo',
            'reportes.ver_crecimiento_equipo',
        ]));

        // COORDINADORA: todo lo de líder + gestión de líderes y cierres
        $coordinadora->syncPermissions(array_unique([
            ...$lider->permissions->pluck('name')->toArray(),

            'red.asignar_lideres',
            'red.asignar_coordinadoras',
            'red.ver_todas',

            'crecimiento.ver_bonos_lideres',
            'crecimiento.configurar_bonos_lideres',
            'crecimiento.ver_bonos_coordinadoras',
            'crecimiento.configurar_bonos_coordinadoras',
            'crecimiento.ver_cierres_campana',
            'crecimiento.cerrar_campana',
            'crecimiento.configurar_premios_liderazgo',
            'crecimiento.ver_metricas_liderazgo',
            'crecimiento.editar_metricas_liderazgo',
            'crecimiento.ver_repartos_compras',

            'reportes.ver_ventas_global',
            'reportes.ver_crecimiento_global',
        ]));

        

        // ADMIN: todo el sistema
        $admin->syncPermissions(Permission::all());
    }
}
