<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Не вдалося увійти',
        'success' => 'Увійшов',
        'password-reset' => 'Скидання пароля',
        'checkpoint' => 'Потрібна двофакторна автентифікація',
        'recovery-token' => 'Використано токен відновлення двофакторної автентифікації',
        'token' => 'Пройдено двофакторну аутентифікацію',
        'ip-blocked' => 'Заблоковано запит із невідомої IP-адреси для <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Помилка входу через SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Змінено ім\'я користувача з <b>:old</b> на <b>:new</b>',
            'email-changed' => 'Змінити адресу електронної пошти з <b>:old</b> на <b>:new</b>',
            'password-changed' => 'Змінено пароль',
        ],
        'api-key' => [
            'create' => 'Створено новий ключ API <b>:identifier</b>',
            'delete' => 'Видалено ключ API <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Додано SSH ключ <b>:fingerprint</b> для облікового запису',
            'delete' => 'Видалено SSH ключ <b>:fingerprint</b> для облікового запису',
        ],
        'two-factor' => [
            'create' => 'Увімкнено двофакторну аутентифікацію',
            'delete' => 'Вимкнено двофакторну аутентифікацію',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Виконано "<b>:command</b>" на сервері',
        ],
        'power' => [
            'start' => 'Сервер запущено',
            'stop' => 'Сервер зупинено',
            'restart' => 'Сервер перезапущено',
            'kill' => 'Завершено серверний процес',
        ],
        'backup' => [
            'download' => 'Завантажено резервну копію <b>:name</b>',
            'delete' => 'Видалено резервну копію <b>:name</b>',
            'restore' => 'Відновлено резервну копію <b>:name</b> (видалені файли: <b>:truncate</b>)',
            'restore-complete' => 'Завершено відновлення резервної копії <b>:name</b>',
            'restore-failed' => 'Не вдалося виконати відновлення резервної копії <b>:name</b>',
            'start' => 'Запущено створення резервної копії <b>:name</b>',
            'complete' => 'Позначено резервну копію <b>:name</b> як завершену',
            'fail' => 'Позначено резервну копію <b>:name</b> як невдалу',
            'lock' => 'Заблоковано резервну копію <b>:name</b>',
            'unlock' => 'Розблоковано резервну копію <b>:name</b>',
            'rename' => 'Перейменування резервної копії з "<b>:old_name</b>" на "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Створено нову базу даних <b>:name</b>',
            'rotate-password' => 'Пароль змінено для бази даних <b>:name</b>',
            'delete' => 'Видалено базу даних <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Стиснуто <b>:directory:files</b> | Стиснуто <b>:count</b> файлів у <b>:directory</b>',
            'read' => 'Переглянуто вміст <b>:file</b>',
            'copy' => 'Створено копію <b>:file</b>',
            'create-directory' => 'Створено каталог <b>:directory:name</b>',
            'decompress' => 'Розпаковано <b>:file</b> у <b>:directory</b>',
            'delete' => 'Видалено <b>:directory:files</b> | Видалено <b>:count</b> файлів у <b>:directory</b>',
            'download' => 'Завантажено <b>:file</b>',
            'pull' => 'Завантажено віддалений файл з <b>:url</b> до <b>:directory</b>',
            'rename' => 'Переміщено/перейменовано <b>:from</b> у <b>:to</b>|Переміщено/перейменовано <b>:count</b> файлів у <b>:directory</b>',
            'write' => 'Записано новий вміст у <b>:file</b>',
            'upload' => 'Розпочато завантаження файлу',
            'uploaded' => 'Завантажено <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Заблоковано доступ SFTP через обмеження прав',
            'create' => 'Створено <b>:files</b> | Створено <b>:count</b> нових файлів',
            'write' => 'Змінено вміст <b>:files</b> | Змінено вміст <b>:count</b> файлів',
            'delete' => 'Видалено <b>:files</b> | Видалено <b>:count</b> файлів',
            'create-directory' => 'Створено каталог <b>:files</b> | Створено <b>:count</b> каталогів',
            'rename' => 'Перейменовано <b>:from</b> у <b>:to</b> | Перейменовано або переміщено <b>:count</b> файлів',
        ],
        'allocation' => [
            'create' => 'Додано <b>:allocation</b> на сервер',
            'notes' => 'Оновлено нотатки для <b>:allocation</b> з "<b>:old</b>" на "<b>:new</b>"',
            'primary' => 'Встановлено <b>:allocation</b> як основний порт сервера',
            'delete' => 'Видалено <b>:allocation</b> порт',
        ],
        'schedule' => [
            'create' => 'Створено <b>:name</b> розклад',
            'update' => 'Оновлено <b>:name</b> розклад',
            'execute' => 'Вручну виконано розклад <b>:name</b>',
            'delete' => 'Видалено <b>:name</b> розклад',
        ],
        'task' => [
            'create' => 'Створено нове завдання "<b>:action</b>" для <b>:name</b>',
            'update' => 'Оновлено завдання "<b>:action</b>" для <b>:name</b>',
            'delete' => 'Видалено завдання "<b>:action</b>" для розкладу <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Перейменовано сервер з "<b>:old</b>" на "<b>:new</b>"',
            'description' => 'Змінено опис сервера з "<b>:old</b>" на "<b>:new</b>"',
            'reinstall' => 'Сервер перевстановлено',
        ],
        'startup' => [
            'edit' => 'Змінено параметр <b>:variable</b> з "<b>:old</b>" на "<b>:new</b>"',
            'image' => 'Оновлено Docker зображення для сервера з <b>:old</b> на <b>:new</b>',
            'command' => 'Оновлено Docker образ для сервера з <b>:old</b> на <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Додано <b>:email</b> як субкористувача',
            'update' => 'Оновлено дозволи субкористувача для <b>:email</b>',
            'delete' => 'Видалено <b>:email</b> як субкористувача',
        ],
        'crashed' => 'Сервер зазнав помилки',
    ],
];
