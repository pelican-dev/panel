<?php

return [
    'user' => [
        'search_users' => 'Ingrese un nombre de usuario, ID de usuario o dirección de correo electrónico',
        'select_search_user' => 'ID del usuario a eliminar (Ingrese \'0\' para volver a buscar)',
        'deleted' => 'Usuario eliminado exitosamente del Panel.',
        'confirm_delete' => '¿Está seguro de que desea eliminar este usuario del Panel?',
        'no_users_found' => 'No se encontraron usuarios para el término de búsqueda proporcionado.',
        'multiple_found' => 'Se encontraron varias cuentas para el usuario proporcionado, no se puede eliminar un usuario debido al indicador --no-interaction.',
        'ask_admin' => '¿Es este usuario un administrador?',
        'ask_email' => 'Dirección de correo electrónico',
        'ask_username' => 'Nombre de usuario',
        'ask_password' => 'Contraseña',
        'ask_password_tip' => 'Si desea crear una cuenta con una contraseña aleatoria enviada por correo electrónico al usuario, vuelva a ejecutar este comando (CTRL+C) y pase el indicador `--no-password`.',
        'ask_password_help' => 'Las contraseñas deben tener al menos 8 caracteres de longitud y contener al menos una letra mayúscula y un número.',
        '2fa_help_text' => [
            'Este comando desactivará la autenticación de 2 factores para la cuenta de un usuario si está habilitada. Esto solo debe usarse como un comando de recuperación de cuenta si el usuario está bloqueado fuera de su cuenta.',
            'Si esto no es lo que quería hacer, presione CTRL+C para salir de este proceso.',
        ],
        '2fa_disabled' => 'La autenticación de 2 factores ha sido desactivada para :email.',
    ],
    'schedule' => [
        'output_line' => 'Enviando trabajo para la primera tarea en `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Eliminando el archivo de copia de seguridad del servicio: :file.',
    ],
    'server' => [
        'rebuild_failed' => 'La solicitud de reconstrucción para ":name" (#:id) en el nodo ":node" falló con el error: :message',
        'reinstall' => [
            'failed' => 'La solicitud de reinstalación para ":name" (#:id) en el nodo ":node" falló con el error: :message',
            'confirm' => 'Está a punto de reinstalar en un grupo de servidores. ¿Desea continuar?',
        ],
        'power' => [
            'confirm' => 'Está a punto de realizar una acción de :action en :count servidores. ¿Desea continuar?',
            'action_failed' => 'La solicitud de acción de energía para ":name" (#:id) en el nodo ":node" falló con el error: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Host SMTP (p. ej. smtp.gmail.com)',
            'ask_smtp_port' => 'Puerto SMTP',
            'ask_smtp_username' => 'Nombre de usuario SMTP',
            'ask_smtp_password' => 'Contraseña SMTP',
            'ask_mailgun_domain' => 'Dominio de Mailgun',
            'ask_mailgun_endpoint' => 'Punto de conexión de Mailgun',
            'ask_mailgun_secret' => 'Secreto de Mailgun',
            'ask_mandrill_secret' => 'Secreto de Mandrill',
            'ask_postmark_username' => 'Clave API de Postmark',
            'ask_driver' => '¿Qué controlador se debe utilizar para enviar correos electrónicos?',
            'ask_mail_from' => 'Dirección de correo electrónico desde la que deben originarse los correos electrónicos',
            'ask_mail_name' => 'Nombre que deben mostrar los correos electrónicos',
            'ask_encryption' => 'Método de cifrado a utilizar',
        ],
    ],
];
