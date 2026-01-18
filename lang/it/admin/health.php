<?php

return [
    'title' => 'Salute',
    'results_refreshed' => 'Risultato dei controlli di salute aggiornati',
    'checked' => 'Risultati verificati :time',
    'refresh' => 'Aggiorna',
    'results' => [
        'cache' => [
            'label' => 'Cache',
            'ok' => 'Ok',
            'failed_retrieve' => 'Impossibile impostare o recuperare un valore di cache dell\'applicazione.',
            'failed' => 'Si è verificata un\'eccezione con la cache dell\'applicazione: :error',
        ],
        'database' => [
            'label' => 'Database',
            'ok' => 'Ok',
            'failed' => 'Impossibile connettersi al database: :error',
        ],
        'debugmode' => [
            'label' => 'Modalità di Debug',
            'ok' => 'La modalità debug è disabilitata',
            'failed' => 'La modalità debug era prevista :expected, ma in realtà era :actual',
        ],
        'environment' => [
            'label' => 'Ambiente',
            'ok' => 'Ok, impostato a :actual',
            'failed' => 'L\'ambiente è impostato su :actual, atteso :expected',
        ],
        'nodeversions' => [
            'label' => 'Versione Nodo',
            'ok' => 'I Nodi sono aggiornati',
            'failed' => ':outdated/:all Nodi sono obsoleti',
            'no_nodes_created' => 'Nessun Nodo creato',
            'no_nodes' => 'Nessun Nodo',
            'all_up_to_date' => 'Tutti aggiornati',
            'outdated' => ':outdated/:all obsoleti',
        ],
        'panelversion' => [
            'label' => 'Versione Pannello',
            'ok' => 'Il pannello è aggiornato',
            'failed' => 'La versione installata è :currentVersion ma l\'ultima è :latestVersion',
            'up_to_date' => 'Aggiornato',
            'outdated' => 'Obsoleto',
        ],
        'schedule' => [
            'label' => 'Pianificazioni',
            'ok' => 'Ok',
            'failed_last_ran' => 'L\'ultima esecuzione delle pianificazioni è stata più di :time minuti fa',
            'failed_not_ran' => 'Le pianificazioni non sono ancora state eseguite.',
        ],
        'useddiskspace' => [
            'label' => 'Spazio sul Disco',
        ],
    ],
    'checks' => [
        'successful' => 'Riusciti',
        'failed' => 'Falliti :checks',
    ],
];
