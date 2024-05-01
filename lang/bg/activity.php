<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Провален опит за вход',
        'success' => 'Успешно влезна',
        'password-reset' => 'Нулиране на парола',
        'reset-password' => 'Поискано нулиране на парола',
        'checkpoint' => 'Поискано дву-факторно удостоверяване',
        'recovery-token' => 'Използван дву-факторен токен за възстановяване',
        'token' => 'Решено дву-факторно предизвикателство',
        'ip-blocked' => 'Блокирана заявка от непосочен IP адрес за :identifier',
        'sftp' => [
            'fail' => 'Провален SFTP вход',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Сменен имейл от :old на :new',
            'password-changed' => 'Сменена парола',
        ],
        'api-key' => [
            'create' => 'Създаден нов API ключ :identifier',
            'delete' => 'Изтрит API ключ :identifier',
        ],
        'ssh-key' => [
            'create' => 'Добавен SSH ключ :fingerprint на акаунт',
            'delete' => 'Махнат SSH ключ :fingerprint от акаунт',
        ],
        'two-factor' => [
            'create' => 'Активирано дву-факторно удостоверяване',
            'delete' => 'Деактивирано дву-факторно удостоверяване',
        ],
    ],
    'server' => [
        'reinstall' => 'Реинсталиран сървър',
        'console' => [
            'command' => 'Изпълнен ":command" на сървъра',
        ],
        'power' => [
            'start' => 'Стартира сървъра',
            'stop' => 'Спря сървъра',
            'restart' => 'Рестартира сървъра',
            'kill' => 'Уби сървърния процес',
        ],
        'backup' => [
            'download' => 'Изтегли :name архив',
            'delete' => 'Изтри :name архив',
            'restore' => 'Restored the :name backup (deleted files: :truncate)',
            'restore-complete' => 'Успешно реставрира архива :name',
            'restore-failed' => 'Неуспешно се реставрира архива :name',
            'start' => 'Стартира нов архив :name',
            'complete' => 'Маркира архива :name като приключил',
            'fail' => 'Маркира архива :name като неуспешен',
            'lock' => 'Заключи архива :name',
            'unlock' => 'Отключи архива :name',
        ],
        'database' => [
            'create' => 'Създаде нова датабаза :name',
            'rotate-password' => 'Парола сменена за датабаза :name',
            'delete' => 'Изтри датабаза :name',
        ],
        'file' => [
            'compress_one' => 'Компресира :directory:file',
            'compress_other' => 'Компресире :count файла в :directory',
            'read' => 'Видя съдържанието на :file',
            'copy' => 'Създаде копие на :file',
            'create-directory' => 'Създаде папката :directory:name',
            'decompress' => 'Декомпресира :files в :directory',
            'delete_one' => 'Изтри :directory:files.0',
            'delete_other' => 'Изтри :count файла в :directory',
            'download' => 'Изтегли :file',
            'pull' => 'Изтегли отдалечен файл от :url до :directory',
            'rename_one' => 'Преименува :directory:files.0.from на :directory:files.0.to',
            'rename_other' => 'Преименува :count файла в :directory',
            'write' => 'Написа новo съдържание на :file',
            'upload' => 'Започна качването на файл',
            'uploaded' => 'Качи :directory:file',
        ],
        'sftp' => [
            'denied' => 'Блокора SFTP достъп заради разрешения',
            'create_one' => 'Създаде :files.0',
            'create_other' => 'Създаде :count нови файлове',
            'write_one' => 'Промени съдържанието на :files.0',
            'write_other' => 'Промени съдържанието на :count файла',
            'delete_one' => 'Изтри :files.0',
            'delete_other' => 'Изтри :count файла',
            'create-directory_one' => 'Създаде папката :files.0',
            'create-directory_other' => 'Създаде :count папки',
            'rename_one' => 'Преименува :files.0.from на files.0.to',
            'rename_other' => 'Преименува или премести :count файла',
        ],
        'allocation' => [
            'create' => 'Добави :allocation на сървъра',
            'notes' => 'Смени бележките за :allocation от ":old" на ":new"',
            'primary' => 'Сложи :allocation като главната алокация на сървъра',
            'delete' => 'Изтри алокацията :allocation',
        ],
        'schedule' => [
            'create' => 'Създаде графика :name',
            'update' => 'Актуализира графика :name',
            'execute' => 'Ръчно изпълни графика :name',
            'delete' => 'Изтри графика :name',
        ],
        'task' => [
            'create' => 'Създаде нова задача ":action" за графика :name',
            'update' => 'Актуализира задачата ":action" за графика :name',
            'delete' => 'Изтри задача за графика :name',
        ],
        'settings' => [
            'rename' => 'Преименува сървъра от :old на :new',
            'description' => 'Смени описанието на сървъра от :old на :new',
        ],
        'startup' => [
            'edit' => 'Changed the :variable variable from ":old" to ":new"',
            'image' => 'Смени Docker Image-a за сървъра от :old на :new',
        ],
        'subuser' => [
            'create' => 'Добави :email като подпотребител',
            'update' => 'Актуализира подпотребителските разрешения за :email',
            'delete' => 'Премахна :email като подпотребител',
        ],
    ],
];
