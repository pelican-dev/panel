<?php

return [
    'title' => 'Fichiers',
    'name' => 'Nom',
    'size' => 'Taille',
    'modified_at' => 'Modifié le',
    'actions' => [
        'open' => 'Ouvrir',
        'download' => 'Télécharger',
        'copy' => [
            'title' => 'Copier',
            'notification' => 'Fichier copié',
        ],
        'upload' => [
            'title' => 'Importer',
            'from_files' => 'Importer des fichiers',
            'from_url' => 'Importer depuis l\'URL',
            'url' => 'URL',
            'drop_files' => 'Déposez les fichiers à envoyer',
            'success' => 'Fichiers envoyés avec succès',
            'failed' => 'Impossible d\'envoyer les fichiers',
            'header' => 'Envoi des fichiers',
            'error' => 'Une erreur est survenue pendant l\'envoi',
        ],
        'rename' => [
            'title' => 'Renommer',
            'file_name' => 'Nom du fichier',
            'notification' => 'Fichier renommé',
        ],
        'move' => [
            'title' => 'Déplacer',
            'directory' => 'Dossier',
            'directory_hint' => 'Entrez le nouveau répertoire, relatif au répertoire courant.',
            'new_location' => 'Nouvel emplacement',
            'new_location_hint' => 'Entrez l\'emplacement de ce fichier ou du dossier, relatif au répertoire courant.',
            'notification' => 'Fichier déplacé',
            'bulk_notification' => ':count fichiers ont été déplacés vers :directory',
        ],
        'permissions' => [
            'title' => 'Permissions',
            'read' => 'Lecture',
            'write' => 'Écriture',
            'execute' => 'Exécution',
            'owner' => 'Propriétaire',
            'group' => 'Groupe',
            'public' => 'Public',
            'notification' => 'Permissions changées en :mode',
        ],
        'archive' => [
            'title' => 'Archiver',
            'archive_name' => 'Nom de l\'archive',
            'notification' => 'Archive créée',
            'extension' => 'Extension',
        ],
        'unarchive' => [
            'title' => 'Désarchiver',
            'notification' => 'Désarchivage terminé',
        ],
        'new_file' => [
            'title' => 'Nouveau fichier',
            'file_name' => 'Nom du nouveau fichier',
            'syntax' => 'Coloration syntaxique',
            'create' => 'Créer',
        ],
        'new_folder' => [
            'title' => 'Nouveau dossier',
            'folder_name' => 'Nouveau nom de dossier',
        ],
        'nested_search' => [
            'title' => 'Recherche imbriquée',
            'search_term' => 'Terme de recherche',
            'search_term_placeholder' => 'Saisissez un terme de recherche, par ex. *.txt',
            'search' => 'Rechercher',
            'search_for_term' => 'Recherche :term',
        ],
        'delete' => [
            'notification' => 'Fichier supprimé',
            'bulk_notification' => ':count fichiers ont été supprimés',
        ],
        'edit' => [
            'title' => 'Édition: :file',
            'save_close' => 'Enregistrer & Fermer',
            'save' => 'Enregistrer',
            'cancel' => 'Annuler',
            'notification' => 'Fichier enregistré',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> est trop grand !',
            'body' => 'Le max est de :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> introuvable !',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> est un dossier',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> existe déjà !',
        ],
        'files_node_error' => [
            'title' => 'Impossible de charger les fichiers !',
        ],
        'pelicanignore' => [
            'title' => 'Vous éditez un fichier <code>.pelicanignore</code> !',
            'body' => 'Tous les fichiers et dossiers listés ici vont être exclus des sauvegardes. Les wildcards sont supportées en utilisant un astérisque (<code>*</code>).<br>Vous pouvez annuler une règle précédente en préfixant un point d\'exclamation (<code>!</code>).',
        ],
    ],
];
