<?php

return [
    'title' => 'Status',
    'results_refreshed' => 'Rezultati provere statusa ažurirani',
    'checked' => 'Provereni rezultati od :time',
    'refresh' => 'Osveži',
    'results' => [
        'cache' => [
            'label' => 'Keš',
            'ok' => 'U redu',
            'failed_retrieve' => 'Nije bilo moguće postaviti ili dohvatiti vrednost keš memorije aplikacije.',
            'failed' => 'Došlo je do izuzetka sa keš memorijom aplikacije: :error',
        ],
        'database' => [
            'label' => 'Baza podataka',
            'ok' => 'U redu',
            'failed' => 'Nije bilo moguće povezati se sa bazom podataka: :error',
        ],
        'debugmode' => [
            'label' => 'Režim za otklanjanje grešaka',
            'ok' => 'Režim za otklanjanje grešaka je isključen',
            'failed' => 'Režim za otklanjanje grešaka je očekivan da bude :expectes, ali zapravo je :actual',
        ],
        'environment' => [
            'label' => 'Okruženje',
            'ok' => 'U redu, postavljeno na :actual',
            'failed' => 'Okruženje je postavljeno na :actual, očekivano :expected',
        ],
        'nodeversions' => [
            'label' => 'Verzije Čvora',
            'ok' => 'Čvorovi su ažurirani',
            'failed' => ':outdated/:all Čvorovi su zastareli',
            'no_nodes_created' => 'Nema kreiranih čvorova',
            'no_nodes' => 'Nema čvorova',
            'all_up_to_date' => 'Sve je ažurirano',
            'outdated' => ':outdated/:all zastarelo',
        ],
        'panelversion' => [
            'label' => 'Panel verzija',
            'ok' => 'Panel je ažuriran',
            'failed' => 'Instalirana verzija je :currentVersion, ali najnovija verzija je :latestVersion',
            'up_to_date' => 'Ažurirano',
            'outdated' => 'Zastarelo',
        ],
        'schedule' => [
            'label' => 'Raspored',
            'ok' => 'U redu',
            'failed_last_ran' => 'Poslednje izvršenje rasporeda bilo je pre više od :time minuta',
            'failed_not_ran' => 'Raspored još nije izvršen.',
        ],
        'useddiskspace' => [
            'label' => 'Prostor na disku',
        ],
    ],
    'checks' => [
        'successful' => 'Uspešno',
        'failed' => 'Neuspešno',
    ],
];
