<?php

return [
    'title' => 'File',
    'name' => 'Nome',
    'size' => 'Dimensione',
    'modified_at' => 'Modificato il',
    'actions' => [
        'open' => 'Apri',
        'download' => 'Scarica',
        'copy' => [
            'title' => 'Copia',
            'notification' => 'File Copiato',
        ],
        'upload' => [
            'title' => 'Carica',
            'from_files' => 'Carica File',
            'from_url' => 'Carica da URL',
            'url' => 'URL',
            'drop_files' => 'Trascina qui i file da caricare',
            'success' => 'File caricati correttamente',
            'failed' => 'Caricamento dei file non riuscito',
            'header' => 'Caricamento File',
            'error' => 'Si è verificato un errore durante il caricamento',
        ],
        'rename' => [
            'title' => 'Rinomina',
            'file_name' => 'Nome File',
            'notification' => 'Il file è stato rinominato',
        ],
        'move' => [
            'title' => 'Sposta',
            'directory' => 'Cartella',
            'directory_hint' => 'Inserisci la nuova cartella, relativa alla cartella corrente.',
            'new_location' => 'Nuova posizione',
            'new_location_hint' => 'Inserisci la posizione di questo file o cartella, relativa alla cartella corrente.',
            'notification' => 'File Spostato',
            'bulk_notification' => ':count file sono stati spostati in :directory',
        ],
        'permissions' => [
            'title' => 'Permessi',
            'read' => 'Lettura',
            'write' => 'Scrittura',
            'execute' => 'Esegui',
            'owner' => 'Proprietario',
            'group' => 'Gruppo',
            'public' => 'Pubblico',
            'notification' => 'Permessi cambiati in :mode',
        ],
        'archive' => [
            'title' => 'Archivio',
            'archive_name' => 'Nome archivio',
            'notification' => 'Archivio Creato',
            'extension' => 'Estensione',
        ],
        'unarchive' => [
            'title' => 'Disarchivia',
            'notification' => 'Disarchivio Completato',
        ],
        'new_file' => [
            'title' => 'Nuovo file',
            'file_name' => 'Nome del nuovo file',
            'syntax' => 'Evidenziazione della sintassi',
            'create' => 'Crea',
        ],
        'new_folder' => [
            'title' => 'Nuova cartella',
            'folder_name' => 'Nome della nuova cartella',
        ],
        'nested_search' => [
            'title' => 'Ricerca in tutte le cartelle',
            'search_term' => 'Termine di ricerca',
            'search_term_placeholder' => 'Inserisci un termine di ricerca, es. *.txt',
            'search' => 'Cerca',
            'search_for_term' => 'Ricerca :term',
        ],
        'delete' => [
            'notification' => 'File cancellato',
            'bulk_notification' => ':count file sono stati eliminati',
        ],
        'edit' => [
            'title' => 'Modificando: :file',
            'save_close' => 'Salva & Chiudi',
            'save' => 'Salva',
            'cancel' => 'Annulla',
            'notification' => 'File salvato',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> è troppo grande!',
            'body' => 'Il massimo è :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> non trovato!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> è una directory',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> esiste già!',
        ],
        'files_node_error' => [
            'title' => 'Impossibile caricare i file!',
        ],
        'pelicanignore' => [
            'title' => 'Stai modificando un file <code>.pelicanignore</code>!',
            'body' => 'Tutti i file o le directory elencati qui saranno esclusi dai backup. Le caratteri jolly sono supportati utilizzando un asterisco (<code>*</code>).<br>Puoi negare una regola precedente preponendo un punto esclamativo (<code>!</code>).',
        ],
    ],
];
