<?php

return [
    'title' => 'Helse',
    'results_refreshed' => 'Helsesjekkresultater oppdatert',
    'checked' => 'Sjekkede resultater fra :time',
    'refresh' => 'Oppdater',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'Ok',
            'failed_retrieve' => 'Kunne ikke sette eller hente en applikasjonsbufferverdi.',
            'failed' => 'En feil oppstod med applikasjonsbufferen: :error',
        ],
        'database' => [
            'label' => 'Database',
            'ok' => 'Ok',
            'failed' => 'Kunne ikke koble til databasen: :error',
        ],
        'debugmode' => [
            'label' => 'Feilsøkingsmodus',
            'ok' => 'Feilsøkingsmodus er deaktivert',
            'failed' => 'Feilsøkingsmodus var forventet å være :expected, men var faktisk :actual',
        ],
        'environment' => [
            'label' => 'Miljø',
            'ok' => 'Ok, satt til :actual',
            'failed' => 'Miljøet er satt til :actual, forventet :expected',
        ],
        'nodeversions' => [
            'label' => 'Nodeversjoner',
            'ok' => 'Nodene er oppdatert',
            'failed' => ':outdated/:all noder er utdaterte',
            'no_nodes_created' => 'Ingen noder opprettet',
            'no_nodes' => 'Ingen noder',
            'all_up_to_date' => 'Alle er oppdatert',
            'outdated' => ':outdated/:all utdaterte',
        ],
        'panelversion' => [
            'label' => 'Panelversjon',
            'ok' => 'Panelet er oppdatert',
            'failed' => 'Installert versjon er :currentVersion, men nyeste er :latestVersion',
            'up_to_date' => 'Oppdatert',
            'outdated' => 'Utdatert',
        ],
        'schedule' => [
            'label' => 'Tidsplan',
            'ok' => 'Ok',
            'failed_last_ran' => 'Den siste kjøringen av tidsplanen var for mer enn :time minutter siden',
            'failed_not_ran' => 'Tidsplanen har ikke blitt kjørt ennå.',
        ],
        'useddiskspace' => [
            'label' => 'Diskplass',
        ],
    ],
    'checks' => [
        'successful' => 'Vellykket',
        'failed' => 'Mislykket',
    ],
];
