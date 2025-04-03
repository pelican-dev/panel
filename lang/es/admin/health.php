<?php

return [
    'title' => 'Salud',
    'results_refreshed' => 'Resultados de la verificación de salud actualizados',
    'checked' => 'Resultados verificados de :time',
    'refresh' => 'Actualizar',
    'results' => [
        'cache' => [
            'label' => 'Caché',
            'ok' => 'Ok',
            'failed_retrieve' => 'No se pudo establecer o recuperar un valor de caché de la aplicación.',
            'failed' => 'Ocurrió una excepción con la caché de la aplicación: :error',
        ],
        'database' => [
            'label' => 'Base de Datos',
            'ok' => 'Ok',
            'failed' => 'No se pudo conectar a la base de datos: :error',
        ],
        'debugmode' => [
            'label' => 'Modo Depuración',
            'ok' => 'El modo de depuración está desactivado',
            'failed' => 'Se esperaba que el modo de depuración fuera :expected, pero en realidad fue :actual',
        ],
        'environment' => [
            'label' => 'Entorno',
            'ok' => 'Ok, Establecido en: :actual',
            'failed' => 'El entorno está establecido en :actual, Se esperaba :expected',
        ],
        'nodeversions' => [
            'label' => 'Versiones de Nodo',
            'ok' => 'Los nodos están actualizados',
            'failed' => ':outdated/:all Nodos están desactualizados',
            'no_nodes_created' => 'No se han creado nodos',
            'no_nodes' => 'Sin nodos',
            'all_up_to_date' => 'Todos actualizados',
            'outdated' => ':outdated/:all desactualizados',
        ],
        'panelversion' => [
            'label' => 'Versión del Panel',
            'ok' => 'El panel está actualizado',
            'failed' => 'La versión instalada es :currentVersion pero la última es :latestVersion',
            'up_to_date' => 'Actualizado',
            'outdated' => 'Desactualizado',
        ],
        'schedule' => [
            'label' => 'Programación',
            'ok' => 'Ok',
            'failed_last_ran' => 'La última ejecución de la programación fue hace más de :time minutos',
            'failed_not_ran' => 'La programación aún no se ha ejecutado.',
        ],
        'useddiskspace' => [
            'label' => 'Espacio en Disco',
        ],
    ],
    'checks' => [
        'successful' => 'Exitoso',
        'failed' => 'Fallido',
    ],
];
