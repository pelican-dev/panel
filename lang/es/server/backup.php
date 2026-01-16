<?php

return [
    'title' => 'Copias de seguridad',
    'empty' => 'Sin copias de seguridad',
    'size' => 'Tamaño',
    'created_at' => 'Creado el',
    'status' => 'Estado',
    'is_locked' => 'Estado de bloqueo',
    'backup_status' => [
        'in_progress' => 'En curso',
        'successful' => 'Completado',
        'failed' => 'Fallido',
    ],
    'actions' => [
        'create' => [
            'title' => 'Crear copia de seguridad',
            'limit' => 'Límite de copia de seguridad alcanzado',
            'created' => ':name creado',
            'notification_success' => 'Copia de seguridad creada con éxito',
            'notification_fail' => 'Creación de copia de seguridad fallida',
            'name' => 'Nombre',
            'ignored' => 'Archivos y directorios ignorados',
            'locked' => '¿Bloqueado?',
            'lock_helper' => 'Evita que esta copia de seguridad sea eliminada hasta que se desbloquee explícitamente.',
        ],
        'lock' => [
            'lock' => 'Bloquear',
            'unlock' => 'Ve a desbloquear',
        ],
        'download' => 'Descargar',
        'rename' => [
            'title' => 'Renombrar',
            'new_name' => 'Nombre de la copia de seguridad',
            'notification_success' => 'Backup renombrado',
        ],
        'restore' => [
            'title' => 'Restaurar',
            'helper' => 'Su servidor se detendrá. No podrá controlar el estado del servidor, acceder al administrador de archivos o crear copias de seguridad adicionales hasta que este sea completado.',
            'delete_all' => '¿Borrar todos los archivos antes de restaurar la copia de seguridad?',
            'notification_started' => 'Restaurando copia de seguridad',
            'notification_success' => 'Copia de seguridad restaurada con éxito',
            'notification_fail' => 'Fallo al recuperar la copia de seguridad',
            'notification_fail_body_1' => 'Este servidor no se encuentra actualmente en un estado que permita restaurar una copia de seguridad.',
            'notification_fail_body_2' => 'Esta copia de seguridad no se puede restaurar en este momento: no se ha completado o fallado.',
        ],
        'delete' => [
            'title' => 'Eliminar copia de seguridad',
            'description' => '¿Desea eliminar :backup?',
            'notification_success' => 'Copia de seguridad eliminada',
            'notification_fail' => 'No se pudo eliminar la copia de seguridad',
            'notification_fail_body' => 'Error de conexión al nodo. Por favor, inténtelo de nuevo.',
        ],
    ],
];
