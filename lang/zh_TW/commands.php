<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => '提供從此 Panel 匯出的 Egg 應該使用的電子郵件地址。這應該是一個有效的電子郵件地址。',
            'url' => '應用程式 URL 必須以 https:// 或 http:// 開頭，具體取決於您是否使用 SSL。如果您不包含該協定 (scheme)，您的電子郵件和其他內容將連結到錯誤的位置。',
            'timezone' => "時區應該符合 PHP 支援的時區之一。如果您不確定，請參考 https://php.net/manual/en/timezones.php。",
        ],
        'redis' => [
            'note' => '您已經為一個或多個選項選擇了 Redis 驅動程式，請在下面提供有效的連線資訊。在大多數情況下，您可以使用提供的預設值，除非您修改了您的設定。',
            'comment' => '預設情況下，Redis 伺服器實例的使用者名稱是 default 並且沒有密碼，因為它在本地運行且外部無法存取。如果情況確實如此，只需按 Enter 鍵，無需輸入值。',
            'confirm' => '似乎已經為 Redis 定義了 :field，您想要更改它嗎？',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => '強烈建議不要使用 "localhost" 作為您的資料庫主機，因為我們經常看到 socket 連線問題。如果您想使用本地連線，您應該使用 "127.0.0.1"。',
        'DB_USERNAME_note' => "使用 root 帳戶進行 MySQL 連線不僅極不推薦，而且此應用程式也不允許。您需要為該軟體建立一個 MySQL 使用者。",
        'DB_PASSWORD_note' => '您似乎已經定義了 MySQL 連線密碼，您想要更改它嗎？',
        'DB_error_2' => '您的連線憑證未儲存。在繼續之前，您需要提供有效的連線資訊。',
        'go_back' => '返回並重試',
    ],
    'make_node' => [
        'name' => '輸入一個短識別碼，用於將此節點與其他節點區分開來',
        'description' => '輸入用於識別該節點的描述',
        'scheme' => '請輸入 https 用於 SSL 或 http 用於非 SSL 連線',
        'fqdn' => '輸入用於連接守護程序 (daemon) 的網域名稱 (例如 node.example.com)。僅當您未在此節點上使用 SSL 時才可以使用 IP 地址',
        'public' => '這個節點應該是公開的嗎？注意：將節點設定為私有將使您無法自動部署到該節點。',
        'behind_proxy' => '您的 FQDN 是否在代理伺服器後面？',
        'maintenance_mode' => '是否應該啟用維護模式？',
        'memory' => '輸入最大記憶體量',
        'memory_overallocate' => '輸入允許超額分配的記憶體量，-1 將停用檢查，0 將阻止建立新伺服器',
        'disk' => '輸入最大磁碟空間',
        'disk_overallocate' => '輸入允許超額分配的磁碟量，-1 將停用檢查，0 將阻止建立新伺服器',
        'cpu' => '輸入最大 CPU 量',
        'cpu_overallocate' => '輸入允許超額分配的 CPU 量，-1 將停用檢查，0 將阻止建立新伺服器',
        'upload_size' => '輸入最大檔案上傳大小',
        'daemonListen' => '輸入守護程序監聽連接埠',
        'daemonConnect' => '輸入守護程序連接埠 (可以與監聽連接埠相同)',
        'daemonSFTP' => '輸入守護程序 SFTP 監聽連接埠',
        'daemonSFTPAlias' => '輸入守護程序 SFTP 別名 (可以為空)',
        'daemonBase' => '輸入基礎資料夾',
        'success' => '成功建立了名稱為 :name 且 ID 為 :id 的新節點',
    ],
    'node_config' => [
        'error_not_exist' => '所選節點不存在。',
        'error_invalid_format' => '指定的格式無效。有效選項為 yaml 和 json。',
    ],
    'key_generate' => [
        'error_already_exist' => '您似乎已經設定了應用程式加密金鑰。繼續此過程將覆蓋該金鑰，並導致任何現有加密資料損壞。除非您知道自己在做什麼，否則請勿繼續。',
        'understand' => '我了解執行此命令的後果，並承擔遺失加密資料的所有責任。',
        'continue' => '您確定要繼續嗎？變更應用程式加密金鑰將導致資料遺失。',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => '沒有需要執行的伺服器排程任務。',
            'error_message' => '處理排程任務時發生錯誤: :schedules',
        ],
    ],
];
