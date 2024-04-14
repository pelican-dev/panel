<?php

return [
    'notices' => [
        'imported' => 'Pomyślnie zaimportowano to jądro i związane z nim zmienne.',
        'updated_via_import' => 'To jajko zostało zaktualizowane przy użyciu dostarczonego pliku.',
        'deleted' => 'Pomyślnie usunięto żądane jajko z Panelu.',
        'updated' => 'Konfiguracja jajka została pomyślnie zaktualizowana.',
        'script_updated' => 'Skrypt instalacyjny jajka został zaktualizowany i zostanie uruchomiony za każdym razem, gdy serwery zostaną zainstalowane.',
        'egg_created' => 'Nowe jajkozostało dodane. Musisz zrestartować wszystkie uruchomione Daemon\'y, aby zastosować nowe jądro.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Zmienna ":variable" została usunięta i nie będzie już dostępna dla serwerów po przebudowie.',
            'variable_updated' => 'Zmienna ":variable" została zaktualizowana. Musisz przebudować serwery za pomocą tej zmiennej, aby zastosować zmiany.',
            'variable_created' => 'Nowa zmienna została pomyślnie stworzona i przypisana do tego jądra.',
        ],
    ],
];
