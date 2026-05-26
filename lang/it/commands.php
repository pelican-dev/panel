<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Fornisci l\'indirizzo email da cui dovrebbero provenire le eggs esportate da questo pannello. Deve essere un indirizzo email valido.',
            'url' => 'L\'URL dell\'applicazione DEVE iniziare con https:// o http:// a seconda se si utilizza SSL o meno. Se non includi lo schema, le tue email e altri contenuti si collegheranno alla posizione sbagliata.',
            'timezone' => 'Il fuso orario dovrebbe corrispondere a uno dei fusi orari supportati da PHP. Se non sei sicuro, fai riferimento a https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Hai selezionato il driver Redis per una o più opzioni, fornisci le informazioni di connessione valide qui sotto. Nella maggior parte dei casi puoi usare i valori predefiniti forniti a meno che tu non abbia modificato la tua configurazione.',
            'comment' => 'Per impostazione predefinita, l\'istanza Redis ha come nome utente "default" e nessuna password, perché è eseguito localmente e non è accessibile dall\'esterno. Se questo è il caso, basta premere invio senza inserire un valore.',
            'confirm' => 'Sembra che :field sia già stato definito per Redis, vorresti cambiarlo?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Si consiglia vivamente di non utilizzare "localhost" come host del database. Abbiamo visto frequenti problemi di connessione del socket . Se si desidera utilizzare una connessione locale si dovrebbe usare "127.0.0.1".',
        'DB_USERNAME_note' => "L'utilizzo dell'account root per le connessioni MySQL non solo è altamente disapprovato, ma non è nemmeno consentito da questa applicazione. Dovrai aver creato un utente MySQL per questo software.",
        'DB_PASSWORD_note' => 'Sembra che tu abbia già una password di connessione MySQL definita, vuoi cambiarla?',
        'DB_error_2' => 'Le tue credenziali di connessione NON sono state salvate. Prima di procedere, dovrai fornire informazioni di connessione valide.',
        'go_back' => 'Torna indietro e riprova',
    ],
    'make_node' => [
        'name' => 'Inserisci un breve identificatore usato per distinguere questo nodo dagli altri',
        'description' => 'Inserisci una descrizione per identificare il nodo',
        'scheme' => 'Inserisci https per SSL o http per una connessione non ssl',
        'fqdn' => 'Inserisci un nome di dominio (es. node.example.com) da usare per connettersi al daemon. Un indirizzo IP può essere utilizzato solo se non si utilizza SSL per questo nodo',
        'public' => 'Questo nodo dovrebbe essere pubblico? Come nota, impostando un nodo in privato negherai la possibilità di auto generazione di questo nodo.',
        'behind_proxy' => 'Il tuo FQDN è dietro un proxy?',
        'maintenance_mode' => 'La modalità di manutenzione deve essere attivata?',
        'memory' => 'Inserisci la quantità massima di memoria',
        'memory_overallocate' => 'Inserisci la quantità di memoria da allocare, -1 disabiliterà il controllo e 0 impedirà la creazione di nuovi server',
        'disk' => 'Inserisci la quantità massima di spazio su disco',
        'disk_overallocate' => 'Inserisci la quantità di disco da sovrallocare. -1 disabiliterà il controllo e 0 bloccherà la creazione di nuovi server',
        'cpu' => 'Inserisci la quantità massima di cpu',
        'cpu_overallocate' => 'Inserisci il quantitativo di cpu da sovrallocare, -1 disabiliterà il controllo e 0 bloccherà la creazione di nuovi server',
        'upload_size' => "'Inserisci la dimensione massima del file",
        'daemonListen' => 'Inserisci la porta di ascolto del daemon',
        'daemonConnect' => 'Inserisci la porta di connessione del daemon (può essere la stessa della porta di ascolto)',
        'daemonSFTP' => 'Inserisci la porta di ascolto SFTP del demone',
        'daemonSFTPAlias' => 'Inserisci l\'alias SFTP del daemon (può essere vuoto)',
        'daemonBase' => 'Inserisci la cartella di base',
        'success' => 'Creato con successo un nuovo nodo con il nome :name che ha id :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Il nodo selezionato non esiste.',
        'error_invalid_format' => 'Formato specificato non valido. Le opzioni valide sono yaml e json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Sembra che tu abbia già configurato una chiave di cifratura dell\'applicazione. Continuando con questo processo, sovrascriverai quella chiave causando la corruzione dei dati per qualsiasi dato crittografato esistente. NON CONTINUARE SE NON SAI COSA STAI FACENDO.',
        'understand' => 'Comprendo le conseguenze dell\'esecuzione di questo comando e accetto ogni responsabilità per la perdita di dati crittografati.',
        'continue' => 'Sei sicuro di voler continuare? Cambiare la chiave di crittografia dell\'applicazione COMPORTERA\' LA PERDITA DEI DATI',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Non ci sono attività pianificate per i server che devono essere eseguite.',
            'error_message' => 'Si è verificato un errore durante l\'elaborazione dello Schedule: ',
        ],
    ],
];
