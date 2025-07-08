<?php

return [
    'title' => 'Kondycja',
    'results_refreshed' => 'Wyniki oceny kondycji zaktualizowane',
    'checked' => 'Wyniki sprawdzone od :time',
    'refresh' => 'Odśwież',
    'results' => [
        'cache' => [
            'label' => 'Pamięć podręczna',
            'ok' => 'OK',
            'failed_retrieve' => 'Nie można ustawić lub pobrać wartości pamięci podręcznej aplikacji.',
            'failed' => 'Wystąpił błąd z pamięcią podręczną aplikacji: :error',
        ],
        'database' => [
            'label' => 'Baza danych',
            'ok' => 'OK',
            'failed' => 'Nie można połączyć się z bazą danych: :error',
        ],
        'debugmode' => [
            'label' => 'Tryb debugowania',
            'ok' => 'Tryb debugowania jest wyłączony',
            'failed' => 'Tryb debugowania powinien być :expected ale jest :actual',
        ],
        'environment' => [
            'label' => 'Środowisko',
            'ok' => 'Ok, ustawiony na :actual',
            'failed' => 'Środowisko jest ustawione na :actual , oczekiwano :expected',
        ],
        'nodeversions' => [
            'label' => 'Wersje węzłów',
            'ok' => 'Węzły są aktualne',
            'failed' => ':outdated/:all węzły są nieaktualne',
            'no_nodes_created' => 'Brak utworzonych węzłów',
            'no_nodes' => 'Brak węzłów',
            'all_up_to_date' => 'Wszystko aktualne',
            'outdated' => ':outdated/:all nieaktualne',
        ],
        'panelversion' => [
            'label' => 'Wersja panelu',
            'ok' => 'Panel jest aktualny',
            'failed' => 'Zainstalowana wersja to :currentVersion ale najnowsza to :latestVersion',
            'up_to_date' => 'Aktualne',
            'outdated' => 'Nieaktualne',
        ],
        'schedule' => [
            'label' => 'Harmonogram',
            'ok' => 'OK',
            'failed_last_ran' => 'Ostatnie uruchomienie harmonogramu było więcej niż :time minut temu',
            'failed_not_ran' => 'Harmonogram nie został jeszcze uruchomiony.',
        ],
        'useddiskspace' => [
            'label' => 'Miejsce na dysku',
        ],
    ],
    'checks' => [
        'successful' => 'Sukces',
        'failed' => 'Niepowodzenie',
    ],
];
