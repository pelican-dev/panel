<?php

return [
    'user' => [
        'search_users' => '輸入用戶名、用戶ID或電子郵件地址',
        'select_search_user' => '選擇要刪除的用戶ID（輸入\'0\'重新搜索）',
        'deleted' => '用戶已成功從面板中刪除。',
        'confirm_delete' => '您確定要從面板中刪除此用戶嗎？',
        'no_users_found' => '未找到符合搜索條件的用戶。',
        'multiple_found' => '找到多個符合條件的用戶帳戶，由於啟用了--no-interaction標誌，無法刪除用戶。',
        'ask_admin' => '此用戶是否為管理員？',
        'ask_email' => '電子郵件地址',
        'ask_username' => '用戶名',
        'ask_password' => '密碼',
        'ask_password_tip' => '如果您想創建一個隨機密碼並通過電子郵件發送給用戶，請重新運行此命令（按CTRL+C）並傳遞`--no-password`標誌。',
        'ask_password_help' => '密碼長度必須至少為8個字符，且包含至少一個大寫字母和數字。',
        '2fa_help_text' => [
            '此命令將禁用用戶帳戶的雙因素認證（如果已啟用）。此功能僅應用於帳戶恢復，當用戶被鎖定在帳戶外時使用。',
            '如果您不想執行此操作，請按CTRL+C退出。',
        ],
        '2fa_disabled' => '已為:email禁用雙因素認證。',
    ],
    'schedule' => [
        'output_line' => '正在為計劃任務`:schedule`(:id)分派第一個任務。',
    ],
    'maintenance' => [
        'deleting_service_backup' => '正在刪除服務備份文件:file。',
    ],
    'server' => [
        'rebuild_failed' => '節點":node"上的伺服器":name"(#:id)重建請求失敗，錯誤信息: :message',
        'reinstall' => [
            'failed' => '節點":node"上的伺服器":name"(#:id)重新安裝請求失敗，錯誤信息: :message',
            'confirm' => '您即將對一組伺服器執行重新安裝操作。是否繼續？',
        ],
        'power' => [
            'confirm' => '您即將對:count台伺服器執行:action操作。是否繼續？',
            'action_failed' => '節點":node"上的伺服器":name"(#:id)電源操作請求失敗，錯誤信息: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP主機（例如smtp.gmail.com）',
            'ask_smtp_port' => 'SMTP端口',
            'ask_smtp_username' => 'SMTP用戶名',
            'ask_smtp_password' => 'SMTP密碼',
            'ask_mailgun_domain' => 'Mailgun域名',
            'ask_mailgun_endpoint' => 'Mailgun端點',
            'ask_mailgun_secret' => 'Mailgun密鑰',
            'ask_mandrill_secret' => 'Mandrill密鑰',
            'ask_postmark_username' => 'Postmark API密鑰',
            'ask_driver' => '應使用哪種驅動程序發送電子郵件？',
            'ask_mail_from' => '電子郵件的發件人地址',
            'ask_mail_name' => '電子郵件的發件人名稱',
            'ask_encryption' => '使用的加密方法',
        ],
    ],
];