<?php

return [
    'title' => 'Файли',
    'name' => 'Назва',
    'size' => 'Розмір',
    'modified_at' => 'Дата зміни',
    'actions' => [
        'open' => 'Відкрити',
        'download' => 'Вивантаження',
        'copy' => [
            'title' => 'Копіювати',
            'notification' => 'Файл скопійовано',
        ],
        'upload' => [
            'title' => 'Залити',
            'from_files' => 'Вивантажити файли',
            'from_url' => 'Завантажити з URL',
            'url' => 'URL',
            'drop_files' => 'Перетягніть файли, щоб завантажити',
            'success' => 'Файли успішно вивантажено',
            'failed' => 'Не вдалося завантажити файли',
            'header' => 'Завантаження файлів',
            'error' => 'Виникла помилка під час завантаження',
        ],
        'rename' => [
            'title' => 'Перейменувати',
            'file_name' => 'Ім\'я файлу',
            'notification' => 'Файл перейменовано',
        ],
        'move' => [
            'title' => 'Перемістити',
            'directory' => 'Каталог',
            'directory_hint' => 'Введіть новий каталог, відносно поточної директорії.',
            'new_location' => 'Нове розташування',
            'new_location_hint' => 'Введіть розташування цього файлу або каталогу, відносно поточної директорії.',
            'notification' => 'Файл переміщено',
            'bulk_notification' => ':count файлів було переміщено в :directory',
        ],
        'permissions' => [
            'title' => 'Доступи',
            'read' => 'Читання',
            'write' => 'Запис',
            'execute' => 'Виконання',
            'owner' => 'Власник',
            'group' => 'Група',
            'public' => 'Публічний',
            'notification' => 'Доступи змінено на :mode',
        ],
        'archive' => [
            'title' => 'Архів',
            'archive_name' => 'Назва архіву',
            'notification' => 'Архів створено',
            'extension' => 'Розширення',
        ],
        'unarchive' => [
            'title' => 'Розархівувати',
            'notification' => 'Розархівування завершено',
        ],
        'new_file' => [
            'title' => 'Новий файл',
            'file_name' => 'Нове ім\'я файлу',
            'syntax' => 'Підсвічування синтаксису',
            'create' => 'Створити',
        ],
        'new_folder' => [
            'title' => 'Нова тека',
            'folder_name' => 'Нова назва теки',
        ],
        'nested_search' => [
            'title' => 'Пошук в файлах',
            'search_term' => 'Пошуковий запит',
            'search_term_placeholder' => 'Введіть слово для пошуку, наприклад *.txt',
            'search' => 'Пошук',
            'search_for_term' => 'Пошук в :term',
        ],
        'delete' => [
            'notification' => 'Файл видалено',
            'bulk_notification' => 'Видалено :count файлів',
        ],
        'edit' => [
            'title' => 'Редагування: :file',
            'save_close' => 'Зберегти та закрити',
            'save' => 'Зберегти',
            'cancel' => 'Скасувати',
            'notification' => 'Файл збережено',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> завеликий!',
            'body' => 'Макс :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> не знайдено!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> це каталог',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> вже існує!',
        ],
        'files_node_error' => [
            'title' => 'Не вдалося завантажити файли!',
        ],
        'pelicanignore' => [
            'title' => 'Ви редагуєте <code>.pelicanignore</code> файл!',
            'body' => 'Усі перелічені тут файли чи каталоги буде виключено з резервних копій. Символи підстановки підтримуються за допомогою зірочки (<code>*</code>).<br>Ви можете скасувати попереднє правило, додавши перед ним знак оклику (<code>!</code>).',
        ],
    ],
];
