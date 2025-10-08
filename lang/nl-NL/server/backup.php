<?php

return [
    'title' => 'Back-ups',
    'empty' => 'Geen back-ups',
    'size' => 'Grootte',
    'created_at' => 'Aangemaakt op',
    'status' => 'Status',
    'is_locked' => 'Vergrendelstatus',
    'backup_status' => [
        'in_progress' => 'Bezig',
        'successful' => 'Succesvol',
        'failed' => 'Mislukt',
    ],
    'actions' => [
        'create' => [
            'title' => 'Maak back-up',
            'limit' => 'Back-up limiet bereikt',
            'created' => ':name aangemaakt',
            'notification_success' => 'Back-up succesvol gemaakt',
            'notification_fail' => 'Back-up maken is mislukt',
            'name' => 'Naam',
            'ignored' => 'Genegeerde bestanden & mappen',
            'locked' => 'Vergrendeld?',
            'lock_helper' => 'Voorkomt dat deze back-up wordt verwijderd totdat deze expliciet is ontgrendeld.',
        ],
        'lock' => [
            'lock' => 'Vergrendelen',
            'unlock' => 'Ontgrendelen',
        ],
        'download' => 'Downloaden',
        'rename' => [
            'title' => 'Hernoem',
            'new_name' => 'Back-up naam',
            'notification_success' => 'Back-up succesvol hernoemd',
        ],
        'restore' => [
            'title' => 'Herstellen',
            'helper' => 'De server zal worden gestopt. Je kunt de server status niet beheren, toegang krijgen tot de bestandsbeheerder of extra back-ups maken totdat dit proces is voltooid.',
            'delete_all' => 'Alle bestanden verwijderen voordat de back-up hersteld wordt?',
            'notification_started' => 'Bezig met herstellen van back-up',
            'notification_success' => 'Back-up succesvol hersteld',
            'notification_fail' => 'Back-up herstellen mislukt',
            'notification_fail_body_1' => 'Deze server bevindt zich momenteel niet in een status die het mogelijk maakt om een back-up te herstellen.',
            'notification_fail_body_2' => 'Deze back-up kan op dit moment niet worden hersteld: niet voltooid of mislukt.',
        ],
        'delete' => [
            'title' => 'Back-up Verwijderen',
            'description' => 'Wilt u :backup verwijderen?',
            'notification_success' => 'Back-up verwijderd',
            'notification_fail' => 'Back-up kon niet worden verwijderd',
            'notification_fail_body' => 'Verbinding met node mislukt. Probeer het opnieuw.',
        ],
    ],
];
