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
        'password-reset' => 'Restablecer contraseña',
        'checkpoint' => 'Autenticación de dos factores solicitada',
        'recovery-token' => 'Token de recuperación de dos factores utilizado',
        'token' => 'Autenticación en dos pasos resuelta',
        'ip-blocked' => 'Solicitud bloqueada de la dirección IP no listada para <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Inicio de sesión SFTP fallido',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Nombre de usuario cambiado de <b>:old</b> a <b>:new</b>',
            'email-changed' => 'Correo cambiado de <b>:old</b> a <b>:new</b>',
            'password-changed' => 'Contraseña cambiada',
        ],
        'api-key' => [
            'create' => 'Nueva clave API <b>:identifier</b> creada',
            'delete' => 'Clave API <b>:identifier</b> eliminada',
        ],
        'ssh-key' => [
            'create' => 'Clave SSH <b>:fingerprint</b> añadida a la cuenta',
            'delete' => 'Clave SSH <b>:fingerprint</b> eliminada de la cuenta',
        ],
        'two-factor' => [
            'create' => 'Se habilitó la autenticación de dos factores',
            'delete' => 'Se deshabilitó la autenticación de dos factores',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Ejecutado "<b>:command</b>" en el servidor',
        ],
        'power' => [
            'start' => 'Se ha iniciado el servidor',
            'stop' => 'Se ha detenido el servidor',
            'restart' => 'Se ha reiniciado el servidor',
            'kill' => 'Se ha forzado la detención del proceso del servidor',
        ],
        'backup' => [
            'download' => 'Descargada la copia de seguridad <b>:name</b>',
            'delete' => 'Copia de seguridad <b>:name</b> eliminada',
            'restore' => 'Copia de seguridad <b>:name</b> restaurada (archivos eliminados: <b>:truncate</b>)',
            'restore-complete' => 'Restauración de la copia de seguridad <b>:name</b> completada',
            'restore-failed' => 'No se pudo completar la restauración de la copia de seguridad <b>:name</b>',
            'start' => 'Nueva copia de seguridad <b>:name</b> iniciada',
            'complete' => 'Copia de seguridad <b>:name</b> marcada como completada',
            'fail' => 'Copia de seguridad <b>:name</b> marcada como fallida',
            'lock' => 'La copia de seguridad <b>:name</b> ha sido bloqueada.',
            'unlock' => 'La copia de seguridad <b>:name</b> ha sido desbloqueada.',
            'rename' => 'Copia de seguridad renombrada de "<b>:old_name</b>" a "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Base de datos <b>:name</b> creada',
            'rotate-password' => 'Contraseña de la base de datos <b>:name</b> renovada',
            'delete' => 'Base de datos <b>:name</b> eliminada',
        ],
        'file' => [
            'compress' => 'Comprimidos <b>:directory:files</b>|Comprimidos <b>:count</b> archivos en <b>:directory</b>.',
            'read' => 'Contenido de <b>:file</b> visualizado',
            'copy' => 'Creada una copia de <b>:file</b>',
            'create-directory' => 'Creado el directorio <b>:directory:name</b>',
            'decompress' => 'Descomprimido <b>:file</b> en <b>:directory</b>',
            'delete' => 'Eliminado <b>:directory:files</b>|Eliminados <b>:count</b> archivos en <b>:directory</b>',
            'download' => 'Descargado <b>:file</b>',
            'pull' => 'Descargado un archivo remoto desde <b>:url</b> a <b>:directory</b>',
            'rename' => 'Movido/ Renombrado <b>:from</b> a <b>:to</b> | Movidos/ Renombrados <b>:count</b> archivos en <b>:directory</b>',
            'write' => 'Escrito nuevo contenido en <b>:file</b>',
            'upload' => 'Iniciada la subida de un archivo',
            'uploaded' => 'Subido <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Acceso SFTP bloqueado debido a los permisos',
            'create' => 'Creados <b>:files</b> | Creados <b>:count</b> archivos nuevos',
            'write' => 'Modificados los contenidos de <b>:files</b>| Modificados los contenidos de <b>:count</b> archivos',
            'delete' => 'Eliminado <b>:files</b>|Eliminados <b>:count</b> archivos',
            'create-directory' => 'Creado el directorio <b>:files</b> | Creados <b>:count</b> directorios',
            'rename' => 'Renombrado <b>:from</b> a <b>:to</b>|Renombrados o movidos <b>:count</b> archivos',
        ],
        'allocation' => [
            'create' => 'Añadido <b>:allocation</b> al servidor',
            'notes' => 'Actualizadas las notas para <b>:allocation</b> de "<b>:old</b>" a "<b>:new</b>"',
            'primary' => 'Establecida <b>:allocation</b> como la asignación principal del servidor',
            'delete' => 'Asignación <b>:allocation</b> eliminada',
        ],
        'schedule' => [
            'create' => 'Tarea programada <b>:name</b> creada',
            'update' => 'Tarea programada <b>:name</b> actualizada',
            'execute' => 'Tarea programada <b>:name</b> ejecutada manualmente',
            'delete' => 'Tarea programada <b>:name</b> eliminada',
        ],
        'task' => [
            'create' => 'Nueva tarea "<b>:action</b>" creada para la programación <b>:name</b>',
            'update' => 'Tarea "<b>:action</b>" de la programación <b>:name</b> actualizada',
            'delete' => 'Tarea "<b>:action</b>" de la programación <b>:name</b> eliminada',
        ],
        'settings' => [
            'rename' => 'Renombrado el servidor de "<b>:old</b>" a "<b>:new</b>"',
            'description' => 'Cambiada la descripción del servidor de "<b>:old</b>" a "<b>:new</b>"',
            'reinstall' => 'Servidor reinstalado',
        ],
        'startup' => [
            'edit' => 'Cambiada la variable <b>:variable</b> de "<b>:old</b>" a "<b>:new</b>"',
            'image' => 'Imagen Docker del servidor actualizada de <b>:old</b> a <b>:new</b>',
            'command' => 'Se actualizó el comando de inicio del servidor de <b>:old</b> a <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Añadido <b>:email</b> como subusuario',
            'update' => 'Actualizados los permisos del subusuario <b>:email</b>',
            'delete' => 'Eliminado <b>:email</b> como subusuario',
        ],
        'mount' => [
            'update' => 'Montajes del servidor actualizados',
        ],
        'crashed' => 'El servidor ha fallado',
    ],
];
