<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => '請提供由此面板匯出的 Egg 的建立者電子郵件地址。這必須是一個有效的電子郵件地址。',
            'url' => '應用程式 URL 必須以 https:// 或 http:// 開頭，取決於您是否使用 SSL。如果您不包含通訊協定，您的電子郵件和其他內容將連結到錯誤的位置。',
            'timezone' => "時區應符合 PHP 支援的時區之一。如果您不確定，請參考 https://php.net/manual/en/timezones.php。",
        ],
        'redis' => [
            'note' => '您為一個或多個選項選擇了 Redis 驅動程式，請在下方提供有效的連線資訊。大多數情況下，除非您修改了設定，否則可以使用預設值。',
            'comment' => '預設情況下，Redis 伺服器實例的使用者名稱為 default 且沒有密碼，因為它是在本機執行且無法從外部存取。如果是這種情況，只需按 Enter 鍵即可，無需輸入值。',
            'confirm' => '似乎已經為 Redis 定義了 :field，您想要更改它嗎？',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => '強烈建議不要將 "localhost" 用作資料庫主機，因為我們經常看到 socket 連線問題。如果您想使用本機連線，應該使用 "127.0.0.1"。',
        'DB_USERNAME_note' => "使用 root 帳戶進行 MySQL 連線不僅極不推薦，此應用程式也不允許這樣做。您需要為此軟體建立一個 MySQL 使用者。",
        'DB_PASSWORD_note' => '看來您已經定義了 MySQL 連線密碼，您想要更改它嗎？',
        'DB_error_2' => '您的連線憑證尚未儲存。在繼續之前，您需要提供有效的連線資訊。',
        'go_back' => '返回並重試',
    ],
    'make_node' => [
        'name' => '輸入一個簡短的識別碼，用於區分此節點與其他節點',
        'description' => '輸入描述以識別節點',
        'scheme' => '請輸入 https (使用 SSL) 或 http (非 SSL 連線)',
        'fqdn' => '輸入用於連接守護程序 (Daemon) 的網域名稱 (例如 node.example.com)。僅當此節點不使用 SSL 時才可以使用 IP 位址',
        'public' => '此節點應該是公開的嗎？請注意，將節點設定為私有將會拒絕自動部署到此節點的能力。',
        'behind_proxy' => '您的 FQDN 是否位於代理伺服器後面？',
        'maintenance_mode' => '是否應啟用維護模式？',
        'memory' => '輸入記憶體最大量',
        'memory_overallocate' => '輸入允許超額分配的記憶體量，-1 將停用檢查，0 將阻止建立新伺服器',
        'disk' => '輸入磁碟空間最大量',
        'disk_overallocate' => '輸入允許超額分配的磁碟量，-1 將停用檢查，0 將阻止建立新伺服器',
        'cpu' => '輸入 CPU 最大量',
        'cpu_overallocate' => '輸入允許超額分配的 CPU 量，-1 將停用檢查，0 將阻止建立新伺服器',
        'upload_size' => '輸入最大檔案上傳大小',
        'daemonListen' => '輸入守護程序監聽埠',
        'daemonConnect' => '輸入守護程序連接埠 (可與監聽埠相同)',
        'daemonSFTP' => '輸入守護程序 SFTP 監聽埠',
        'daemonSFTPAlias' => '輸入守護程序 SFTP 別名 (可留空)',
        'daemonBase' => '輸入基礎資料夾',
        'success' => '成功建立了一個名稱為 :name 且 ID 為 :id 的新節點',
    ],
    'node_config' => [
        'error_not_exist' => '所選節點不存在。',
        'error_invalid_format' => '指定的格式無效。有效選項為 yaml 和 json。',
    ],
    'key_generate' => [
        'error_already_exist' => '看來您已經設定了應用程式加密金鑰。繼續此過程將覆蓋該金鑰並導致任何現有加密資料損壞。除非您知道自己在做什麼，否則不要繼續。',
        'understand' => '我了解執行此指令的後果，並承擔遺失加密資料的所有責任。',
        'continue' => '您確定要繼續嗎？更改應用程式加密金鑰將導致資料遺失。',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => '沒有需要執行的伺服器排程任務。',
            'error_message' => '處理排程時發生錯誤：:schedules',
        ],
    ],
];
