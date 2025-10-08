<?php

return [
    'user' => [
        'search_users' => 'Wprowadź nazwę użytkownika, identyfikator użytkownika lub adres e-mail.',
        'select_search_user' => 'ID użytkownika do usunięcia (Wprowadź „0”, aby ponownie wyszukać)',
        'deleted' => 'Użytkownik został pomyślnie usunięty z panelu.',
        'confirm_delete' => 'Czy na pewno chcesz usunąć tego użytkownika z Panelu?',
        'no_users_found' => 'Nie znaleziono użytkowników dla podanego terminu wyszukiwania.',
        'multiple_found' => 'Znaleziono wiele kont dla podanego użytkownika, nie można usunąć użytkownika z powodu flagi --no-interaction.',
        'ask_admin' => 'Czy ten użytkownik jest administratorem?',
        'ask_email' => 'Adres E-mail',
        'ask_username' => 'Nazwa Użytkownika',
        'ask_password' => 'Hasło',
        'ask_password_tip' => 'Jeśli chcesz utworzyć konto z losowym hasłem wysyłanym do użytkownika, uruchom ponownie tę komendę (CTRL+C) i użyj flagi --no-password.',
        'ask_password_help' => 'Hasła muszą mieć co najmniej 8 znaków i zawierać przynajmniej jedną wielką literę oraz cyfrę.',
        '2fa_help_text' => 'To polecenie wyłączy uwierzytelnianie dwuskładnikowe dla konta użytkownika, jeśli jest ono włączone. Powinno być używane wyłącznie jako polecenie odzyskiwania konta, jeśli użytkownik nie ma dostępu do swojego konta. Jeśli nie jest to zamierzone działanie, naciśnij klawisze CTRL+C, aby zakończyć ten proces.',
        '2fa_disabled' => 'Uwierzytelnianie dwuetapowe zostało wyłączone dla :email.',
    ],
    'schedule' => [
        'output_line' => 'Wysyłanie zadania dla pierwszego zadania w :schedule (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Usuwanie pliku kopii zapasowej usługi :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Żądanie ponownej budowy dla ":name" (#:id) na węźle ":node" nie powiodło się z błędem: :message',
        'reinstall' => [
            'failed' => 'Żądanie ponownej instalacji dla ":name" (#:id) na węźle ":node" nie powiodło się z błędem: :message',
            'confirm' => 'Zaraz wykonasz ponowną instalację na grupie serwerów. Czy chcesz kontynuować?',
        ],
        'power' => [
            'confirm' => 'Zaraz wykonasz akcję :action na :count serwerach. Czy chcesz kontynuować?',
            'action_failed' => 'Żądanie akcji zasilania dla ":name" (#:id) na węźle ":node" nie powiodło się z błędem: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Serwer SMTP (np. smtp.gmail.com)',
            'ask_smtp_port' => 'Port SMTP',
            'ask_smtp_username' => 'Nazwa użytkownika SMTP',
            'ask_smtp_password' => 'Hasło SMTP',
            'ask_mailgun_domain' => 'Domena Mailgun',
            'ask_mailgun_endpoint' => 'Punkt końcowy Mailgun',
            'ask_mailgun_secret' => 'Sekret Mailgun',
            'ask_mandrill_secret' => 'Sekret Mandrill',
            'ask_postmark_username' => 'Klucz API Postmark',
            'ask_driver' => 'Który dostawca powinien być użyty do wysyłania e-maili?',
            'ask_mail_from' => 'Adres e-mail, z którego powinny być wysyłane wiadomości',
            'ask_mail_name' => 'Nazwa, która powinna być wyświetlana jako nadawca e-maili',
            'ask_encryption' => 'Używana metoda szyfrowania',
        ],
    ],
];
