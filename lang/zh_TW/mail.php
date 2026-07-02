<?php

return [
    'greeting' => '您好 :name！',

    'account_created' => [
        'body' => '您會收到這封電子郵件，是因為您在 :app 上已建立了一個帳號。',
        'username' => '使用者名稱：:username',
        'email' => '電子郵件：:email',
        'action' => '設定您的帳號',
    ],

    'added_to_server' => [
        'body' => '您已被新增為以下伺服器的子使用者，可讓您對該伺服器擁有特定控制權。',
        'server_name' => '伺服器名稱：:name',
        'action' => '造訪伺服器',
    ],

    'removed_from_server' => [
        'body' => '您已被移除以下伺服器的子使用者身分。',
        'server_name' => '伺服器名稱：:name',
        'action' => '造訪面板',
    ],

    'server_installed' => [
        'body' => '您的伺服器已完成安裝，現在可供您使用。',
        'server_name' => '伺服器名稱：:name',
        'action' => '登入並開始使用',
    ],

    'backup_completed' => [
        'body_success' => '備份建立成功。',
        'body_failed' => '備份建立失敗。',
        'backup_name' => '備份名稱：:name',
        'server_name' => '伺服器名稱：:name',
        'action' => '檢視備份',
    ],

    'mail_tested' => [
        'subject' => '面板測試訊息',
        'body' => '這是面板郵件系統的測試。一切運作正常！',
    ],
];
