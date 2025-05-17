<?php

return [
    'title' => 'Systemzustand',
    'results_refreshed' => 'Systemzustandsprüfung aktualisiert',
    'checked' => 'Ergebnisse geprüft von :time',
    'refresh' => 'Aktualisieren',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'OK',
            'failed_retrieve' => 'Konnte keinen Cache-Wert setzen oder abrufen.',
            'failed' => 'Ein Fehler ist im Anwendungs-Cache aufgetreten: :error',
        ],
        'database' => [
            'label' => 'Datenbank',
            'ok' => 'OK',
            'failed' => 'Konnte keine Verbindung zur Datenbank herstellen: :error',
        ],
        'debugmode' => [
            'label' => 'Debug-Modus',
            'ok' => 'Debug-Modus ist deaktiviert',
            'failed' => 'Der Debug-Modus sollte :expected sein, ist aber :actual',
        ],
        'environment' => [
            'label' => 'Umgebung',
            'ok' => 'OK, Gesetzt auf :actual',
            'failed' => 'Umgebung ist auf :actual gesetzt, Erwartet: :expected',
        ],
        'nodeversions' => [
            'label' => 'Node-Versionen',
            'ok' => 'Nodes sind auf dem neuesten Stand',
            'failed' => ':outdated/:all Nodes sind veraltet',
            'no_nodes_created' => 'Keine Nodes erstellt',
            'no_nodes' => 'Keine Nodes',
            'all_up_to_date' => 'Alle auf dem neuesten Stand',
            'outdated' => ':outdated/:all veraltet',
        ],
        'panelversion' => [
            'label' => 'Panel-Version',
            'ok' => 'Panel ist auf dem neuesten Stand',
            'failed' => 'Installierte Version ist :currentVersion, aber die neueste ist :latestVersion',
            'up_to_date' => 'Auf dem neuesten Stand',
            'outdated' => 'Veraltet',
        ],
        'schedule' => [
            'label' => 'Zeitplan',
            'ok' => 'OK',
            'failed_last_ran' => 'Der letzte Durchlauf des Zeitplans war vor mehr als :time Minuten',
            'failed_not_ran' => 'Der Zeitplan wurde noch nicht ausgeführt.',
        ],
        'useddiskspace' => [
            'label' => 'Festplattenspeicher',
        ],
    ],
    'checks' => [
        'successful' => 'Erfolgreich',
        'failed' => 'Fehlgeschlagen',
    ],
]; 