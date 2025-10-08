<?php

return [
    'title' => 'Hälsa',
    'results_refreshed' => 'Hälsokontrollens resultat är uppdaterade',
    'checked' => 'Kontrollerade resultat från :time',
    'refresh' => 'Uppdatera',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'Ok',
            'failed_retrieve' => 'Kunde inte ställa in eller hämta ett programmets cachevärde.',
            'failed' => 'Ett fel inträffade med applikations cache :error',
        ],
        'database' => [
            'label' => 'Databas',
            'ok' => 'Ok',
            'failed' => 'Kunde inte ansluta till databasen: :error',
        ],
        'debugmode' => [
            'label' => 'Felsökningsläge',
            'ok' => 'Felsökningsläget är inaktiverat',
            'failed' => 'Felsökningsläget förväntades vara :expected, men var faktiskt :actual',
        ],
        'environment' => [
            'label' => 'Miljö',
            'ok' => 'Ok, satt till :actual',
            'failed' => 'Miljö är satt till :actual , Förväntad :expected',
        ],
        'nodeversions' => [
            'label' => 'Node versioner',
            'ok' => 'Noderna är uppdaterade',
            'failed' => ':outdated/:all noder är utdaterade',
            'no_nodes_created' => 'Inga noder skapade',
            'no_nodes' => 'Inga noder',
            'all_up_to_date' => 'Alla är aktuella',
            'outdated' => ':outdated/:all utdaterad',
        ],
        'panelversion' => [
            'label' => 'Panelens version',
            'ok' => 'Panelen är uppdaterad',
            'failed' => 'Installerad version är :currentVersion men senaste är :latestVersion',
            'up_to_date' => 'Aktuell',
            'outdated' => 'Utdaterad',
        ],
        'schedule' => [
            'label' => 'Schema',
            'ok' => 'Ok',
            'failed_last_ran' => 'Den senaste körningen av schemat var mer än :time minuter sedan',
            'failed_not_ran' => 'Schemat har inte körts än.',
        ],
        'useddiskspace' => [
            'label' => 'Diskutrymme',
        ],
    ],
    'checks' => [
        'successful' => 'Lyckad',
        'failed' => 'Misslyckad',
    ],
];
