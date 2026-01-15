<?php

return [
    'title' => 'Pliki',
    'name' => 'Nazwa',
    'size' => 'Rozmiar',
    'modified_at' => 'Zmodyfikowano:',
    'actions' => [
        'open' => 'Otwórz',
        'download' => 'Pobierz',
        'copy' => [
            'title' => 'Kopiuj',
            'notification' => 'Plik skopiowany',
        ],
        'upload' => [
            'title' => 'Prześlij',
            'from_files' => 'Prześlij pliki',
            'from_url' => 'Prześlij z adresu URL',
            'url' => 'URL',
            'drop_files' => 'Upuść plik, aby przesłać',
            'success' => 'Pliki zostały przesłane pomyślnie',
            'failed' => 'Nie udało się przesłać plików',
            'header' => 'Przesyłanie plików...',
            'error' => 'Wystąpił błąd podczas przesyłania',
        ],
        'rename' => [
            'title' => 'Zmień nazwę',
            'file_name' => 'Nazwa pliku',
            'notification' => 'Zmieniono nazwę pliku',
        ],
        'move' => [
            'title' => 'Przenieś',
            'directory' => 'Katalog',
            'directory_hint' => 'Wprowadź nowy katalog względem aktualnego katalogu.',
            'new_location' => 'Nowa lokalizacja',
            'new_location_hint' => 'Wprowadź lokalizację tego pliku lub folderu, w stosunku do bieżącego katalogu.',
            'notification' => 'Plik przeniesiony',
            'bulk_notification' => ':count plików zostało przeniesionych do :directory',
        ],
        'permissions' => [
            'title' => 'Uprawnienia',
            'read' => 'Odczyt',
            'write' => 'Pisanie',
            'execute' => 'Wykonywanie',
            'owner' => 'Właściciel',
            'group' => 'Grupa',
            'public' => 'Publiczna',
            'notification' => 'Uprawnienia zmienione na :mode',
        ],
        'archive' => [
            'title' => 'Archiwum',
            'archive_name' => 'Nazwa archiwum',
            'notification' => 'Archiwum utworzone',
            'extension' => 'Rozszerzenie',
        ],
        'unarchive' => [
            'title' => 'Rozpakuj',
            'notification' => 'Rozpakowywanie zakończone',
        ],
        'new_file' => [
            'title' => 'Nowy plik',
            'file_name' => 'Nazwa nowego pliku',
            'syntax' => 'Podświetlanie składni',
            'create' => 'Utwórz',
        ],
        'new_folder' => [
            'title' => 'Nowy folder',
            'folder_name' => 'Nazwa nowego folderu',
        ],
        'nested_search' => [
            'title' => 'Wyszukiwanie zagnieżdżone',
            'search_term' => 'Wyszukiwana fraza',
            'search_term_placeholder' => 'Wprowadź wyszukiwaną frazę, np. *.txt',
            'search' => 'Wyszukaj',
            'search_for_term' => 'Wyszukiwanie :term',
        ],
        'delete' => [
            'notification' => 'Plik usunięty',
            'bulk_notification' => ':count plików zostało usuniętych',
        ],
        'edit' => [
            'title' => 'Edytowanie: :file',
            'save_close' => 'Zapisz & Zamknij',
            'save' => 'Zapisz',
            'cancel' => 'Anuluj',
            'notification' => 'Zapisano plik',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> jest zbyt duża!',
            'body' => 'Max to :max',
        ],
        'file_not_found' => [
            'title' => 'Nie znaleziono <code>:name</code>!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> jest katalogiem',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> już istnieje!',
        ],
        'files_node_error' => [
            'title' => 'Nie można załadować plików!',
        ],
        'pelicanignore' => [
            'title' => 'Edytujesz plik <code>.pelicanignore</code>!',
            'body' => 'Wszelkie pliki lub katalogi wymienione w tym miejscu zostaną wykluczone z kopii zapasowych. Symbole wieloznaczne są obsługiwane za pomocą gwiazdki (<code>*</code>).<br>Można unieważnić poprzednią regułę, dodając przed nią wykrzyknik (<code>!</code>).',
        ],
    ],
];
