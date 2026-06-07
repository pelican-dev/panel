<?php

return [
    'title' => 'Consola',
    'command' => 'Escribe un comando...',
    'command_blocked' => 'Servidor sin conexión...',
    'command_blocked_title' => 'No es posible enviar un comando cuando el servidor está apagado',
    'open_in_admin' => 'Abrir como admin',
    'power_actions' => [
        'start' => 'Iniciar',
        'stop' => 'Detener',
        'restart' => 'Reiniciar',
        'kill' => 'Forzar detención',
        'kill_tooltip' => '¡Esto puede provocar corrupción y/o pérdida de datos!',
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
        'stopping' => 'Deteniendo',
        'offline' => 'Apagado',
        'missing' => 'No encontrado',
    ],
    'websocket_error' => [
        'title' => '¡No se ha podido conectar con el websocket!',
        'body' => 'Revisa la consola para más detalles.',
    ],
];
