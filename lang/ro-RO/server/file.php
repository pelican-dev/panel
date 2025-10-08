<?php

return [
    'title' => 'Fișiere',
    'name' => 'Nume',
    'size' => 'Dimensiune',
    'modified_at' => 'Modificat pe',
    'actions' => [
        'open' => 'Deschide',
        'download' => 'Descarcă',
        'copy' => [
            'title' => 'Copiază',
            'notification' => 'Fișier copiat',
        ],
        'upload' => [
            'title' => 'Încarcă',
            'from_files' => 'Încărcă fişiere',
            'from_url' => 'Încarcă de la URL',
            'url' => 'URL',
        ],
        'rename' => [
            'title' => 'Redenumește',
            'file_name' => 'Numele Fișierului',
            'notification' => 'Fişier redenumit',
        ],
        'move' => [
            'title' => 'Mută',
            'directory' => 'Director',
            'directory_hint' => 'Introdu noul director, relativ la directorul curent.',
            'new_location' => 'Noua locație',
            'new_location_hint' => 'Introdu locația acestui fișier sau folder, relativ la directorul curent.',
            'notification' => 'Fișier mutat',
            'bulk_notification' => ':count Fișierele au fost mutate în :directory',
        ],
        'permissions' => [
            'title' => 'Permisiuni',
            'read' => 'Citire',
            'write' => 'Scriere',
            'execute' => 'Executare',
            'owner' => 'Proprietar',
            'group' => 'Grup',
            'public' => 'Public',
            'notification' => 'Permisiunile au fost schimbate în :mode',
        ],
        'archive' => [
            'title' => 'Arhivează',
            'archive_name' => 'Nume arhivă',
            'notification' => 'Arhivă creată',
        ],
        'unarchive' => [
            'title' => 'Dezarhivează',
            'notification' => 'Dezarhivare finalizată',
        ],
        'new_file' => [
            'title' => 'Fișier nou',
            'file_name' => 'Denumire fișier nou',
            'syntax' => 'Evidențiere sitaxă',
            'create' => 'Creează',
        ],
        'new_folder' => [
            'title' => 'Folder nou',
            'folder_name' => 'Nume folder nou',
        ],
        'global_search' => [
            'title' => 'Căutare globală',
            'search_term' => 'Caută un termen',
            'search_term_placeholder' => 'Introduceți un termen de căutare, ex. *.txt',
            'search' => 'Caută',
            'search_for_term' => 'Căutare :term',
        ],
        'delete' => [
            'notification' => 'Fișier șters',
            'bulk_notification' => ':count fișiere au fost șterse',
        ],
        'edit' => [
            'title' => 'Editare: :file',
            'save_close' => 'Salvează şi închide',
            'save' => 'Salvează',
            'cancel' => 'Anulează',
            'notification' => 'Fișier salvat',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> este prea mare!',
            'body' => 'Maximul este :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> nu a fost găsit!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> este un director',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> există deja!',
        ],
        'files_node_error' => [
            'title' => 'Nu s-au putut încărca fișierele!',
        ],
        'pelicanignore' => [
            'title' => 'Editezi un fișier <code>.pelicanignore</code>!',
            'body' => 'Orice fișiere sau directoare listate aici vor fi excluse din copiile de rezervă. Se pot folosi wildcard-uri cu ajutorul asteriscului (<code>*</code>).<br>Poți anula o regulă precedentă adăugând un semn de exclamare la început (<code>!</code>).',
        ],
    ],
];
