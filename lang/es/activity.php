<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Inicio de sesión fallido',
        'success' => 'Sesión iniciada',
        'password-reset' => 'Contraseña restablecida',
        'checkpoint' => 'Autenticación de dos factores solicitada',
        'recovery-token' => 'Token de recuperación de dos factores utilizado',
        'token' => 'Desafío de dos factores resuelto',
        'ip-blocked' => 'Solicitud bloqueada desde una dirección IP no listada para <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Inicio de sesión SFTP fallido',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Correo electrónico cambiado de <b>:old</b> a <b>:new</b>',
            'password-changed' => 'Contraseña cambiada',
        ],
        'api-key' => [
            'create' => 'Clave API nueva creada <b>:identifier</b>',
            'delete' => 'Clave API eliminada <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Clave SSH añadida <b>:fingerprint</b> a la cuenta',
            'delete' => 'Clave SSH eliminada <b>:fingerprint</b> de la cuenta',
        ],
        'two-factor' => [
            'create' => 'Autenticación de dos factores habilitada',
            'delete' => 'Autenticación de dos factores deshabilitada',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Ejecutado "<b>:command</b>" en el servidor',
        ],
        'power' => [
            'start' => 'Servidor iniciado',
            'stop' => 'Servidor detenido',
            'restart' => 'Servidor reiniciado',
            'kill' => 'Proceso del servidor terminado',
        ],
        'backup' => [
            'download' => 'Copia de seguridad <b>:name</b> descargada',
            'delete' => 'Copia de seguridad <b>:name</b> eliminada',
            'restore' => 'Copia de seguridad <b>:name</b> restaurada (archivos eliminados: <b>:truncate</b>)',
            'restore-complete' => 'Restauración de la copia de seguridad <b>:name</b> completada',
            'restore-failed' => 'Error al completar la restauración de la copia de seguridad <b>:name</b>',
            'start' => 'Copia de seguridad nueva iniciada <b>:name</b>',
            'complete' => 'Copia de seguridad <b>:name</b> marcada como completa',
            'fail' => 'Copia de seguridad <b>:name</b> marcada como fallida',
            'lock' => 'Copia de seguridad <b>:name</b> bloqueada',
            'unlock' => 'Copia de seguridad <b>:name</b> desbloqueada',
        ],
        'database' => [
            'create' => 'Base de datos nueva creada <b>:name</b>',
            'rotate-password' => 'Contraseña rotada para la base de datos <b>:name</b>',
            'delete' => 'Base de datos <b>:name</b> eliminada',
        ],
        'file' => [
            'compress' => '<b>:directory:files</b> comprimidos|<b>:count</b> archivos comprimidos en <b>:directory</b>',
            'read' => 'Contenido de <b>:file</b> visto',
            'copy' => 'Copia de <b>:file</b> creada',
            'create-directory' => 'Directorio <b>:directory:name</b> creado',
            'decompress' => '<b>:file</b> descomprimido en <b>:directory</b>',
            'delete' => '<b>:directory:files</b> eliminados|<b>:count</b> archivos eliminados en <b>:directory</b>',
            'download' => '<b>:file</b> descargado',
            'pull' => 'Archivo remoto descargado de <b>:url</b> a <b>:directory</b>',
            'rename' => '<b>:from</b> movido/renombrado a <b>:to</b>|<b>:count</b> archivos movidos/renombrados en <b>:directory</b>',
            'write' => 'Contenido nuevo escrito en <b>:file</b>',
            'upload' => 'Carga de archivo iniciada',
            'uploaded' => '<b>:directory:file</b> cargado',
        ],
        'sftp' => [
            'denied' => 'Acceso SFTP bloqueado debido a permisos',
            'create' => '<b>:files</b> creados|<b>:count</b> archivos nuevos creados',
            'write' => 'Contenido de <b>:files</b> modificado|Contenido de <b>:count</b> archivos modificado',
            'delete' => '<b>:files</b> eliminados|<b>:count</b> archivos eliminados',
            'create-directory' => 'Directorio <b>:files</b> creado|<b>:count</b> directorios creados',
            'rename' => '<b>:from</b> renombrado a <b>:to</b>|<b>:count</b> archivos renombrados o movidos',
        ],
        'allocation' => [
            'create' => '<b>:allocation</b> añadido al servidor',
            'notes' => 'Notas para <b>:allocation</b> actualizadas de "<b>:old</b>" a "<b>:new</b>"',
            'primary' => '<b>:allocation</b> establecido como asignación de servidor principal',
            'delete' => 'Asignación <b>:allocation</b> eliminada',
        ],
        'schedule' => [
            'create' => 'Horario <b>:name</b> creado',
            'update' => 'Horario <b>:name</b> actualizado',
            'execute' => 'Horario <b>:name</b> ejecutado manualmente',
            'delete' => 'Horario <b>:name</b> eliminado',
        ],
        'task' => [
            'create' => 'Tarea "<b>:action</b>" nueva creada para el horario <b>:name</b>',
            'update' => 'Tarea "<b>:action</b>" actualizada para el horario <b>:name</b>',
            'delete' => 'Tarea "<b>:action</b>" eliminada para el horario <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Servidor renombrado de "<b>:old</b>" a "<b>:new</b>"',
            'description' => 'Descripción del servidor cambiada de "<b>:old</b>" a "<b>:new</b>"',
            'reinstall' => 'Servidor reinstalado',
        ],
        'startup' => [
            'edit' => 'Variable <b>:variable</b> cambiada de "<b>:old</b>" a "<b>:new</b>"',
            'image' => 'Imagen de Docker para el servidor actualizada de <b>:old</b> a <b>:new</b>',
        ],
        'subuser' => [
            'create' => '<b>:email</b> añadido como subusuario',
            'update' => 'Permisos de subusuario actualizados para <b>:email</b>',
            'delete' => '<b>:email</b> eliminado como subusuario',
        ],
        'crashed' => 'Servidor falló',
    ],
];
