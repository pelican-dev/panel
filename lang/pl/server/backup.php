<?php

return [
    'title' => 'Kopie zapasowe',
    'empty' => 'Brak kopii zapasowych',
    'size' => 'Rozmiar',
    'created_at' => 'Data utworzenia',
    'status' => 'Stan',
    'is_locked' => 'Status Blokady',
    'backup_status' => [
        'in_progress' => 'W trakcie',
        'successful' => 'Zakończono pomyślnie',
        'failed' => 'Nie powiodło się',
    ],
    'actions' => [
        'create' => [
            'title' => 'Utwórz kopię zapasową',
            'limit' => 'Osiągnięto limit kopii zapasowych',
            'created' => ':name został utworzony',
            'notification_success' => 'Kopia zapasowa została utworzona pomyślnie',
            'notification_fail' => 'Tworzenie kopii zapasowej nie powiodło się',
            'name' => 'Nazwa',
            'ignored' => 'Ignorowane Pliki & Foldery',
            'locked' => 'Zablokowane?',
            'lock_helper' => 'Zapobiega usunięciu tej kopii zapasowej do czasu jej wyraźnego odblokowania.',
        ],
        'lock' => [
            'lock' => 'Zablokuj',
            'unlock' => 'Odblokuj',
        ],
        'download' => 'Pobierz',
        'rename' => [
            'title' => 'Zmień nazwę',
            'new_name' => 'Nazwa kopii zapasowej',
            'notification_success' => 'Nazwa kopii zapasowej została zmieniona',
        ],
        'restore' => [
            'title' => 'Przywróć',
            'helper' => 'Twój serwer zostanie zatrzymany. Nie będziesz w stanie kontrolować stanu zasilania, uzyskać dostępu do menedżera plików lub utworzyć dodatkowe kopie zapasowe do czasu zakończenia tego procesu.',
            'delete_all' => 'Usunąć wszystkie pliki przed przywróceniem kopii zapasowej?',
            'notification_started' => 'Przywracanie kopii zapasowej',
            'notification_success' => 'Kopia zapasowa została przywrócona pomyślnie',
            'notification_fail' => 'Nie udało się przywrócić kopii zapasowej',
            'notification_fail_body_1' => 'Ten serwer nie jest obecnie w stanie umożliwiającym przywrócenie kopii zapasowej.',
            'notification_fail_body_2' => 'Ta kopia zapasowa nie może zostać przywrócona w tym momencie: nie została zakończona lub nie powiodła się.',
        ],
        'delete' => [
            'title' => 'Usuń kopię zapasową',
            'description' => 'Czy chcesz usunąć :backup?',
            'notification_success' => 'Kopia zapasowa została usunięta',
            'notification_fail' => 'Nie udało się usunąć kopii zapasowej',
            'notification_fail_body' => 'Połączenie z węzłem nie powiodło się. Spróbuj ponownie.',
        ],
    ],
];
