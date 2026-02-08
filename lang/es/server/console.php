<?php

return [
    'title' => 'Consola',
    'command' => 'Escribe un comando...',
    'command_blocked' => 'Servidor sin conexión...',
    'command_blocked_title' => 'No se puede enviar el comando cuando el servidor está desconectado',
    'open_in_admin' => 'Abrir como admin',
    'power_actions' => [
        'start' => 'Comenzar',
        'stop' => 'Detener',
        'restart' => 'Reiniciar',
        'kill' => 'Matar',
        'kill_tooltip' => 'Esto puede resultar en corrupción de datos y/o pérdida de datos!',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => 'Memoria',
        'network' => 'Red',
        'disk' => 'Almacenamiento',
        'name' => 'Nombre',
        'status' => 'Estado',
        'address' => 'Dirección',
        'unavailable' => 'No disponible',
    ],
    'status' => [
        'created' => 'Creado',
        'starting' => 'Iniciando',
        'running' => 'En ejecución',
        'restarting' => 'Reiniciando',
        'exited' => 'Finalizado',
        'paused' => 'En pausa',
        'dead' => 'Finalizado abruptamente',
        'removing' => 'Eliminando',
        'stopping' => 'Parando',
        'offline' => 'Desconectado',
        'missing' => 'Ausente',
    ],
    'websocket_error' => [
        'title' => '¡No se ha podido conectar con websocket!',
        'body' => 'Revisa la consola para más detalles.',
    ],
];
