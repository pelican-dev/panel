<?php

return [
    'title' => 'Egészség',
    'results_refreshed' => 'Az egészség ellenőrzés eredményei frissítve',
    'checked' => 'Ellenőrzött eredmények :time',
    'refresh' => 'Újratöltés',
    'results' => [
        'cache' => [
            'label' => 'Gyorsítótár',
            'ok' => 'Oké',
            'failed_retrieve' => 'Nem sikerült beállítani vagy lekérni egy alkalmazás gyorsítótár értékét.',
            'failed' => 'Kivétel történt az alkalmazás gyorsítótárával kapcsolatban: :error',
        ],
        'database' => [
            'label' => 'Adatbázis',
            'ok' => 'Oké',
            'failed' => 'Nem lehet csatlakozni az adatbázishoz: :error',
        ],
        'debugmode' => [
            'label' => 'Hibakereső mód',
            'ok' => 'A hibakeresési mód le van tiltva',
            'failed' => 'A hibakeresési módnak :expected -nek kellett volna lennie, de valójában :actual volt.',
        ],
        'environment' => [
            'label' => 'Környezet',
            'ok' => 'Rendben, :actual -ra(re) lett állítva',
            'failed' => 'Környezet :actual -ra(re) van állítva, Elvárt visszont :expected',
        ],
        'nodeversions' => [
            'label' => 'Csomópont Verzió',
            'ok' => 'Csomópontok naprakészek',
            'failed' => ':all csomópont ból :outdated elavult',
            'no_nodes_created' => 'Nincsenek létrehozott csomópontok',
            'no_nodes' => 'Nincsenek csomópontok',
            'all_up_to_date' => 'Minden naprakész',
            'outdated' => ':outdated/:all elavult',
        ],
        'panelversion' => [
            'label' => 'Panel Verzió',
            'ok' => 'A Panel naprakész',
            'failed' => 'A telepített verzió :currentVersion, de a legújabb :latestVersion',
            'up_to_date' => 'Naprakész',
            'outdated' => 'Elavult',
        ],
        'schedule' => [
            'label' => 'Időzítő',
            'ok' => 'Oké',
            'failed_last_ran' => 'Az időzítő utolsó futása több mint :time perccel ezelőtt volt.',
            'failed_not_ran' => 'Az időzítő még nem futott le.',
        ],
        'useddiskspace' => [
            'label' => 'Lemezterület',
        ],
    ],
    'checks' => [
        'successful' => 'Sikeres',
        'failed' => 'Sikertelen',
    ],
];
