<?php

return [
    'title' => 'Backup',
    'empty' => 'Nessun Backup',
    'size' => 'Dimensione',
    'created_at' => 'Creato il',
    'status' => 'Stato',
    'is_locked' => 'Stato bloccaggio',
    'backup_status' => [
        'in_progress' => 'In corso',
        'successful' => 'Riuscito',
        'failed' => 'Non riuscito',
    ],
    'actions' => [
        'create' => [
            'title' => 'Crea Backup',
            'limit' => 'Hai raggiunto il limite massimo di backup',
            'created' => ':name creato',
            'notification_success' => 'Creazione del backup riuscita',
            'notification_fail' => 'Creazione del backup fallita',
            'name' => 'Nome',
            'ignored' => 'File e Cartelle Ignorati',
            'locked' => 'Bloccato?',
            'lock_helper' => 'Impedisce che questo backup venga eliminato a meno che non sia esplicitamente sbloccato',
        ],
        'lock' => [
            'lock' => 'Blocca',
            'unlock' => 'Sblocca',
        ],
        'download' => 'Scarica',
        'rename' => [
            'title' => 'Rinomina',
            'new_name' => 'Nome Backup',
            'notification_success' => 'Backup rinominato correttamente',
        ],
        'restore' => [
            'title' => 'Ripristina',
            'helper' => 'Il tuo server sarà fermato. Non sarai in grado di controllare lo stato di accensione, accedere al file manager o creare ulteriori backup fin quando questo processo non sarà completato.',
            'delete_all' => 'Eliminare tutti i file prima di ripristinare il backup?',
            'notification_started' => 'Ripristino backup',
            'notification_success' => 'Backup ripristinato con successo',
            'notification_fail' => 'Ripristino del backup non riuscito',
            'notification_fail_body_1' => 'Questo server non è attualmente in uno stato che consente di ripristinare un backup.',
            'notification_fail_body_2' => 'Questo backup non può essere ripristinato al momento: non completato o non riuscito',
        ],
        'delete' => [
            'title' => 'Elimina backup',
            'description' => 'Vuoi eliminare :backup?',
            'notification_success' => 'Backup eliminato',
            'notification_fail' => 'Impossibile eliminare il backup',
            'notification_fail_body' => 'Connessione al nodo non riuscita. Riprova.',
        ],
    ],
];
