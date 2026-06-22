<?php

return [
    'greeting' => '你好 :name！',

    'account_created' => [
        'body' => '您會收到這封電子郵件是因為在 :app 為您建立了一個帳戶。',
        'username' => '使用者名稱：:username',
        'email' => '電子郵件：:email',
        'action' => '設定您的帳戶',
    ],

    'added_to_server' => [
        'body' => '您已被新增為以下伺服器的子使用者，允許您對該伺服器擁有特定的控制權。',
        'server_name' => '伺服器名稱：:name',
        'action' => '前往伺服器',
    ],

    'removed_from_server' => [
        'body' => '您已從以下伺服器的子使用者中被移除。',
        'server_name' => '伺服器名稱：:name',
        'action' => '前往面板',
    ],

    'server_installed' => [
        'body' => '您的伺服器已經安裝完成，現在可供您使用。',
        'server_name' => '伺服器名稱：:name',
        'action' => '登入並開始使用',
    ],

    'backup_completed' => [
        'body_success' => '已成功建立備份。',
        'body_failed' => '建立備份失敗。',
        'backup_name' => '備份名稱：:name',
        'server_name' => '伺服器名稱：:name',
        'action' => '查看備份',
    ],

    'mail_tested' => [
        'subject' => '面板測試訊息',
        'body' => '這是面板郵件系統的測試。您的設定已準備就緒！',
    ],
];
