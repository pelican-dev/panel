<?php

return [
    'greeting' => '¡Hola :name!',

    'account_created' => [
        'body' => 'Estás recibiendo este correo porque se ha creado una cuenta para ti en :app.',
        'username' => 'Nombre de usuario: :username',
        'email' => 'Correo: :email',
        'action' => 'Configura tu cuenta',
    ],

    'added_to_server' => [
        'body' => 'Has sido añadido como subusuario para el siguiente servidor, lo que te permite cierto control sobre él.',
        'server_name' => 'Nombre del servidor: :name',
        'action' => 'Visitar servidor',
    ],

    'removed_from_server' => [
        'body' => 'Has sido eliminado como subusuario para el siguiente servidor.',
        'server_name' => 'Nombre del servidor: :name',
        'action' => 'Visitar panel',
    ],

    'server_installed' => [
        'body' => 'Tu servidor ha finalizado su instalación y está listo para que lo utilices.',
        'server_name' => 'Nombre del servidor: :name',
        'action' => 'Iniciar sesión y comenzar a usar',
    ],

    'backup_completed' => [
        'body_success' => 'La copia de seguridad se creó correctamente.',
        'body_failed' => 'La creación de la copia de seguridad ha fallado.',
        'backup_name' => 'Nombre de la copia de seguridad: :name',
        'server_name' => 'Nombre del servidor: :name',
        'action' => 'Ver copias de seguridad',
    ],

    'mail_tested' => [
        'subject' => 'Mensaje de prueba del panel',
        'body' => 'Esta es una prueba del sistema de correo del panel. ¡Has completado su configuración!',
    ],
];
