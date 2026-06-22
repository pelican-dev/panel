<?php

return [
    'user' => [
        'search_users' => 'Introduce un nombre de usuario, ID de usuario o dirección de correo',
        'select_search_user' => 'ID del usuario a eliminar (Introduce \'0\' para volver a buscar)',
        'deleted' => 'Usuario eliminado correctamente del panel.',
        'confirm_delete' => '¿Estás seguro de que quieres eliminar este usuario del panel?',
        'no_users_found' => 'No se encontraron usuarios para el término de búsqueda proporcionado.',
        'multiple_found' => 'Se encontraron varias cuentas para el usuario proporcionado, no se puede eliminar un usuario debido a la opción --no-interaction.',
        'ask_admin' => '¿Es este usuario un administrador?',
        'ask_email' => 'Dirección de correo',
        'ask_username' => 'Nombre de usuario',
        'ask_password' => 'Contraseña',
        'ask_password_tip' => 'Si deseas crear una cuenta con una contraseña aleatoria enviada por correo al usuario, vuelve a ejecutar este comando (CTRL+C) y agrega la opción --no-password.',
        'ask_password_help' => 'Las contraseñas deben tener al menos 8 caracteres de longitud y contener al menos una letra mayúscula y un número.',
        '2fa_help_text' => 'Este comando deshabilitará la autenticación de doble factor para la cuenta de un usuario si está activado. Esto solo debería usarse como un comando de recuperación de cuenta si el usuario está bloqueado fuera de su cuenta. Si esto no es lo que querías hacer, pulsa CTRL+C para salir de este proceso.',
        '2fa_disabled' => 'La autenticación de dos factores ha sido desactivada para :email.',
    ],
    'schedule' => [
        'output_line' => 'Enviando acción para la primera tarea en `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Eliminando archivo de copia de seguridad del servicio :file.',
    ],
    'server' => [
        'rebuild_failed' => 'La solicitud de reconstrucción para ":name" (#:id) en el nodo ":node" falló con el error: :message',
        'reinstall' => [
            'failed' => 'La solicitud de reinstalación para ":name" (#:id) en el nodo ":node" falló con el error: :message',
            'confirm' => 'Estás a punto de reinstalar un grupo de servidores. ¿Deseas continuar?',
        ],
        'power' => [
            'confirm' => 'Estás a punto de realizar una :action en :count servidores. ¿Deseas continuar?',
            'action_failed' => 'La acción para ":name" (#:id) en el nodo ":node" falló con el error: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Host SMTP (por ejemplo, smtp.gmail.com)',
            'ask_smtp_port' => 'Puerto SMTP',
            'ask_smtp_username' => 'Nombre de usuario SMTP',
            'ask_smtp_password' => 'Contraseña SMTP',
            'ask_mailgun_domain' => 'Dominio de Mailgun',
            'ask_mailgun_endpoint' => 'Endpoint de Mailgun',
            'ask_mailgun_secret' => 'Secreto de Mailgun',
            'ask_mandrill_secret' => 'Secreto de Mandrill',
            'ask_postmark_username' => 'Clave API de Postmark',
            'ask_driver' => '¿Qué driver debe usarse para enviar correos?',
            'ask_mail_from' => 'Dirección de correo desde la cual deben enviarse los correos',
            'ask_mail_name' => 'Nombre que debe aparecer en los correos',
            'ask_encryption' => 'Método de cifrado a usar',
        ],
    ],
];
