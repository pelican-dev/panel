<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Nie udało się zalogować',
        'success' => 'Zalogowano',
        'password-reset' => 'Zresetuj hasło',
        'checkpoint' => 'Zażądano uwierzytelnienia dwuetapowego',
        'recovery-token' => 'Użyto tokena odzyskiwania uwierzytelnienia dwuetapowego',
        'token' => 'Udane uwierzytelnienie dwuetapowe',
        'ip-blocked' => 'Zablokowano żądanie z nieuwzględnionego adresu IP dla <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Nie udało się zalogować do SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Zmieniono nazwę użytkownika z <b>:old</b> na <b>:new</b>',
            'email-changed' => 'Zmieniono e-mail z <b>:old</b> na <b>:new</b>',
            'password-changed' => 'Hasło zostało zmienione',
        ],
        'api-key' => [
            'create' => 'Utworzono nowy klucz API <b>:identifier</b>',
            'delete' => 'Usunięto klucz API <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Dodano klucz SSH <b>:fingerprint</b> do konta',
            'delete' => 'Usunięto klucz SSH <b>:fingerprint</b> z konta',
        ],
        'two-factor' => [
            'create' => 'Włączono autoryzację dwuetapową',
            'delete' => 'Wyłączona autoryzacja dwuetapowa',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Wykonano "<b>:command</b>" na serwerze',
        ],
        'power' => [
            'start' => 'Uruchomiono serwer',
            'stop' => 'Zatrzymano serwer',
            'restart' => 'Zrestartowano serwer',
            'kill' => 'Zabito proces serwera',
        ],
        'backup' => [
            'download' => 'Pobrano kopię zapasową o nazwie <b>:name</b>',
            'delete' => 'Usunięto kopię zapasową o nazwie <b>:name</b>',
            'restore' => 'Przywrócono kopię zapasową o nazwie <b>:name</b> (usunięte pliki: <b>:truncate</b>)',
            'restore-complete' => 'Zakończono przywracanie kopii zapasowej o nazwie <b>:name</b>',
            'restore-failed' => 'Nie udało się ukończyć przywracania kopii zapasowej o nazwie <b>:name</b>',
            'start' => 'Rozpoczęto tworzenie kopii zapasowej o nazwie <b>:name</b>',
            'complete' => 'Tworzenie kopii zapasowej <b>:name</b> zostało ukończone',
            'fail' => 'Tworzenie kopii zapasowej <b>:name</b> nie powiodło się',
            'lock' => 'Zablokowano kopię zapasową <b>:name</b>',
            'unlock' => 'Odblokowano kopię zapasową <b>:name</b>',
            'rename' => 'Zmieniono nazwę kopii zapasowej z "<b>:old_name</b>" na "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Utworzono nową bazę danych o nazwie <b>:name</b>',
            'rotate-password' => 'Hasło dla bazy danych <b>:name</b> zostało zmienione',
            'delete' => 'Usunięto bazę danych o nazwie <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Skompresowano <b>:directory:files</b> | Skompresowano <b>:count</b> plików w <b>:directory</b>',
            'read' => 'Wyświetlono zawartość pliku <b>:file</b>',
            'copy' => 'Utworzono kopię pliku <b>:file</b>',
            'create-directory' => 'Utworzono katalog <b>:directory:name</b>',
            'decompress' => 'Rozpakowano plik <b>:file</b> w katalogu <b>:directory</b>',
            'delete' => 'Usunięto <b>:directory:files</b> | Usunięto <b>:count</b> plików w <b>:directory</b>',
            'download' => 'Pobrano plik <b>:file</b>',
            'pull' => 'Pobrano plik zdalny z <b>:url</b> do <b>:directory</b>',
            'rename' => 'Przeniesiono/Zmieniono nazwę z <b>:from</b> na <b>:to</b> | Przeniesiono/Zmieniono nazwę <b>:count</b> plików w <b>:directory</b>',
            'write' => 'Wpisano nową zawartość do pliku <b>:file</b>',
            'upload' => 'Rozpoczęto przesyłanie pliku',
            'uploaded' => 'Przesłano <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Dostęp SFTP został zablokowany z powodu braku uprawnień',
            'create' => 'Utworzono <b>:files</b> | Utworzono <b>:count</b> nowych plików',
            'write' => 'Zmodyfikowano zawartość pliku <b>:files</b> | Zmieniono zawartość <b>:count</b> plików',
            'delete' => 'Usunięto <b>:files</b> | Usunięto <b>:count</b> plików',
            'create-directory' => 'Utworzono katalog <b>:files</b> | Utworzono <b>:count</b> katalogów',
            'rename' => 'Zmieniono nazwę <b>:from</b> na <b>:to</b> | Zmieniono nazwę lub przeniesiono <b>:count</b> plików',
        ],
        'allocation' => [
            'create' => 'Dodano <b>:allocation</b> do serwera',
            'notes' => 'Zaktualizowano notatki dla <b>:allocation</b> z "<b>:old</b>" na "<b>:new</b>"',
            'primary' => 'Ustaw <b>:allocation</b> jako główną alokację serwera',
            'delete' => 'Usunięto alokację <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'Utworzono harmonogram <b>:name</b>',
            'update' => 'Zaktualizowano harmonogram <b>:name</b>',
            'execute' => 'Ręcznie aktywowano harmonogram o nazwie <b>:name</b>',
            'delete' => 'Usunięto harmonogram <b>:name</b>',
        ],
        'task' => [
            'create' => 'Utworzono nowe zadanie "<b>:action</b>" dla harmonogramu <b>:name</b>',
            'update' => 'Zaktualizowano zadanie "<b>:action</b>" dla harmonogramu <b>:name</b>',
            'delete' => 'Usunięto zadanie "<b>:action</b>" z harmonogramu <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Zmieniono nazwę serwera z "<b>:old</b>" na "<b>:new</b>"',
            'description' => 'Zmieniono opis serwera z "<b>:old</b>" na "<b>:new</b>"',
            'reinstall' => 'Serwer został zreinstalowany',
        ],
        'startup' => [
            'edit' => 'Zmieniono zmienną <b>:variable</b> z "<b>:old</b>" na "<b>:new</b>"',
            'image' => 'Zaktualizowano obraz Dockera dla serwera z <b>:old</b> na <b>:new</b>',
            'command' => 'Zaktualizowano Komendę Startową dla serwera z <b>:old</b> na <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Dodano <b>:email</b> jako podużytkownika',
            'update' => 'Zaktualizowano uprawnienia podużytkownika dla <b>:email</b>',
            'delete' => 'Usunięto <b>:email</b> jako podużytkownika',
        ],
        'crashed' => 'Serwer uległ awarii',
    ],
];
