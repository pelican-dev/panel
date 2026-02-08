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
        'checkpoint' => 'Solicitud de autenticación de dos factores solicitada',
        'recovery-token' => 'Clave de recuperación de autenticación de dos factores usado',
        'token' => 'Autenticación en dos pasos resuelta',
        'ip-blocked' => 'Solicitud bloqueada de la dirección IP no listada para <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Inicio de sesión SFTP fallido',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Nombre de usuario cambiado de <b>:old</b> a <b>:new</b>',
            'email-changed' => 'Correo electrónico cambiado de <b>:old</b> a <b>:new</b>',
            'password-changed' => 'Contraseña cambiada',
        ],
        'api-key' => [
            'create' => 'Se creó una nueva clave API </b>:identifier</b>',
            'delete' => 'Se eliminó la clave API <b>:identificador</b>',
        ],
        'ssh-key' => [
            'create' => 'Se agregó la clave SSH <b>:huella</b> a la cuenta',
            'delete' => 'Se eliminó la clave SSH <b>:huella</b> a la cuenta',
        ],
        'two-factor' => [
            'create' => 'Se habilitó la autenticación de dos factores',
            'delete' => 'Se deshabilitó la autenticación de dos factores',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Se ejecutó "<b>:comando"</b>" en el servidor',
        ],
        'power' => [
            'start' => 'Se ha iniciado el servidor',
            'stop' => 'Se ha detenido el servidor',
            'restart' => 'Se ha reiniciado el servidor',
            'kill' => 'Se ha detenido el proceso del servidor',
        ],
        'backup' => [
            'download' => 'Descargada la copia de seguridad <b>:name</b>',
            'delete' => 'Eliminada la copia de seguridad: <b>:name</b> ',
            'restore' => 'Restaurada la copia de seguridad: <b>:name</b>. (Archivos eliminados: <b>:truncate</b>) ',
            'restore-complete' => 'Restaurada la copia de seguridad: <b>:name</b> ',
            'restore-failed' => 'No se pudo completar la restauración de la copia de seguridad: <b>: name</b>',
            'start' => 'Iniciada una nueva copia de seguridad <b>:name</b> ',
            'complete' => 'Marcada la copia de seguridad <b>:name</b>  como completada',
            'fail' => 'Marcada la copia de seguridad <b>:name</b>  como fallida',
            'lock' => 'La copia de seguridad <b>:name</b> ha sido bloqueada.',
            'unlock' => 'La copia de seguridad <b>:name</b> ha sido desbloqueada.',
            'rename' => 'Copia de seguridad renombrada de "<b>:old_name</b>" a "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'La base de datos  <b>:name</b> ha sido creada.',
            'rotate-password' => 'La contraseña de la base de datos  <b>:name</b> ha sido rotada.',
            'delete' => 'La base de datos  <b>:name</b> se ha eliminado.',
        ],
        'file' => [
            'compress' => 'Comprimidos <b>:directory:files</b>|Comprimidos <b>:count</b> archivos en <b>:directory</b>.',
            'read' => 'Los contenidos de <b>:file</b> han sido vistos.',
            'copy' => 'Creado una copia de <b>:file</b>',
            'create-directory' => 'Creado el directorio <b>:directory:name</b>',
            'decompress' => 'Descomprimido <b>:file</b> en <b>:directory</b>',
            'delete' => 'Eliminado <b>:directory:files</b>|Eliminado  <b>:count</b> archivos en <b>:directory</b>',
            'download' => 'Descargado <b>:file</b>',
            'pull' => 'Descargado un archivo remoto desde <b>:url</b> a <b>:directory</b>',
            'rename' => 'Movido/ Renombrado <b>:from</b> a <b>:to</b> | Movidos/ Renombrados <b>:count</b> archivos en <b>:directory</b>',
            'write' => 'Escrito nuevo contenido en <b>:file</b>',
            'upload' => 'Iniciada la subida de un archivo',
            'uploaded' => 'Subido <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Acceso SFTP bloqueado debido a los permisos',
            'create' => 'Creado <b>:files</b>|Creado <b>:count</b> archivos nuevos',
            'write' => 'Modificados los contenidos de <b>:files</b>| Modificados los contenidos de <b>:count</b> archivos',
            'delete' => 'Eliminado <b>:files</b>|Eliminados <b>:count</b> archivos',
            'create-directory' => 'Creado el directorio <b>:files</b> | Creados <b>:count</b> directorios',
            'rename' => 'Renombrado <b>:from</b> a <b>:to</b>|Renombrados o movidos <b>:count</b> archivos',
        ],
        'allocation' => [
            'create' => 'Añadido <b>:allocation</b> al servidor',
            'notes' => 'Actualizadas las notas para <b>:allocation</b> de "<b>:old</b>" a "<b>:new</b>"',
            'primary' => 'Establecida <b>:allocation</b> como la asignación primaria del servidor',
            'delete' => 'Eliminada la asignación <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'Creado el horario <b>:name</b>',
            'update' => 'Actualizado el horario <b>:name</b>',
            'execute' => 'Ejecutó manualmente el horario <b>:name</b>',
            'delete' => 'Eliminado el horario <b>:name</b>',
        ],
        'task' => [
            'create' => 'Creada una nueva tarea "<b>:action</b>" para el horario <b>:name</b>',
            'update' => 'Actualizada la tarea"<b>:action</b>" para el horario <b>:name</b>',
            'delete' => 'Se eliminó la tarea "<b>:action</b>" del horario <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Renombrado el servidor de "<b>:old</b>" a "<b>:new</b>"',
            'description' => 'Cambiada la descripción del servidor de "<b>:old</b>" a "<b>:new</b>"',
            'reinstall' => 'Servidor reinstalado',
        ],
        'startup' => [
            'edit' => 'Cambiada la variable <b>:variable</b> de "<b>:old</b>" a "<b>:new</b>"',
            'image' => 'Actualizada la Imagen Docker del el servidor de <b>:old</b> a <b>:new</b>',
            'command' => 'Se actualizó el comando de inicio del servidor de <b>:old</b> a <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Añadido <b>:email</b> como subusuario',
            'update' => 'Actualizados los permisos del subusuario <b>:email</b>',
            'delete' => 'Eliminado <b>:email</b> como subusuario',
        ],
        'crashed' => 'Servidor caído',
    ],
];
