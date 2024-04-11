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
        'password-reset' => 'Restablecimiento de contraseña',
        'reset-password' => 'Solicitud de restablecimiento de contraseña',
        'checkpoint' => 'Solicitud de autenticación de dos factores',
        'recovery-token' => 'Token de recuperación de dos factores utilizado',
        'token' => 'Resuelto desafío de dos factores',
        'ip-blocked' => 'Solicitud bloqueada desde la dirección IP no listada para :identifier',
        'sftp' => [
            'fail' => 'Inicio de sesión SFTP fallido',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Cambio de correo electrónico de :old a :new',
            'password-changed' => 'Contraseña cambiada',
        ],
        'api-key' => [
            'create' => 'Se creó una nueva clave API :identifier',
            'delete' => 'Se eliminó la clave API :identifier',
        ],
        'ssh-key' => [
            'create' => 'Se agregó la clave SSH :fingerprint a la cuenta',
            'delete' => 'Se eliminó la clave SSH :fingerprint de la cuenta',
        ],
        'two-factor' => [
            'create' => 'Se habilitó la autenticación de dos factores',
            'delete' => 'Se deshabilitó la autenticación de dos factores',
        ],
    ],
    'server' => [
        'reinstall' => 'Servidor reinstalado',
        'console' => [
            'command' => 'Ejecutado ":command" en el servidor',
        ],
        'power' => [
            'start' => 'Iniciado el servidor',
            'stop' => 'Detenido el servidor',
            'restart' => 'Reiniciado el servidor',
            'kill' => 'Finalizado el proceso del servidor',
        ],
        'backup' => [
            'download' => 'Descargada la copia de seguridad :name',
            'delete' => 'Eliminada la copia de seguridad :name',
            'restore' => 'Restaurada la copia de seguridad :name (archivos eliminados: :truncate)',
            'restore-complete' => 'Restauración completa de la copia de seguridad :name',
            'restore-failed' => 'Falló la restauración de la copia de seguridad :name',
            'start' => 'Iniciada una nueva copia de seguridad :name',
            'complete' => 'Marcada la copia de seguridad :name como completada',
            'fail' => 'Marcada la copia de seguridad :name como fallida',
            'lock' => 'Bloqueada la copia de seguridad :name',
            'unlock' => 'Desbloqueada la copia de seguridad :name',
        ],
        'database' => [
            'create' => 'Creada nueva base de datos :name',
            'rotate-password' => 'Contraseña rotada para la base de datos :name',
            'delete' => 'Eliminada la base de datos :name',
        ],
        'file' => [
            'compress_one' => 'Comprimido :directory:file',
            'compress_other' => 'Comprimidos :count archivos en :directory',
            'read' => 'Visto el contenido de :file',
            'copy' => 'Creada una copia de :file',
            'create-directory' => 'Creado directorio :directory:name',
            'decompress' => 'Descomprimidos :files en :directory',
            'delete_one' => 'Eliminado :directory:files.0',
            'delete_other' => 'Eliminados :count archivos en :directory',
            'download' => 'Descargado :file',
            'pull' => 'Descargado un archivo remoto desde :url a :directory',
            'rename_one' => 'Renombrado :directory:files.0.from a :directory:files.0.to',
            'rename_other' => 'Renombrados :count archivos en :directory',
            'write' => 'Escrito nuevo contenido en :file',
            'upload' => 'Iniciada una carga de archivo',
            'uploaded' => 'Cargado :directory:file',
        ],
        'sftp' => [
            'denied' => 'Acceso SFTP bloqueado debido a permisos',
            'create_one' => 'Creado :files.0',
            'create_other' => 'Creados :count nuevos archivos',
            'write_one' => 'Modificado el contenido de :files.0',
            'write_other' => 'Modificado el contenido de :count archivos',
            'delete_one' => 'Eliminado :files.0',
            'delete_other' => 'Eliminados :count archivos',
            'create-directory_one' => 'Creado el directorio :files.0',
            'create-directory_other' => 'Creados :count directorios',
            'rename_one' => 'Renombrado :files.0.from a :files.0.to',
            'rename_other' => 'Renombrados o movidos :count archivos',
        ],
        'allocation' => [
            'create' => 'Añadida :allocation al servidor',
            'notes' => 'Actualizadas las notas para :allocation de ":old" a ":new"',
            'primary' => 'Establecida :allocation como la asignación primaria del servidor',
            'delete' => 'Eliminada la asignación :allocation',
        ],
        'schedule' => [
            'create' => 'Creado el horario :name',
            'update' => 'Actualizado el horario :name',
            'execute' => 'Ejecutado manualmente el horario :name',
            'delete' => 'Eliminado el horario :name',
        ],
        'task' => [
            'create' => 'Creada una nueva tarea ":action" para el horario :name',
            'update' => 'Actualizada la tarea ":action" para el horario :name',
            'delete' => 'Eliminada una tarea para el horario :name',
        ],
        'settings' => [
            'rename' => 'Renombrado el servidor de :old a :new',
            'description' => 'Cambiada la descripción del servidor de :old a :new',
        ],
        'startup' => [
            'edit' => 'Cambiada la variable :variable de ":old" a ":new"',
            'image' => 'Actualizada la imagen de Docker del servidor de :old a :new',
        ],
        'subuser' => [
            'create' => 'Añadido :email como subusuario',
            'update' => 'Actualizados los permisos del subusuario :email',
            'delete' => 'Eliminado :email como subusuario',
        ],
    ],
];
