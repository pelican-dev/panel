<?php

return [
    'user' => [
        'search_users' => 'Enter a Username, User ID, or Email Address',
        'select_search_user' => 'ID of user to delete (Enter \'0\' to re-search)',
        'deleted' => 'User successfully deleted from the Panel.',
        'confirm_delete' => 'Are you sure you want to delete this user from the Panel?',
        'no_users_found' => 'No users were found for the search term provided.',
        'multiple_found' => 'Multiple accounts were found for the user provided, unable to delete a user because of the --no-interaction flag.',
        'ask_admin' => 'Цей користувач є адміністратором?',
        'ask_email' => 'Адрес електронної пошти',
        'ask_username' => 'Ім\'я користувача',
        'ask_name_first' => 'Ім’я',
        'ask_name_last' => 'Прізвище',
        'ask_password' => 'Пароль',
        'ask_password_tip' => 'If you would like to create an account with a random password emailed to the user, re-run this command (CTRL+C) and pass the `--no-password` flag.',
        'ask_password_help' => 'Passwords must be at least 8 characters in length and contain at least one capital letter and number.',
        '2fa_help_text' => [
            'This command will disable 2-factor authentication for a user\'s account if it is enabled. This should only be used as an account recovery command if the user is locked out of their account.',
            'If this is not what you wanted to do, press CTRL+C to exit this process.',
        ],
        '2fa_disabled' => '2-Factor authentication has been disabled for :email.',
    ],
    'schedule' => [
        'output_line' => 'Dispatching job for first task in `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Deleting service backup file :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild request for ":name" (#:id) on node ":node" failed with error: :message',
        'reinstall' => [
            'failed' => 'Reinstall request for ":name" (#:id) on node ":node" failed with error: :message',
            'confirm' => 'You are about to reinstall against a group of servers. Do you wish to continue?',
        ],
        'power' => [
            'confirm' => 'Ви збираєтесь виконати :action проти :count серверів. Бажаєте продовжити?',
            'action_failed' => 'Не вдалося виконати запит дії живлення для ":name" (#:id) на вузол ":node" з помилкою: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Хост (напр. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Порт',
            'ask_smtp_username' => 'SMTP Логін',
            'ask_smtp_password' => 'SMTP Пароль',
            'ask_mailgun_domain' => 'Домен Mailgun',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Секрет Mailgun',
            'ask_mandrill_secret' => 'Секрет Mandrill',
            'ask_postmark_username' => 'Ключ API Postmark',
            'ask_driver' => 'Which driver should be used for sending emails?',
            'ask_mail_from' => 'Email address emails should originate from',
            'ask_mail_name' => 'Ім\'я, з яких повинні розсилатися електронні листи',
            'ask_encryption' => 'Метод шифрування',
        ],
    ],
];
