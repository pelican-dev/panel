<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Не удалось войти в аккаунт',
        'success' => 'Успешный вход',
        'password-reset' => 'Пароль сброшен',
        'checkpoint' => 'Запрошена двухфакторная аутентификация',
        'recovery-token' => 'Использован резервный ключ 2FA',
        'token' => 'Двухфакторная проверка пройдена',
        'ip-blocked' => 'Заблокирован запрос с незарегистрированного IP для <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Неудачный вход в SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Сменено имя пользователя с <b>:old</b> на <b>:new</b>',
            'email-changed' => 'Смена почты с <b>:old</b> на <b>:new</b>',
            'password-changed' => 'Пароль изменён',
        ],
        'api-key' => [
            'create' => 'Создан API-ключ <b>:identifier</b>',
            'delete' => 'API-ключ <b>:identifier</b> удалён',
        ],
        'ssh-key' => [
            'create' => 'Добавлен SSH ключ <b>:fingerprint</b> на аккаунт',
            'delete' => 'SSH ключ <b>:fingerprint</b> удалён с аккаунта',
        ],
        'two-factor' => [
            'create' => 'Включено подтверждение через 2FA',
            'delete' => '2FA отключено',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Выполнил "<b>:command</b>" на сервере',
        ],
        'power' => [
            'start' => 'Запустил сервер',
            'stop' => 'Остановил сервер',
            'restart' => 'Перезапустил сервер',
            'kill' => 'Убил процесс сервера',
        ],
        'backup' => [
            'download' => 'Скачал бэкап <b>:name</b>',
            'delete' => 'Удалил бэкап <b>:name</b>',
            'restore' => 'Восстановил бэкап <b>:name</b> (удалённые файлы: <b>:truncate</b>)',
            'restore-complete' => 'Восстановление бэкапа <b>:name</b> завершено',
            'restore-failed' => 'Не удалось восстановить бэкап <b>:name</b>',
            'start' => 'Запустил новый бэкап <b>:name</b>',
            'complete' => 'Бэкап <b>:name</b> обозначен как завершённый',
            'fail' => 'Бэкап <b>:name</b> обозначен как неуспешный',
            'lock' => 'Заблокировал бэкап <b>:name</b>',
            'unlock' => 'Разблокировал бэкап <b>:name</b>',
            'rename' => 'Резервная копия была переименована с "<b>:old_name</b>" в "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Создал новую базу данных <b>:name</b>',
            'rotate-password' => 'Сбросил пароль базы данных <b>:name</b>',
            'delete' => 'Удалил базу данных <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Сжал <b>:directory:files</b>|Сжато <b>:count</b> файлов в <b>:directory</b>',
            'read' => 'Просмотрел содержимое <b>:file</b>',
            'copy' => 'Создал копию файла <b>:file</b>',
            'create-directory' => 'Создал директорию <b>:directory:name</b>',
            'decompress' => 'Распаковал <b>:file</b> в <b>:directory</b>',
            'delete' => 'Удалил <b>:directory:files</b>|Удалено <b>:count</b> файлов в <b>:directory</b>',
            'download' => 'Скачал <b>:file</b>',
            'pull' => 'Скачал удалённый файл по адресу <b>:url</b> в <b>:directory</b>',
            'rename' => 'Переместил/ Переименовал <b>:from</b> в <b>:to</b>|Переместил/ Переименовал <b>:count</b> файлов в директорию <b>:directory</b>',
            'write' => 'Обновил содержимое <b>:file</b>',
            'upload' => 'Начал выгрузку файла',
            'uploaded' => 'Загрузил <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Подключение по SFTP заблокировано из-за отсутствия разрешений',
            'create' => 'Создал <b>:files</b>|Создано <b>:count</b> новых файлов',
            'write' => 'Изменил содержимое <b>:files</b>|Изменено содержимое <b>:count</b> файлов',
            'delete' => 'Удалил <b>:files</b>|Удалено <b>:count</b> файлов',
            'create-directory' => 'Создал директорию <b>:files</b>|Создано <b>:count</b> директорий',
            'rename' => 'Переименовал <b>:from</b> в <b>:to</b>|Переименовано или перемещено <b>:count</b> файлов',
        ],
        'allocation' => [
            'create' => 'Добавил порт <b>:allocation</b> к серверу',
            'notes' => 'Обновил заметку порта <b>:allocation</b> с "<b>:old</b>" на "<b>:new</b>"',
            'primary' => 'Назначил порт <b>:allocation</b> как основной',
            'delete' => 'Удалил порт <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'Создал расписание <b>:name</b>',
            'update' => 'Изменил расписание <b>:name</b>',
            'execute' => 'Выполнил расписание <b>:name</b> вручную',
            'delete' => 'Удалил расписание <b>:name</b>',
        ],
        'task' => [
            'create' => 'Создал задачу "<b>:action</b>" в расписании <b>:name</b>',
            'update' => 'Обновил задачу "<b>:action</b>" в расписании <b>:name</b>',
            'delete' => 'Удалил задачу "<b>:action</b>" в расписании <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Переименовал сервер с "<b>:old</b>" на "<b>:new</b>"',
            'description' => 'Изменил описание сервера с "<b>:old</b>" на "<b>:new</b>"',
            'reinstall' => 'Переустановил сервер',
        ],
        'startup' => [
            'edit' => 'Изменил переменную <b>:variable</b> с "<b>:old</b>" на "<b>:new</b>"',
            'image' => 'Образ Docker обновлён с <b>:old</b> на <b>:new</b>',
            'command' => 'Обновлена команда запуска для сервера с <b>:old</b> на <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Добавил <b>:email</b> как субпользователя',
            'update' => 'Обновил права субпользователя <b>:email</b>',
            'delete' => 'Удалил <b>:email</b> из субпользователей',
        ],
        'crashed' => 'Сервер принудительно завершил процесс',
    ],
];
