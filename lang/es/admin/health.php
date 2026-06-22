<?php

return [
    'title' => 'Estado',
    'results_refreshed' => 'Resultados de comprobación de estado actualizados',
    'checked' => 'Resultados comprobados hace :time',
    'refresh' => 'Actualizar',
    'results' => [
        'cache' => [
            'label' => 'Caché',
            'ok' => 'Ok',
            'failed_retrieve' => 'No se pudo establecer o recuperar un valor de la caché de la aplicación.',
            'failed' => 'Se ha producido una excepción con el caché de la aplicación: :error',
        ],
        'database' => [
            'label' => 'Base de datos',
            'ok' => 'Ok',
            'failed' => 'No se pudo conectar a la base de datos: :error',
        ],
        'debugmode' => [
            'label' => 'Modo de depuración',
            'ok' => 'El modo de depuración está desactivado',
            'failed' => 'Se esperaba que el modo de depuración fuera :expected, pero en realidad era :actual',
        ],
        'environment' => [
            'label' => 'Entorno',
            'ok' => 'Ok, establecido a :actual',
            'failed' => 'El entorno está configurado como :actual; se esperaba :expected.',
        ],
        'nodeversions' => [
            'label' => 'Versiones del nodo',
            'ok' => 'Los nodos están actualizados',
            'failed' => ':outdated/:all nodos están desactualizados',
            'no_nodes_created' => 'No hay nodos creados',
            'no_nodes' => 'No hay nodos',
            'all_up_to_date' => 'Todo actualizado',
            'outdated' => ':outdated/:all desactualizados',
        ],
        'panelversion' => [
            'label' => 'Versión del panel',
            'ok' => 'El panel está actualizado',
            'failed' => 'La versión instalada es :currentVersion pero la última es :latestVersion',
            'up_to_date' => 'Actualizado',
            'outdated' => 'Desactualizado',
        ],
        'schedule' => [
            'label' => 'Schedule',
            'ok' => 'Ok',
            'failed_last_ran' => 'La última ejecución de la tarea programada fue hace más de :time minutos',
            'failed_not_ran' => 'La tarea programada aún no se ha ejecutado.',
        ],
        'useddiskspace' => [
            'label' => 'Espacio en disco',
        ],
    ],
    'checks' => [
        'successful' => 'Completado',
        'failed' => ':checks fallidos',
    ],
];
