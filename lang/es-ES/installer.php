<?php

return [
    'title' => 'Instalador del panel',
    'requirements' => [
        'title' => 'Requisitos del Servidor',
        'sections' => [
            'version' => [
                'title' => 'Versión de PHP',
                'or_newer' => ':version o más reciente',
                'content' => 'Tu versión de PHP es :version.',
            ],
            'extensions' => [
                'title' => 'Extensiones PHP',
                'good' => 'Todas las extensiones de PHP necesarias están instaladas.',
                'bad' => 'Faltan las siguientes extensiones de PHP: :extensions',
            ],
            'permissions' => [
                'title' => 'Permisos de la carpeta',
                'good' => 'Todas las carpetas tienen los permisos correctos.',
                'bad' => 'Las siguientes carpetas tienen permisos incorrectos: :folders',
            ],
        ],
        'exception' => 'Faltan algunos requisitos',
    ],
    'environment' => [
        'title' => 'Entorno',
        'fields' => [
            'app_name' => 'Nombre de la aplicación',
            'app_name_help' => 'Este será el Nombre de tu Panel.',
            'app_url' => 'URL de la aplicación',
            'app_url_help' => 'Esta será la URL desde la que accedas a tu Panel.',
            'account' => [
                'section' => 'Administrador',
                'email' => 'E-mail',
                'username' => 'Usuario',
                'password' => 'Contraseña',
            ],
        ],
    ],
    'database' => [
        'title' => 'Base de datos',
        'driver' => 'Controlador de la base de datos',
        'driver_help' => 'El controlador utilizado para la base de datos del panel. Recomendamos "SQLite".',
        'fields' => [
            'host' => 'Host de la base de datos',
            'host_help' => 'El host de su base de datos. Asegúrese de que es accesible.',
            'port' => 'Puerto de base de datos',
            'port_help' => 'El puerto de su base de datos.',
            'path' => 'Ruta de la base de datos',
            'path_help' => 'La ruta del archivo .sqlite relativa a la carpeta de la base de datos.',
            'name' => 'Nombre de la base de datos',
            'name_help' => 'El nombre de la base de datos del panel.',
            'username' => 'Nombre de usuario de la base de datos',
            'username_help' => 'El nombre de su usuario de la base de datos.',
            'password' => 'Contraseña de la base de datos',
            'password_help' => 'La contraseña de su usuario de la base de datos. Puede estar vacía.',
        ],
        'exceptions' => [
            'connection' => 'Conexión a la base de datos fallida',
            'migration' => 'Migración fallida',
        ],
    ],
    'session' => [
        'title' => 'Sesión',
        'driver' => 'Controlador de sesión',
        'driver_help' => 'El controlador utilizado para almacenar sesiones. Recomendamos "Filesystem" o "Database".',
    ],
    'cache' => [
        'title' => 'Caché',
        'driver' => 'Controlador de caché',
        'driver_help' => 'El controlador utilizado para cachear. Recomendamos "Filesystem".',
        'fields' => [
            'host' => 'Servidor Redis',
            'host_help' => 'El host de su base de datos. Asegúrese de que es accesible.',
            'port' => 'Puerto de Redis',
            'port_help' => 'El puerto de tu servidor redis.',
            'username' => 'Nombre de usuario para Redis',
            'username_help' => 'La contraseña de su usuario de la base de datos. Puede estar vacía',
            'password' => 'Contraseña de Redis',
            'password_help' => 'La contraseña de su usuario de la base de datos. Puede estar vacía.',
        ],
        'exception' => 'Conexión a la base de datos fallida',
    ],
    'queue' => [
        'title' => 'Cola',
        'driver' => 'Controlador de cola',
        'driver_help' => 'El controlador utilizado para gestionar las colas. Recomendamos "Base de datos".',
        'fields' => [
            'done' => 'He realizado los dos pasos siguientes.',
            'done_validation' => '¡Debes realizar ambos pasos antes de continuar!',
            'crontab' => 'Ejecute el siguiente comando para configurar su crontab. Tenga en cuenta que <code>www-data</code> es el usuario de su servidor web. ¡En algunos sistemas, este nombre de usuario puede ser diferente!',
            'service' => 'Para configurar el servicio de cola de trabajo, solo tienes que ejecutar el siguiente comando.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'No se pudo escribir en el archivo .env.',
        'migration' => 'No se pudieron ejecutar las migraciones.',
        'create_user' => 'No se pudo crear el usuario administrador.',
    ],
    'next_step' => 'Siguiente Paso.',
    'finish' => 'Terminar.',
];
