<?php

return [
    'fields' => [
        'direccion' => [
            'label' => 'Direccion',
            'type' => 'TextInput',
            'rules' => 'required',
        ],
        'dni' => [
            'label' => 'DNI',
            'type' => 'TextInput',
            'rules' => 'required|numeric',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp',
            'type' => 'TextInput',
            'rules' => 'required',
        ],
        'zona' => [
            'label' => 'Zona',
            'type' => 'TextInput',
            'rules' => '',
        ],
        'departamento' => [
            'label' => 'Departamento',
            'type' => 'TextInput',
            'rules' => '',
         ],
],

];