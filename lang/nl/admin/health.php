<?php

return [
    'title' => 'Status ',
    'results_refreshed' => 'Resultaten van gezondheidscontrole bijgewerkt',
    'checked' => 'Gecontroleerde resultaten van :time',
    'refresh' => 'Vernieuw',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'Oké',
            'failed_retrieve' => 'Kan de waarde van de applicatie-cache niet instellen of ophalen.',
            'failed' => 'Er is een uitzondering opgetreden in de applicatie cache: :error',
        ],
        'database' => [
            'label' => 'Database',
            'ok' => 'Oké',
            'failed' => 'Kan niet verbinden met de database: :error',
        ],
        'debugmode' => [
            'label' => 'Debugmodus',
            'ok' => 'Debugmodus is uitgeschakeld',
            'failed' => 'De debug modus zou :expected, maar eigenlijk :actual waren',
        ],
        'environment' => [
            'label' => 'Environment',
            'ok' => 'Oké, ingesteld op :actual',
            'failed' => 'Omgeving ingesteld op :actual , verwacht :expected',
        ],
        'nodeversions' => [
            'label' => 'Node Versie',
            'ok' => 'Nodes zijn up-to-date',
            'failed' => ':verouderd/:alle Nodes zijn verouderd',
            'no_nodes_created' => 'Geen Nodes Aangemaakt',
            'no_nodes' => 'Geen Nodes',
            'all_up_to_date' => 'Alle up-to-date',
            'outdated' => ':verouderd/:all verouderd',
        ],
        'panelversion' => [
            'label' => 'Paneel versie',
            'ok' => 'Uw paneel is up to date',
            'failed' => 'Geïnstalleerde versie is :currentVersion maar de laatste versie is :latestVersion',
            'up_to_date' => 'Up-to-date',
            'outdated' => 'Verouderd',
        ],
        'schedule' => [
            'label' => 'Planning',
            'ok' => 'Oké',
            'failed_last_ran' => 'De laatste uitvoering van de planning was meer dan :time minuten geleden',
            'failed_not_ran' => 'De planning is nog niet uitgevoerd.',
        ],
        'useddiskspace' => [
            'label' => 'Schijfruimte',
        ],
    ],
    'checks' => [
        'successful' => 'Succesvol',
        'failed' => 'mislukt',
    ],
];
