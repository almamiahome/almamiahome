<?php

use App\Models\Pedido;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('permite guardar estado y comprobante de pago en pedidos', function () {
    $pedido = Pedido::create([
        'codigo_pedido' => 'PED-TEST-00001',
        'estado' => 'Nuevo',
    ]);

    $pedido->update([
        'estado_pago' => 'pendiente_verificacion_lider',
        'comprobante_pago_path' => 'comprobantes/pedidos/demo.pdf',
        'comprobante_pago_subido_en' => now(),
    ]);

    expect($pedido->fresh())
        ->estado_pago->toBe('pendiente_verificacion_lider')
        ->comprobante_pago_path->toBe('comprobantes/pedidos/demo.pdf')
        ->comprobante_pago_subido_en->not->toBeNull();
});
