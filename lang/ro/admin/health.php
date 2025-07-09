<?php

return [
    'title' => 'Viață',
    'results_refreshed' => 'Rezultatele controlului de viață actualizate',
    'checked' => 'Rezultate verificate din :time',
    'refresh' => 'Reimprospatare',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'Ok',
            'failed_retrieve' => 'Nu s-a putut seta sau recupera o valoare de cache a aplicației.',
            'failed' => 'O excepție a avut loc cu cache-ul aplicației: :error',
        ],
        'database' => [
            'label' => 'Baza de date',
            'ok' => 'Ok',
            'failed' => 'Nu s-a putut efectua conexiunea la baza de date: :error',
        ],
        'debugmode' => [
            'label' => 'Mod depanare',
            'ok' => 'Modul depanare este dezactivat',
            'failed' => 'Se aștepta ca modul de depanare să fie :expected, dar de fapt a fost :actual',
        ],
        'environment' => [
            'label' => 'Mediu',
            'ok' => 'Ok, Setat la :actual',
            'failed' => 'Mediul este setat la :actual , Așteptat :expected',
        ],
        'nodeversions' => [
            'label' => 'Versiune Node',
            'ok' => 'Nodurile sunt actualizate',
            'failed' => ':outdated/:toate Nodurile sunt depășite',
            'no_nodes_created' => 'Nici un Nod creat',
            'no_nodes' => 'Nici un Nod',
            'all_up_to_date' => 'Toate la zi',
            'outdated' => ':outdate/:all învechit',
        ],
        'panelversion' => [
            'label' => 'Versiune panou',
            'ok' => 'Panoul are cea mai recentă versiune',
            'failed' => 'Versiunea instalată este :currentVersion dar ultima este :latestVersion',
            'up_to_date' => 'La zi',
            'outdated' => 'Învechit',
        ],
        'schedule' => [
            'label' => 'Planificare',
            'ok' => 'Ok',
            'failed_last_ran' => 'Ultima execuție a programului a fost cu mai mult de :time minute în urmă',
            'failed_not_ran' => 'Programul nu s-a executat încă.',
        ],
        'useddiskspace' => [
            'label' => 'Spațiu pe disc',
        ],
    ],
    'checks' => [
        'successful' => 'Reușit',
        'failed' => 'Eșuat',
    ],
];
