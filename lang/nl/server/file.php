<?php

return [
    'title' => 'Bestanden',
    'name' => 'Naam',
    'size' => 'Grootte',
    'modified_at' => 'Gewijzigd op',
    'actions' => [
        'open' => 'Openen',
        'download' => 'Downloaden',
        'copy' => [
            'title' => 'Kopiëren',
            'notification' => 'Bestand gekopieerd',
        ],
        'upload' => [
            'title' => 'Uploaden',
            'from_files' => 'Bestanden uploaden',
            'from_url' => 'Uploaden vanaf URL',
            'url' => 'URL',
            'drop_files' => 'Sleep bestand om te uploaden',
            'success' => 'Bestand succesvol geüpload',
            'failed' => 'Kon de bestanden niet uploaden',
            'header' => 'Bestanden worden geüpload',
            'error' => 'Er is een fout opgetreden tijdens het uploaden',
        ],
        'rename' => [
            'title' => 'Naam Wijzigen',
            'file_name' => 'Bestandsnaam',
            'notification' => 'Bestandsnaam gewijzigd',
        ],
        'move' => [
            'title' => 'Verplaatsen',
            'directory' => 'Map',
            'directory_hint' => 'Voer de nieuwe map in, ten opzichte van de huidige map.',
            'new_location' => 'Nieuwe locatie',
            'new_location_hint' => 'Vul de locatie in van dit bestand of map, ten opzichte van de huidige map.',
            'notification' => 'Bestand verplaatst',
            'bulk_notification' => ':count bestanden zijn verplaatst naar :directory',
        ],
        'permissions' => [
            'title' => 'Rechten',
            'read' => 'Lezen',
            'write' => 'Schrijven',
            'execute' => 'Uitvoeren',
            'owner' => 'Eigenaar',
            'group' => 'Groep',
            'public' => 'Openbaar',
            'notification' => 'De rechten zijn gewijzigd naar :mode',
        ],
        'archive' => [
            'title' => 'Archiveren',
            'archive_name' => 'Archief Naam',
            'notification' => 'Archief aangemaakt',
            'extension' => 'Extensie',
        ],
        'unarchive' => [
            'title' => 'Dearchiveren',
            'notification' => 'Dearchiveren voltooid',
        ],
        'new_file' => [
            'title' => 'Nieuw bestand',
            'file_name' => 'Nieuwe bestandsnaam',
            'syntax' => 'Syntaxis markering',
            'create' => 'Aanmaken',
        ],
        'new_folder' => [
            'title' => 'Nieuwe map',
            'folder_name' => 'Nieuwe mapnaam',
        ],
        'nested_search' => [
            'title' => 'Geneste zoekopdracht',
            'search_term' => 'Zoekterm',
            'search_term_placeholder' => 'Voer een zoekterm in, bijvoorbeeld: *.txt',
            'search' => 'Zoeken',
            'search_for_term' => 'Zoek :term',
        ],
        'delete' => [
            'notification' => 'Bestand Verwijderd',
            'bulk_notification' => ':count bestanden zijn verwijderd',
        ],
        'edit' => [
            'title' => 'Aan het bewerken: :file',
            'save_close' => 'Opslaan & sluiten',
            'save' => 'Opslaan',
            'cancel' => 'Annuleren',
            'notification' => 'Bestand opgeslagen',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> is te groot!',
            'body' => 'Maximale is :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> niet gevonden!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> is een map',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> bestaat al!',
        ],
        'files_node_error' => [
            'title' => 'Bestanden konden niet worden geladen!',
        ],
        'pelicanignore' => [
            'title' => 'Je bent een <code>.pelicanignore</code> bestand aan het aanpassen!',
            'body' => 'Alle bestanden en mappen in dit bestand zullen worden uitgesloten van backups. Wildcards worden ondersteund door het gebruik van een asterisk (<code>*</code>).<br>Je kunt een eerdere regel ontkennen door er een uitroepteken voor te plaatsen (<code>!</code>).',
        ],
    ],
];
