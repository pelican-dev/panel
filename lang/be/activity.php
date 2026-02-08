<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Не атрымалася аўтарызавацца',
        'success' => 'Увайшоў',
        'password-reset' => 'Скінуць пароль',
        'checkpoint' => 'Двухфактарная аўтэнтыфікацыя ўключана',
        'recovery-token' => 'Использован резервный код 2FA',
        'token' => 'Пройдена двухфакторная проверка',
        'ip-blocked' => 'Блакаваная заявка ад неўлічанага IP-адрасу для <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Не атрымалася аўтарызавацца',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Зменен ідэнтыфікатар карыстальніка з <b>:old</b> на <b>:new</b>',
            'email-changed' => 'Зменена электронная пошта з <b>:old</b> на <b>:new</b>',
            'password-changed' => 'Змяніць пароль',
        ],
        'api-key' => [
            'create' => 'Створаны новы API ключ <b>:identifier</b>',
            'delete' => 'Выдалены API ключ <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Дададзены SSH ключ <b>:fingerprint</b> да ўліковага запісу',
            'delete' => 'Выдалены SSH ключ <b>:fingerprint</b> з уліковага запісу',
        ],
        'two-factor' => [
            'create' => 'Включена двухфакторная авторизация',
            'delete' => 'Включена двухфакторная авторизация',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Выканана дзеянне <b>:command</b> на серверы',
        ],
        'power' => [
            'start' => 'Сервер запушчаны',
            'stop' => 'Сервер спынены',
            'restart' => 'Сервер перазапушчаны',
            'kill' => 'Працэс сервера завершаны',
        ],
        'backup' => [
            'download' => 'Спампавана рэзервовая копія <b>:name</b>',
            'delete' => 'Выдалена рэзервовая копія <b>:name</b>',
            'restore' => 'Адноўлена рэзервовая копія <b>:name</b> (выдаленыя файлы: <b>:truncate</b>)',
            'restore-complete' => 'Завершана аднаўленне рэзервовай копіі <b>:name</b>',
            'restore-failed' => 'Няўдалася завяршыць аднаўленне рэзервовай копіі <b>:name</b>',
            'start' => 'Пачата новая рэзервовая копія <b>:identifier</b>',
            'complete' => 'Рэзервовая копія <b>:name</b> адзначана як завершаная',
            'fail' => 'Рэзервовая копія <b>:name</b> адзначана як няўдалая',
            'lock' => 'Замкнута рэзервовая копія <b>:name</b>',
            'unlock' => 'Адкрылі рэзервовую копію <b>:name</b>',
            'rename' => 'Перайменаваны рэзервовы файл з "<b>:old_name</b>" у "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Створана новая база дадзеных <b>:name</b>',
            'rotate-password' => 'Пароль для базы даных <b>:name</b> зменены',
            'delete' => 'Выдалена база дадзеных <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Кампрэсаваны <b>:directory:files</b>|Кампрэсавана <b>:count</b> файлаў у <b>:directory</b>',
            'read' => 'Паглядзелі змесціва файла <b>:file</b>',
            'copy' => 'Створана копія файла <b>:file</b>',
            'create-directory' => 'Створана тэчка <b>:directory:name</b>',
            'decompress' => 'Распакоўка файла <b>:file</b> у <b>:directory</b>',
            'delete' => 'Выдалены <b>:directory:files</b>|Выдалены <b>:count</b> файлаў у <b>:directory</b>',
            'download' => 'Спампаваны файл <b>:file</b>',
            'pull' => 'Спампаваны файл з аддаленага сэрвера з <b>:url</b> у <b>:directory</b>',
            'rename' => 'Перамешчаны/ Пераназваны <b>:from</b> у <b>:to</b>|Перамешчаны/ Пераназваны <b>:count</b> файлаў у <b>:directory</b>',
            'write' => 'Запісаны новы кантэнт у файл <b>:file</b>',
            'upload' => 'Пачата загрузка файла',
            'uploaded' => 'Загружаны файл <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Блакаваная магчымасць доступу SFTP з-за правоў',
            'create' => 'Створаны <b>:files</b>|Створана <b>:count</b> новых файлаў',
            'write' => 'Заменен змест у <b>:files</b>|Зменены змест <b>:count</b> файлаў',
            'delete' => 'Выдалены <b>:files</b>|Выдалены <b>:count</b> файлы',
            'create-directory' => 'Створана папка <b>:files</b>|Створана <b>:count</b> папак',
            'rename' => 'Пераназваны <b>:from</b> у <b>:to</b>|Пераназваны або перамешчаны <b>:count</b> файлы',
        ],
        'allocation' => [
            'create' => 'Дададзена <b>:allocation</b> на сервер',
            'notes' => 'Абноўлены заўвагі для <b>:allocation</b> з <b>:old</b> на <b>:new</b>',
            'primary' => 'Усталявана <b>:allocation</b> як асноўная сетка для сервера',
            'delete' => 'Выдалена сетка <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'Створана задача <b>:name</b>',
            'update' => 'Абноўлена задача <b>:name</b>',
            'execute' => 'Уручную выканана задача <b>:name</b>',
            'delete' => 'Выдалена задача <b>:name</b>',
        ],
        'task' => [
            'create' => 'Створана новая дзеянне "<b>:action</b>" для задачы "<b>:name</b>"',
            'update' => 'Абноўлена дзеянне "<b>:action</b>" для задачы "<b>:name</b>".',
            'delete' => 'Выдалена дзеянне "<b>:action</b>" для задачы "<b>:name</b>"',
        ],
        'settings' => [
            'rename' => 'Пераназваны сервер з "<b>:old</b>" на "<b>:new</b>"',
            'description' => 'Змянёна апісанне сервера з "<b>:old</b>" на "<b>:new</b>"',
            'reinstall' => 'Сервер пераўсталяваны',
        ],
        'startup' => [
            'edit' => 'Змянёна зменная "<b>:variable</b>" з "<b>:old</b>" на "<b>:new</b>"',
            'image' => 'Абноўлены Docker-вобраз для сервера з "<b>:old</b>" на "<b>:new</b>"',
            'command' => 'Абноўлена каманда запуску для сервера з "<b>:old</b>" на "<b>:new</b>"',
        ],
        'subuser' => [
            'create' => 'Дададзены "<b>:email</b>" як падкарыстальнік',
            'update' => 'Абноўлены правы падкарыстальніка для "<b>:email</b>"',
            'delete' => 'Выдалены "<b>:email</b>" як падкарыстальнік',
        ],
        'crashed' => 'Сервер выйшаў з ладу',
    ],
];
