<?php

return [
    'title' => 'Harmonogramy',
    'new' => 'Nowy Harmonogram',
    'edit' => 'Edytuj Harmonogram',
    'save' => 'Zapisz Harmonogram',
    'delete' => 'Usuń Harmonogram',
    'import' => 'Importuj harmonogram',
    'export' => 'Eksportuj harmonogram',
    'name' => 'Nazwa',
    'cron' => 'Cron',
    'status' => 'Stan',
    'schedule_status' => [
        'inactive' => 'Nieaktywny',
        'processing' => 'Przetwarzanie',
        'active' => 'Aktywny',
    ],
    'no_tasks' => 'Brak Zadań',
    'run_now' => 'Uruchom teraz',
    'online_only' => 'Tylko gdy Online',
    'last_run' => 'Ostatnie uruchomienie',
    'next_run' => 'Następne uruchomienie',
    'never' => 'nigdy',
    'cancel' => 'Anuluj',

    'only_online' => 'Tylko gdy serwer jest online?',
    'only_online_hint' => 'Wykonaj ten harmonogram tylko wtedy, gdy serwer jest uruchomiony.',
    'enabled' => 'Włączyć harmonogram?',
    'enabled_hint' => 'Ten harmonogram zostanie wykonany automatycznie, jeśli jest włączony.',

    'cron_body' => 'Pamiętaj, że dane wejściowe cron zawsze zakładają czas UTC.',
    'cron_timezone' => 'Następne uruchomienie w twojej strefie czasowej (:timezone): <b> :next_run </b>',

    'invalid' => 'Nieprawidłowy',

    'time' => [
        'minute' => 'Minut',
        'hour' => 'Godzin',
        'day' => 'Dni',
        'week' => 'Tydzień',
        'month' => 'Miesiąc',
        'day_of_month' => 'Dzień miesiąca',
        'day_of_week' => 'Dzień tygodnia',

        'hourly' => 'Co godzinę',
        'daily' => 'Codziennie',
        'weekly_mon' => 'Tygodniowo (poniedziałek)',
        'weekly_sun' => 'Tygodniowo (niedziela)',
        'monthly' => 'Co miesiąc',
        'every_min' => 'Co x minut',
        'every_hour' => 'Co x godzin',
        'every_day' => 'Co x dni',
        'every_week' => 'Co x tygodni',
        'every_month' => 'Co x miesięcy',
        'every_day_of_week' => 'Każdego x dnia tygodnia',

        'every' => 'Co',
        'minutes' => 'Minuty',
        'hours' => 'Godziny',
        'days' => 'Dni',
        'months' => 'Miesięcy',

        'monday' => 'Poniedziałek',
        'tuesday' => 'Wtorek',
        'wednesday' => 'Środa',
        'thursday' => 'Czwartek',
        'friday' => 'Piątek',
        'saturday' => 'Sobota',
        'sunday' => 'Niedziela',
    ],

    'tasks' => [
        'title' => 'Zadania',
        'create' => 'Utwórz zadanie',
        'limit' => 'Osiągnięto limit zadań',
        'action' => 'Działania',
        'payload' => 'Ładunek',
        'no_payload' => 'Brak danych',
        'time_offset' => 'Przesunięcie czasu',
        'first_task' => 'Pierwsze zadanie',
        'seconds' => 'Sekundy',
        'continue_on_failure' => 'Kontynuuj przy niepowodzeniu',

        'actions' => [
            'title' => 'Działania',
            'power' => [
                'title' => 'Wyślij akcję zasilania',
                'action' => 'Akcja zasilania',
                'start' => 'Uruchom',
                'stop' => 'Zatrzymaj',
                'restart' => 'Zrestartuj',
                'kill' => 'Zabij',
            ],
            'command' => [
                'title' => 'Wyślij polecenie',
                'command' => 'Polecenie',
            ],
            'backup' => [
                'title' => 'Utwórz kopię zapasową',
                'files_to_ignore' => 'Pliki do ignorowania',
            ],
            'delete_files' => [
                'title' => 'Usuń pliki',
                'files_to_delete' => 'Pliki do usunięcia',
            ],
        ],
    ],

    'notification_invalid_cron' => 'Podane dane crona nie pasują do poprawnego wyrażenia',

    'import_action' => [
        'file' => 'Plik',
        'url' => 'Adres URL',
        'schedule_help' => 'To powinien być plik .json w formacie raw (schedule-daily-restart.json).',
        'url_help' => 'Adresy URL muszą wskazywać bezpośrednio na plik .json w formacie raw.',
        'add_url' => 'Nowy adres URL',
        'import_failed' => 'Import nie powiódł się',
        'import_success' => 'Import powiódł się',
    ],
];
