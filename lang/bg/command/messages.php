<?php

return [
    'user' => [
        'search_users' => 'Въведи потребителско име, потребителско ID или имейл адрес',
        'select_search_user' => 'ID на потребителя да се изтрие (Въведи \'0\' за повторно търсене)',
        'deleted' => 'Потребителят успешно бе изтрит от панела.',
        'confirm_delete' => 'Сигурен ли си че искаш да изтриеш този потребител от панела?',
        'no_users_found' => 'Не бяха намерени потребители с предоставената дума за търсене.',
        'multiple_found' => 'Няколко акаунта бяха намерени за този потребител, не може да се изтрие потребител заради --no-interaction флага.',
        'ask_admin' => 'Този потребител администратор ли е?',
        'ask_email' => 'Имейл адрес',
        'ask_username' => 'Потребителско име',
        'ask_name_first' => 'Първо име',
        'ask_name_last' => 'Фамилия',
        'ask_password' => 'Парола',
        'ask_password_tip' => 'Ако искаш да създадеш акаунт с рандомизирана парола изпратена до имейла на потребителя, изпълни отново тази команда (CTRL+C) и подай флага `--no-password`.',
        'ask_password_help' => 'Паролите трябва да са поне 8 знака дълги и да имат поне една главна буква и число.',
        '2fa_help_text' => [
            'Тази команда ще изключи дву-факторното удостоверяване за акаунта на потребител ако е включено. Това трябва само да се използва като команда за възстановяване на акаунт, ако потребителят няма достъп до акаунтът си.',
            'Ако това не бе каквото искаше да направиш, натисни CTRL+C за да излезнеш от този процес.',
        ],
        '2fa_disabled' => 'Дву-факторното удостоверяване бе изключено за :email.',
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
            'confirm' => 'Ще реинсталираш група от сървъри. Сигурен ли си че искаш да продължиш?',
        ],
        'power' => [
            'confirm' => 'Ще изпълниш :action срещу :count сървъри. Искаш ли да продължиш?',
            'action_failed' => 'Power action request for ":name" (#:id) on node ":node" failed with error: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP хост (пример smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP порт',
            'ask_smtp_username' => 'SMTP потребител',
            'ask_smtp_password' => 'SMTP парола',
            'ask_mailgun_domain' => 'Mailgun домейн',
            'ask_mailgun_endpoint' => 'Mailgun крайна точка',
            'ask_mailgun_secret' => 'Mailgun тайна',
            'ask_mandrill_secret' => 'Mandrill тайна',
            'ask_postmark_username' => 'Postmark API ключ',
            'ask_driver' => 'Which driver should be used for sending emails?',
            'ask_mail_from' => 'Имейл адрес, от който трябва да произхождат имейлите',
            'ask_mail_name' => 'Име от което имейлите произлизат',
            'ask_encryption' => 'Метод за използване на криптиране',
        ],
    ],
];
