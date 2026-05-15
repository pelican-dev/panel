<?php

return [
    'greeting' => '你好 :name！',

    'account_created' => [
        'body' => '您收到此電子郵件是因為在 :app 上為您建立了一個帳戶。',
        'username' => '使用者名稱：:username',
        'email' => '電子郵件：:email',
        'action' => '設定您的帳戶',
    ],

    'added_to_server' => [
        'body' => '您已被新增為以下伺服器的子使用者，允許您對伺服器進行特定控制。',
        'server_name' => '伺服器名稱：:name',
        'action' => '存取伺服器',
    ],

    'removed_from_server' => [
        'body' => '您已從以下伺服器的子使用者中移除。',
        'server_name' => '伺服器名稱：:name',
        'action' => '存取 Panel',
    ],

    'server_installed' => [
        'body' => '您的伺服器已安裝完成，現在可以使用了。',
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
        'subject' => 'Panel 測試訊息',
        'body' => '這是 Panel 郵件系統的測試。系統一切正常！',
    ],
];
