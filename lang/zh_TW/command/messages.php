<?php

return [
    'user' => [
        'search_users' => '輸入使用者名稱、使用者 ID 或電子郵件地址',
        'select_search_user' => '要刪除的使用者 ID（輸入 \'0\' 可重新搜尋）',
        'deleted' => '已成功刪除使用者。',
        'confirm_delete' => '你確定要從控制面板中刪除這位使用者嗎？',
        'no_users_found' => '找不到符合搜尋條件的使用者。',
        'multiple_found' => '找到多個符合的帳號，因為使用了 --no-interaction 參數，無法進行刪除。',
        'ask_admin' => '此使用者是否為管理員？',
        'ask_email' => '電子郵件地址',
        'ask_username' => '使用者名稱',
        'ask_password' => '密碼',
        'ask_password_tip' => '如果你想建立一個帳號並讓系統隨機產生密碼後寄送給使用者，請重新運行此指令（按 CTRL+C）並加上 --no-password 參數。',
        'ask_password_help' => '密碼必須至少 8 個字元，並包含至少一個大寫字母和一個數字。',
        '2fa_help_text' => '此指令將會停用使用者帳號的雙重驗證。僅建議在使用者無法存取帳號時，作為帳號復原用途使用。如果這不是您想要執行的操作，請按 CTRL+C 退出此程序。',
        '2fa_disabled' => '已停用 :email 的雙重驗證功能。',
    ],
    'schedule' => [
        'output_line' => '正在為 :schedule（:id）中的第一個任務排程作業。',
    ],
    'maintenance' => [
        'deleting_service_backup' => '正在刪除服務備份檔案 :file。',
    ],
    'server' => [
        'rebuild_failed' => '對 ":name"（#:id）執行重新建構請求時，在節點 ":node" 發生錯誤：:message。',
        'reinstall' => [
            'failed' => '對 ":name"（#:id）執行重新安裝請求時，在節點 ":node" 發生錯誤：:message。',
            'confirm' => '你即將對一組伺服器執行重新安裝操作，是否確定繼續？',
        ],
        'power' => [
            'confirm' => '你即將對 :count 台伺服器執行 :action 操作，確定要繼續嗎？',
            'action_failed' => '對 \':name\'（#:id）執行電源操作請求時，在節點 \':node\' 發生錯誤：:message。',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP 主機（例如：smtp.gmail.com）',
            'ask_smtp_port' => 'SMTP 端口',
            'ask_smtp_username' => 'SMTP 使用者名稱',
            'ask_smtp_password' => 'SMTP 密碼',
            'ask_mailgun_domain' => 'Mailgun 域名',
            'ask_mailgun_endpoint' => 'Mailgun API 端點網址',
            'ask_mailgun_secret' => 'Mailgun 密鑰',
            'ask_mandrill_secret' => 'Mandrill 密鑰',
            'ask_postmark_username' => 'Postmark API 密鑰',
            'ask_driver' => '要使用哪一種郵件傳送方式？',
            'ask_mail_from' => '寄件者電子郵件',
            'ask_mail_name' => '寄件人名稱',
            'ask_encryption' => '加密方式',
        ],
    ],
];
