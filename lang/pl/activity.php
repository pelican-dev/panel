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
        'reset-password' => 'Zażądano zresetowania hasła',
        'checkpoint' => 'Zażądano uwierzytelnienia dwuetapowego',
        'recovery-token' => 'Użyto tokena odzyskiwania uwierzytelnienia dwuetapowego',
        'token' => 'Udane uwierzytelnienie dwuetapowe',
        'ip-blocked' => 'Zablokowano żądanie z nieuwzględnionego adresu IP dla :identifer',
        'sftp' => [
            'fail' => 'Nie udało się zalogować do SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Zmieniono adres e-mail z :old na :new',
            'password-changed' => 'Hasło zostało zmienione',
        ],
        'api-key' => [
            'create' => 'Stwórz nowy klucz API :identifier',
            'delete' => 'Usuń klucz API :identifier',
        ],
        'ssh-key' => [
            'create' => 'Dodano klucz SSH :fingerprint do konta',
            'delete' => 'Usunięto klucz SSH :fingerprint z konta',
        ],
        'two-factor' => [
            'create' => 'Włączono autoryzację dwuetapową',
            'delete' => 'Wyłączona autoryzacja dwuetapowa',
        ],
    ],
    'server' => [
        'reinstall' => 'Zainstalowano ponownie serwer',
        'console' => [
            'command' => 'Wykonano ":command" na serwerze',
        ],
        'power' => [
            'start' => 'Uruchomiono serwer',
            'stop' => 'Zatrzymano serwer',
            'restart' => 'Zrestartowano serwer',
            'kill' => 'Zatrzymano proces serwera',
        ],
        'backup' => [
            'download' => 'Pobrano kopię zapasową o nazwie :name',
            'delete' => 'Usunięto kopię zapasową :name',
            'restore' => 'Przywrócono kopię zapasową o nazwie :name (usunięte pliki: :truncate)',
            'restore-complete' => 'Zakończono przywracanie kopii zapasowej o nazwie :name',
            'restore-failed' => 'Nie udało się zakończyć przywracania kopii zapasowej o nazwie :name',
            'start' => 'Rozpoczęto tworzenie kopii zapasowej :name',
            'complete' => 'Tworzenie kopii zapasowej :name zakończyło się pomyślnie',
            'fail' => 'Tworzenie kopii zapasowej :name nie powiodło się',
            'lock' => 'Zablokowano kopie zapasową :name',
            'unlock' => 'Odblokowano kopię zapasową :name',
        ],
        'database' => [
            'create' => 'Utwórz nową bazę danych :name',
            'rotate-password' => 'Zmieniono hasło dla bazy danych o nazwie :name',
            'delete' => 'Usunięto bazę danych o nazwie :name',
        ],
        'file' => [
            'compress_one' => 'Skompresowano :directory:file',
            'compress_other' => 'Skompresowano :count plików w katalogu :directory',
            'read' => 'Sprawdzono zawartość pliku :file',
            'copy' => 'Utworzono kopię pliku :file',
            'create-directory' => 'Utworzono katalog :directory:name',
            'decompress' => 'Rozpakowano :files w :directory',
            'delete_one' => 'Usunięto :directory:files.0',
            'delete_other' => 'Usunięto :count plików w katalogu :directory',
            'download' => 'Pobrano plik: :file',
            'pull' => 'Pobrano pliki z :url do :directory',
            'rename_one' => 'Zmieniono nazwę :directory:files.0.from na :directory:files.0.to',
            'rename_other' => 'Zmieniono nazwy :count plików w katalogu :directory.',
            'write' => 'Dokonano zapisu nowej zawartości do pliku :file',
            'upload' => 'Rozpoczęto przesyłanie pliku',
            'uploaded' => 'Przesłano :directory:file',
        ],
        'sftp' => [
            'denied' => 'Dostęp SFTP został zablokowany z powodu braku uprawnień',
            'create_one' => 'Utworzono :files.0',
            'create_other' => 'Utworzono :count nowych plików',
            'write_one' => 'Zmodyfikowano zawartość pliku :files.0',
            'write_other' => 'Zmodyfikowano zawartość :count plików',
            'delete_one' => 'Usunięto :files.0',
            'delete_other' => 'Usunięto :count plików',
            'create-directory_one' => 'Utworzono katalog :files.0',
            'create-directory_other' => 'Utworzono :count katalogów',
            'rename_one' => 'Zmieniono nazwę :files.0.from na :files.0.to',
            'rename_other' => 'Zmieniono nazwę lub przeniesiono :count plików',
        ],
        'allocation' => [
            'create' => 'Dodano :allocation do serwera',
            'notes' => 'Zaktualizowano informacje dla :allocation z ":old" na ":new".',
            'primary' => 'Ustawiono :allocation jako główną alokację serwera.',
            'delete' => 'Usunięto alokację :allocation',
        ],
        'schedule' => [
            'create' => 'Utworzono harmonogram o nazwie :name',
            'update' => 'Zaktualizowano harmonogram o nazwie :name',
            'execute' => 'Ręcznie wykonano harmonogram o nazwie :name',
            'delete' => 'Usunięto harmonogram o nazwie :name',
        ],
        'task' => [
            'create' => 'Utworzono nowe zadanie ":action" dla harmonogramu o nazwie :name',
            'update' => 'Zaktualizowano zadanie ":action" dla harmonogramu o nazwie :name.',
            'delete' => 'Usunięto zadanie dla harmonogramu o nazwie :name.',
        ],
        'settings' => [
            'rename' => 'Zmieniono nazwę serwera z :old na :new',
            'description' => 'Zmieniono opis serwera z :old na :new',
        ],
        'startup' => [
            'edit' => 'Zmieniono zmienną :variable z ":old" na ":new".',
            'image' => 'Zaktualizowano obraz Docker dla serwera z :old na :new.',
        ],
        'subuser' => [
            'create' => 'Dodano :email jako drugiego użytkownika.',
            'update' => 'Zaktualizowano uprawnienia dla użytkownika :email',
            'delete' => 'Usunięto :email jako współpracownika.',
        ],
    ],
];
