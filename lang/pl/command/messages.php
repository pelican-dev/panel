<?php

return [
    'user' => [
        'search_users' => 'Wprowadź nazwę użytkownika, identyfikator użytkownika lub adres e-mail',
        'select_search_user' => 'ID użytkownika do usunięcia (Wpisz \'0\' aby wyszukać ponownie)',
        'deleted' => 'Użytkownik został pomyślnie usunięty z Panelu.',
        'confirm_delete' => 'Czy na pewno chcesz usunąć tego użytkownika z Panelu?',
        'no_users_found' => 'Nie znaleziono użytkowników dla podanego terminu wyszukiwania.',
        'multiple_found' => 'Znaleziono wiele kont dla podanego użytkownika, nie można usunąć użytkownika z powodu flagi --no-interaction',
        'ask_admin' => 'Czy ten użytkownik jest administratorem?',
        'ask_email' => 'Adres E-mail',
        'ask_username' => 'Nazwa Użytkownika',
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
        'output_line' => 'Wysyłanie żądania dla pierwszego zadania w `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Usuwanie pliku kopii zapasowej usługi :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Żądanie przebudowy dla ":name" (#:id) na węźle ":node" nie powiodło się z błędem: :message',
        'reinstall' => [
            'failed' => 'Żądanie ponownej instalacji dla ":name" (#:id) na węźle ":node" nie powiodło się z błędem: :message',
            'confirm' => 'Przed Tobą ponowna instalacja na grupie serwerów. Czy chcesz kontynuować?',
        ],
        'power' => [
            'confirm' => 'Zamierzasz wykonać :action przeciwko :count serwerom. Czy chcesz kontynuować?',
            'action_failed' => 'Żądanie akcji zasilania dla ":name" (#:id) na węźle ":node" nie powiodło się z błędem: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Serwer SMTP (np. smtp.gmail.com)',
            'ask_smtp_port' => 'Port SMTP',
            'ask_smtp_username' => 'Nazwa użytkownika SMTP',
            'ask_smtp_password' => 'Hasło SMTP',
            'ask_mailgun_domain' => 'Serwer Mailgun',
            'ask_mailgun_endpoint' => 'Punkt dostępowy Mailgun',
            'ask_mailgun_secret' => 'Sekret Mailgun',
            'ask_mandrill_secret' => 'Sekret Mandrill',
            'ask_postmark_username' => 'Klucz API Postmark',
            'ask_driver' => 'Który sterownik powinien być używany do wysyłania e-maili?',
            'ask_mail_from' => 'Adres e-mail, z którego mają pochodzić wiadomości e-mail',
            'ask_mail_name' => 'Nazwa, z której powinny się pojawić wiadomości e-mail',
            'ask_encryption' => 'Metoda szyfrowania do użycia',
        ],
    ],
];
