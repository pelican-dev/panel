<?php

return [
    'title' => 'Életerő',
    'results_refreshed' => 'Életerő ellenőrzés eredményeinek frissítése',
    'checked' => 'Ellenőrzött eredmények :time -tól/től',
    'refresh' => 'Újratölt',
    'results' => [
        'cache' => [
            'label' => 'Gyorsítótár',
            'ok' => 'Ok',
            'failed_retrieve' => 'Nem sikerült beállítani vagy lekérni egy alkalmazás gyorsítótár értékét.',
            'failed' => 'Kivétel történt az alkalmazás gyorsítótárával kapcsolatban: :error',
        ],
        'database' => [
            'label' => 'Adatbázis',
            'ok' => 'Ok',
            'failed' => 'Nem lehet csatlakozni az adatbázishoz : :error',
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
            'label' => 'Node Verzió',
            'ok' => 'Node-ok naprakészek',
            'failed' => ':outdated/:all A csomópontok elavultak',
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
            'label' => 'Munkamenet',
            'ok' => 'Ok',
            'failed_last_ran' => 'A menetrend utolsó futása több mint :time percekkel ezelőtt volt.',
            'failed_not_ran' => 'A menetrend még nem futott le.',
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
