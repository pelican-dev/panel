<?php

return [
    'title' => '面板安裝程式',
    'requirements' => [
        'title' => '伺服器需求',
        'sections' => [
            'version' => [
                'title' => 'PHP 版本',
                'or_newer' => ':version 或更新版本',
                'content' => '您的 PHP 版本為 :version。',
            ],
            'extensions' => [
                'title' => 'PHP 擴充功能',
                'good' => '所有需要的 PHP 擴充功能已安裝。',
                'bad' => '缺少以下 PHP 擴充功能：:extensions',
            ],
            'permissions' => [
                'title' => '資料夾權限',
                'good' => '所有資料夾都擁有正確的權限。',
                'bad' => '以下資料夾權限錯誤：:folders',
            ],
        ],
        'exception' => '缺少部分需求',
    ],
    'environment' => [
        'title' => '環境',
        'fields' => [
            'app_name' => '應用程式名稱',
            'app_name_help' => '這將是您的面板名稱。',
            'app_url' => '應用程式 URL',
            'app_url_help' => '這將是您存取面板的 URL。',
            'account' => [
                'section' => '管理員使用者',
                'email' => '電子郵件',
                'username' => '使用者名稱',
                'password' => '密碼',
            ],
        ],
    ],
    'database' => [
        'title' => '資料庫',
        'driver' => '資料庫驅動程式',
        'driver_help' => '用於面板資料庫的驅動程式。我們建議使用 "SQLite"。',
        'fields' => [
            'host' => '資料庫主機',
            'host_help' => '您的資料庫主機。請確保它可以被存取。',
            'port' => '資料庫連接埠',
            'port_help' => '您的資料庫連接埠。',
            'path' => '資料庫路徑',
            'path_help' => '您的 .sqlite 檔案相對於 database 資料夾的路徑。',
            'name' => '資料庫名稱',
            'name_help' => '面板資料庫的名稱。',
            'username' => '資料庫使用者名稱',
            'username_help' => '您的資料庫使用者名稱。',
            'password' => '資料庫密碼',
            'password_help' => '您的資料庫使用者密碼。可以留空。',
        ],
        'exceptions' => [
            'connection' => '資料庫連線失敗',
            'migration' => '遷移失敗',
        ],
    ],
    'egg' => [
        'title' => 'Eggs',
        'no_eggs' => '沒有可用的 Eggs',
        'background_install_started' => 'Egg 安裝已開始',
        'background_install_description' => ':count 個 Egg 的安裝已排入佇列，並將在背景繼續執行。',
        'exceptions' => [
            'failed_to_update' => '更新 Egg 索引失敗',
            'no_eggs' => '目前沒有可用的 Egg 可供安裝。',
            'installation_failed' => '安裝選定的 Egg 失敗。請在安裝後透過 Egg 列表匯入它們。',
        ],
    ],
    'session' => [
        'title' => '工作階段 (Session)',
        'driver' => '工作階段驅動程式',
        'driver_help' => '用於儲存工作階段的驅動程式。我們建議使用 "Filesystem" 或 "Database"。',
    ],
    'cache' => [
        'title' => '快取',
        'driver' => '快取驅動程式',
        'driver_help' => '用於快取的驅動程式。我們建議使用 "Filesystem"。',
        'fields' => [
            'host' => 'Redis 主機',
            'host_help' => '您的 Redis 伺服器主機。請確保它可以被存取。',
            'port' => 'Redis 連接埠',
            'port_help' => '您的 Redis 伺服器連接埠。',
            'username' => 'Redis 使用者名稱',
            'username_help' => '您的 Redis 使用者名稱。可以留空',
            'password' => 'Redis 密碼',
            'password_help' => '您的 Redis 使用者密碼。可以留空。',
        ],
        'exception' => 'Redis 連線失敗',
    ],
    'queue' => [
        'title' => '佇列',
        'driver' => '佇列驅動程式',
        'driver_help' => '用於處理佇列的驅動程式。我們建議使用 "Database"。',
        'fields' => [
            'done' => '我已經完成了以下兩個步驟。',
            'done_validation' => '您需要完成兩個步驟才能繼續！',
            'crontab' => '執行以下指令以設定您的 crontab。請注意，<code>www-data</code> 是您的網頁伺服器使用者。在某些系統上，此使用者名稱可能不同！',
            'service' => '要設定佇列工作者服務，您只需執行以下指令。',
        ],
    ],
    'exceptions' => [
        'write_env' => '無法寫入 .env 檔案',
        'migration' => '無法執行遷移',
        'create_user' => '無法建立管理員使用者',
    ],
    'next_step' => '下一步',
    'finish' => '完成',
];
