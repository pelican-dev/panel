<?php

return [
    'title' => 'Copii de rezervă',
    'empty' => 'Nicio copie de rezervă',
    'size' => 'Dimensiune',
    'created_at' => 'Creat pe',
    'status' => 'Status',
    'is_locked' => 'Stare de blocare',
    'backup_status' => [
        'in_progress' => 'În progres',
        'successful' => 'Reușit',
        'failed' => 'Eșuat',
    ],
    'actions' => [
        'create' => [
            'title' => 'Creează copie de rezervă',
            'limit' => 'Limita de copii de rezervă atinsă',
            'created' => ':name creat',
            'notification_success' => 'Copie de rezervă creată cu succes',
            'notification_fail' => 'Crearea copiei de rezervă a eșuat',
            'name' => 'Nume',
            'ignored' => 'Fişiere şi directoare ignorate',
            'locked' => 'Blocat?',
            'lock_helper' => 'Împiedică ștergerea acestei copii de rezervă până când este deblocată în mod explicit.',
        ],
        'lock' => [
            'lock' => 'Blochează',
            'unlock' => 'Deblochează',
        ],
        'download' => 'Descarcă',
        'rename' => [
            'title' => 'Redenumește',
            'new_name' => 'Numele copiei de rezervă',
            'notification_success' => 'Copia de rezervă a fost redenumită cu succes',
        ],
        'restore' => [
            'title' => 'Restaurează',
            'helper' => 'Serverul tău va fi oprit. Nu vei putea controla starea de alimentare, accesa managerul de fișiere sau crea copii de rezervă suplimentare până când acest proces nu se va finaliza.',
            'delete_all' => 'Ștergi toate fișierele înainte de a restaura copia de rezervă?',
            'notification_started' => 'Se restaurează copia de rezervă',
            'notification_success' => 'Copie de rezervă restaurată cu succes',
            'notification_fail' => 'Restaurarea copiei de rezervă a eșuat',
            'notification_fail_body_1' => 'Acest server nu se află momentan într-o stare care să permită restaurarea unei copii de rezervă',
            'notification_fail_body_2' => 'Această copie de rezervă nu poate fi restaurată în acest moment: nu este finalizată sau a eșuat.',
        ],
        'delete' => [
            'title' => 'Șterge copia de rezervă',
            'description' => 'Dorești să ștergi :backup?',
            'notification_success' => 'Copie de rezervă ștearsă',
            'notification_fail' => 'Nu s-a putut șterge copia de rezervă',
            'notification_fail_body' => 'Conexiunea la nod a eșuat. Te rugăm să încerci din nou.',
        ],
    ],
];
