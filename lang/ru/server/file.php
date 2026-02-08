<?php

return [
    'title' => 'Файлы',
    'name' => 'Название',
    'size' => 'Размер',
    'modified_at' => 'Изменен',
    'actions' => [
        'open' => 'Открыть',
        'download' => 'Скачать',
        'copy' => [
            'title' => 'Копировать',
            'notification' => 'Файл скопирован',
        ],
        'upload' => [
            'title' => 'Загрузить',
            'from_files' => 'Загрузить файлы',
            'from_url' => 'Загрузить с URL',
            'url' => 'URL-адрес',
            'drop_files' => 'Перетащите файлы в данную область для загрузки',
            'success' => 'Файлы успешно загружены',
            'failed' => 'Не удалось загрузить файлы',
            'header' => 'Загрузка файлов',
            'error' => 'Произошла ошибка при загрузке файлов',
        ],
        'rename' => [
            'title' => 'Переименовать',
            'file_name' => 'Имя файла',
            'notification' => 'Файл переименован',
        ],
        'move' => [
            'title' => 'Переместить',
            'directory' => 'Каталог',
            'directory_hint' => 'Введите новую директорию, относительно текущей директории.',
            'new_location' => 'Новое местоположение',
            'new_location_hint' => 'Введите расположение этого файла или папки, относительно текущей директории.',
            'notification' => 'Файл перемещён',
            'bulk_notification' => ':count файлов были перемещены в :directory',
        ],
        'permissions' => [
            'title' => 'Права доступа',
            'read' => 'Чтение',
            'write' => 'Запись',
            'execute' => 'Выполнение',
            'owner' => 'Владелец',
            'group' => 'Группа',
            'public' => 'Публичный',
            'notification' => 'Права изменены на :mode',
        ],
        'archive' => [
            'title' => 'Архивировать',
            'archive_name' => 'Имя архива',
            'notification' => 'Архив создан',
            'extension' => 'Расширение',
        ],
        'unarchive' => [
            'title' => 'Разархивировать',
            'notification' => 'Разархивирование завершено',
        ],
        'new_file' => [
            'title' => 'Новый файл',
            'file_name' => 'Имя нового файла',
            'syntax' => 'Выделить синтаксис',
            'create' => 'Создать',
        ],
        'new_folder' => [
            'title' => 'Новая папка',
            'folder_name' => 'Название новой папки',
        ],
        'nested_search' => [
            'title' => 'Вложенный поиск',
            'search_term' => 'Поиск по выражению',
            'search_term_placeholder' => 'Введите слово для поиска, напр. *.txt',
            'search' => 'Поиск',
            'search_for_term' => 'Поиск :term',
        ],
        'delete' => [
            'notification' => 'Файл Удалён',
            'bulk_notification' => ':count файлов были удалены',
        ],
        'edit' => [
            'title' => 'Редактирование: :file',
            'save_close' => 'Сохранить и закрыть',
            'save' => 'Сохранить',
            'cancel' => 'Отмена',
            'notification' => 'Файл сохранен',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> слишком большой!',
            'body' => 'Максимум — :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> не найден!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> является каталогом',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> уже существует!',
        ],
        'files_node_error' => [
            'title' => 'Не удалось загрузить файлы!',
        ],
        'pelicanignore' => [
            'title' => 'Вы редактируете файл <code>.pelicanignore</code>!',
            'body' => 'Все файлы и каталоги, перечисленные здесь, будут исключены из резервных копий. Подстановочные знаки поддерживаются с помощью символа звёздочки (<code>*</code>).<br>Вы можете отменить предыдущее правило, добавив восклицательный знак в начале (<code>!</code>).',
        ],
    ],
];
