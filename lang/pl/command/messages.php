<?php

return [
    'user' => [
        'search_users' => 'Enter a Username, User ID, or Email Address',
        'select_search_user' => 'ID of user to delete (Enter \'0\' to re-search)',
        'deleted' => 'Użytkownik został pomyślnie usunięty z Panelu.',
        'confirm_delete' => 'Czy na pewno chcesz usunąć tego użytkownika z Panelu?',
        'no_users_found' => 'Nie znaleziono użytkowników dla podanego terminu wyszukiwania.',
        'multiple_found' => 'Znaleziono wiele kont dla podanego użytkownika, nie można usunąć użytkownika z powodu flagi --no-interaction',
        'ask_admin' => 'Czy ten użytkownik jest administratorem?',
        'ask_email' => 'Adres E-mail',
        'ask_username' => 'Nazwa Użytkownika',
        'ask_name_first' => 'Imię',
        'ask_name_last' => 'Nazwisko',
        'ask_password' => 'Hasło',
        'ask_password_tip' => 'Jeśli chcesz utworzyć konto z losowym hasłem wysłanym e-mailem do użytkownika, ponownie uruchom tę komendę (CTRL+C) i przekaż flagę `--no-password`.',
        'ask_password_help' => 'Hasła muszą mieć co najmniej 8 znaków i zawierać co najmniej jedną wielką literę oraz cyfrę.',
        '2fa_help_text' => [
            'Ta komenda wyłączy uwierzytelnianie dwuskładnikowe dla konta użytkownika, jeśli jest włączone. Powinna być używana tylko jako polecenie odzyskiwania konta, jeśli użytkownik jest zablokowany na swoim koncie.',
            'Jeśli to nie jest to, co chciałeś zrobić, naciśnij CTRL+C, aby zakończyć ten proces.',
        ],
        '2fa_disabled' => 'Uwierzytelnianie dwuskładnikowe zostało wyłączone dla :email',
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
            'ask_smtp_host' => 'SMTP Host (e.g. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Port',
            'ask_smtp_username' => 'SMTP Username',
            'ask_smtp_password' => 'SMTP Password',
            'ask_mailgun_domain' => 'Mailgun Domain',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Klucz API Postmark',
            'ask_driver' => 'Który sterownik powinien być używany do wysyłania e-maili?',
            'ask_mail_from' => 'Adres e-mail, z którego mają pochodzić wiadomości e-mail',
            'ask_mail_name' => 'Nazwa, z której powinny się pojawić wiadomości e-mail',
            'ask_encryption' => 'Metoda szyfrowania do użycia',
        ],
    ],
];
