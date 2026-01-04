<?php

return [
    'user' => [
        'search_users' => '輸入使用者名稱、使用者 ID 或電子郵件地址',
        'select_search_user' => '要刪除的使用者 ID (輸入 \'0\' 重新搜尋)',
        'deleted' => '使用者已成功從面板中刪除。',
        'confirm_delete' => '您確定要從面板中刪除此使用者嗎？',
        'no_users_found' => '找不到符合搜尋條件的使用者。',
        'multiple_found' => '找到多個使用者帳戶，由於 --no-interaction 標誌，無法刪除使用者。',
        'ask_admin' => '此使用者是管理員嗎？',
        'ask_email' => '電子郵件地址',
        'ask_username' => '使用者名稱',
        'ask_password' => '密碼',
        'ask_password_tip' => '如果您想建立一個隨機密碼並發送給使用者的帳戶，請重新執行此指令 (CTRL+C) 並傳遞 `--no-password` 標誌。',
        'ask_password_help' => '密碼長度必須至少為 8 個字元，並且包含至少一個大寫字母和數字。',
        '2fa_help_text' => '如果使用者的帳戶啟用了兩步驟驗證，此指令將停用它。這僅應在使用者被鎖定在帳戶之外時作為帳戶復原指令使用。如果這不是您想做的，請按 CTRL+C 退出此程序。',
        '2fa_disabled' => ':email 的兩步驟驗證已停用。',
    ],
    'schedule' => [
        'output_line' => '正在為 `:schedule` (:id) 中的第一個任務分派工作。',
    ],
    'maintenance' => [
        'deleting_service_backup' => '正在刪除服務備份檔案 :file。',
    ],
    'server' => [
        'rebuild_failed' => '節點 ":node" 上 ":name" (#:id) 的重建請求失敗，錯誤：:message',
        'reinstall' => [
            'failed' => '節點 ":node" 上 ":name" (#:id) 的重新安裝請求失敗，錯誤：:message',
            'confirm' => '您即將對一組伺服器進行重新安裝。您希望繼續嗎？',
        ],
        'power' => [
            'confirm' => '您即將對 :count 個伺服器執行 :action。您希望繼續嗎？',
            'action_failed' => '節點 ":node" 上 ":name" (#:id) 的電源動作請求失敗，錯誤：:message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP 主機 (例如 smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP 連接埠',
            'ask_smtp_username' => 'SMTP 使用者名稱',
            'ask_smtp_password' => 'SMTP 密碼',
            'ask_mailgun_domain' => 'Mailgun 網域',
            'ask_mailgun_endpoint' => 'Mailgun 端點',
            'ask_mailgun_secret' => 'Mailgun 密鑰',
            'ask_mandrill_secret' => 'Mandrill 密鑰',
            'ask_postmark_username' => 'Postmark API 金鑰',
            'ask_driver' => '應該使用哪個驅動程式發送電子郵件？',
            'ask_mail_from' => '電子郵件的寄件人地址',
            'ask_mail_name' => '電子郵件的寄件人名稱',
            'ask_encryption' => '要使用的加密方法',
        ],
    ],
];
