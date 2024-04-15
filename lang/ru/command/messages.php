<?php

return [
    'user' => [
        'search_users' => 'Введите ID пользователя, его имя или адрес эл. почты',
        'select_search_user' => 'ID пользователя для удаления (введите \'0\' для повторного поиска)',
        'deleted' => 'Пользователь успешно удален из панели.',
        'confirm_delete' => 'Вы уверены, что хотите удалить этого пользователя из панели?',
        'no_users_found' => 'По Вашему запросу не найдено ни одного пользователя.',
        'multiple_found' => 'По Вашему запросу найдено несколько аккаунтов пользователей. Ничего не было предпринято, так как установлен флаг --no-interaction.',
        'ask_admin' => 'Является ли пользователь администратором?',
        'ask_email' => 'Адрес эл. почты',
        'ask_username' => 'Имя пользователя',
        'ask_name_first' => 'Имя',
        'ask_name_last' => 'Фамилия',
        'ask_password' => 'Пароль',
        'ask_password_tip' => 'Если Вы хотите создать пользователя со случайным паролем, который будет отправлен ему на адрес эл. почты, выполните эту команду снова, нажав CTRL+C и добавив флаг `--no-password`.',
        'ask_password_help' => 'Пароль должен содержать минимум одну заглавную букву и число, а также иметь длину не менее 8 символов.',
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
            'confirm' => 'You are about to perform a :action against :count servers. Do you wish to continue?',
            'action_failed' => 'Power action request for ":name" (#:id) on node ":node" failed with error: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP хост (например, smtp.gmail.com)',
            'ask_smtp_port' => 'Порт SMTP',
            'ask_smtp_username' => 'SMTP Username',
            'ask_smtp_password' => 'Пароль SMTP',
            'ask_mailgun_domain' => 'Mailgun Domain',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Postmark API Key',
            'ask_driver' => 'Which driver should be used for sending emails?',
            'ask_mail_from' => 'Email address emails should originate from',
            'ask_mail_name' => 'Name that emails should appear from',
            'ask_encryption' => 'Encryption method to use',
        ],
    ],
];
