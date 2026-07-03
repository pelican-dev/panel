<?php

return [
    'user' => [
        'search_users' => '輸入使用者名稱、使用者 ID 或電子郵件位址',
        'select_search_user' => '要刪除的使用者 ID（輸入 \'0\' 以重新搜尋）',
        'deleted' => '已成功從面板刪除使用者。',
        'confirm_delete' => '您確定要從面板刪除此使用者嗎？',
        'no_users_found' => '找不到符合所提供搜尋字詞的使用者。',
        'multiple_found' => '為所提供的使用者找到了多個帳號，由於使用了 --no-interaction 標記，無法刪除使用者。',
        'ask_admin' => '此使用者是管理員嗎？',
        'ask_email' => '電子郵件位址',
        'ask_username' => '使用者名稱',
        'ask_password' => '密碼',
        'ask_password_tip' => '如果您想要建立一個帶有隨機密碼並透過電子郵件發送給使用者的帳號，請重新執行此指令（CTRL+C）並傳遞 `--no-password` 標記。',
        'ask_password_help' => '密碼長度必須至少為 8 個字元，並包含至少一個大寫字母與數字。',
        '2fa_help_text' => '如果已啟用，此指令將會停用使用者帳號的雙重身分驗證。這僅應在使用者被鎖定在帳號外時，作為帳號復原指令使用。如果這不是您想要執行的操作，請按 CTRL+C 以退出此程序。',
        '2fa_disabled' => '已為 :email 停用雙重身分驗證。',
    ],
    'schedule' => [
        'output_line' => '正在分派 `:schedule` (:id) 中的第一個任務作業。',
    ],
    'maintenance' => [
        'deleting_service_backup' => '正在刪除服務備份檔案 :file。',
    ],
    'server' => [
        'rebuild_failed' => '節點「:node」上「:name」(#:id) 的重建請求失敗，錯誤為：:message',
        'reinstall' => [
            'failed' => '節點「:node」上「:name」(#:id) 的重新安裝請求失敗，錯誤為：:message',
            'confirm' => '您即將對一組伺服器進行重新安裝。您希望繼續嗎？',
        ],
        'power' => [
            'confirm' => '您即將對 :count 台伺服器執行 :action 操作。您希望繼續嗎？',
            'action_failed' => '節點「:node」上「:name」(#:id) 的電源操作請求失敗，錯誤為：:message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP 主機（例如 smtp.gmail.com）',
            'ask_smtp_port' => 'SMTP 連接埠',
            'ask_smtp_username' => 'SMTP 使用者名稱',
            'ask_smtp_password' => 'SMTP 密碼',
            'ask_mailgun_domain' => 'Mailgun 網域',
            'ask_mailgun_endpoint' => 'Mailgun 端點',
            'ask_mailgun_secret' => 'Mailgun 秘密金鑰',
            'ask_mandrill_secret' => 'Mandrill 秘密金鑰',
            'ask_postmark_username' => 'Postmark API 金鑰',
            'ask_driver' => '應使用哪個驅動程式來傳送電子郵件？',
            'ask_mail_from' => '電子郵件應發送自的電子郵件位址',
            'ask_mail_name' => '電子郵件應顯示的發件人名稱',
            'ask_encryption' => '要使用的加密方法',
        ],
    ],
];
