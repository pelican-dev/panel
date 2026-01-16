<?php

return [
    'title' => 'Dateien',
    'name' => 'Name',
    'size' => 'Größe',
    'modified_at' => 'Zuletzt bearbeitet:',
    'actions' => [
        'open' => 'Öffnen',
        'download' => 'Herunterladen',
        'copy' => [
            'title' => 'Kopieren',
            'notification' => 'Datei erfolgreich kopiert',
        ],
        'upload' => [
            'title' => 'Hochladen',
            'from_files' => 'Dateien Hochladen',
            'from_url' => 'Per URL hochladen',
            'url' => 'URL',
            'drop_files' => 'Dateien zum Hochladen hier ablegen',
            'success' => 'Dateien erfolgreich hochgeladen',
            'failed' => 'Fehler beim Hochladen der Dateien',
            'header' => 'Dateien werden hochgeladen',
            'error' => 'Beim Hochladen ist ein Fehler aufgetreten.',
        ],
        'rename' => [
            'title' => 'Umbenennen',
            'file_name' => 'Datei Name',
            'notification' => 'Datei erfolgreich umbenannt',
        ],
        'move' => [
            'title' => 'Verschieben',
            'directory' => 'Ordner',
            'directory_hint' => 'Gib einen neuen Dateipfad an, gelesen vom aktuellen Ordner',
            'new_location' => 'Neuer Ablageort',
            'new_location_hint' => 'Geben Sie den Speicherort dieser Datei oder dieses Ordners relativ zum aktuellen Verzeichnis ein.',
            'notification' => 'Datei erfolgreich verschoben',
            'bulk_notification' => ':count Dateien wurden nach :directory verschoben',
        ],
        'permissions' => [
            'title' => 'Berechtigungen',
            'read' => 'Lesen',
            'write' => 'Schreiben',
            'execute' => 'Befehle Ausführen',
            'owner' => 'Admin',
            'group' => 'Gruppe',
            'public' => 'Öffentlich',
            'notification' => 'Berechtigungen geändert zu :mode',
        ],
        'archive' => [
            'title' => 'Archivieren',
            'archive_name' => 'Archivname',
            'notification' => 'Archiv erstellt',
            'extension' => 'Erweiterung',
        ],
        'unarchive' => [
            'title' => 'De archivieren',
            'notification' => 'De archivieren fertig',
        ],
        'new_file' => [
            'title' => 'Neue Datei',
            'file_name' => 'Neuer Dateiname',
            'syntax' => 'Syntaxhervorhebung',
            'create' => 'Erstellen',
        ],
        'new_folder' => [
            'title' => 'Neuer Ordner',
            'folder_name' => 'Neuer Ordnername',
        ],
        'nested_search' => [
            'title' => 'Verschachtelte Suche',
            'search_term' => 'Suchbegriff',
            'search_term_placeholder' => 'Geben Sie einen Suchbegriff ein, z. B. *.txt',
            'search' => 'Suchen',
            'search_for_term' => 'Suchen :term',
        ],
        'delete' => [
            'notification' => 'Datei gelöscht',
            'bulk_notification' => ':count Dateien wurden gelöscht',
        ],
        'edit' => [
            'title' => 'Bearbeiten: :file',
            'save_close' => 'Speichern & Schließen',
            'save' => 'Speichern',
            'cancel' => 'Abbrechen',
            'notification' => 'Datei gespeichert',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code></code> Ist zu groß',
            'body' => 'Max ist',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> Nicht gefunden',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> ist ein Verzeichnis',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> ist bereits vorhanden!',
        ],
        'files_node_error' => [
            'title' => 'Dateien konnten nicht geladen werden!',
        ],
        'pelicanignore' => [
            'title' => 'Sie bearbeiten eine <code>.pelicanignore</code>-Datei!',
            'body' => 'Alle hier aufgeführten Dateien oder Verzeichnisse werden von Backups ausgeschlossen. Platzhalter werden durch die Verwendung eines Sternchens (<code>*</code>) unterstützt.<br> Sie können eine vorherige Regel negieren, indem Sie ein Ausrufezeichen (<code>!</code>) voranstellen.',
        ],
    ],
];
