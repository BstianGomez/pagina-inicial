<?php

return [
    'paths' => [
        resource_path('views'),
        base_path('Paginas/ApOC/AplicacionOC-main/resources/views'),
        base_path('Paginas/AprendicionGastos/rendicionesGastos-main/resources/views'),
        base_path('Paginas/Apviajes/APviajes-main/resources/views'),
    ],
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
];
