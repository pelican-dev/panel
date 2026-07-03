<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => '請提供此面板匯出 Egg 時應使用的電子郵件位址。這必須是一個有效的電子郵件位址。',
            'url' => '應用程式網址必須以 https:// 或 http:// 開頭，取決於您是否使用 SSL。如果您不包含通訊協定，您的電子郵件和其他內容將連結至錯誤的位置。',
            'timezone' => '時區應符合 PHP 支援的時區之一。如果您不確定，請參考 https://php.net/manual/en/timezones.php。',
        ],
        'redis' => [
            'note' => '您已在一個或多個選項中選擇 Redis 驅動程式，請在下方提供有效的連線資訊。在大多數情況下，除非您修改了設定，否則您可以使用提供的預設值。',
            'comment' => '預設情況下，Redis 伺服器實例的使用者名稱為預設值，且沒有密碼，因為它是在本地執行且外部無法存取。如果是這種情況，只需按下 Enter 鍵，無需輸入任何值。',
            'confirm' => '似乎已為 Redis 定義了 :field，您想要變更它嗎？',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => '強烈建議不要使用「localhost」作為您的資料庫主機，因為我們發現這經常導致 socket 連線問題。如果您想使用本地連線，您應該使用「127.0.0.1」。',
        'DB_USERNAME_note' => '使用 root 帳號進行 MySQL 連線不僅非常不被允許，且此應用程式也禁止這樣做。您需要為此軟體建立一個 MySQL 使用者。',
        'DB_PASSWORD_note' => '您似乎已經定義了 MySQL 連線密碼，您想要變更它嗎？',
        'DB_error_2' => '您的連線憑證尚未儲存。您需要提供有效的連線資訊才能繼續。',
        'go_back' => '返回並重試',
    ],
    'make_node' => [
        'name' => '請輸入一個簡短的識別碼，用於將此節點與其他節點區分開來',
        'description' => '請輸入說明以識別節點',
        'scheme' => '請輸入 https 以使用 SSL，或輸入 http 以使用非 SSL 連線',
        'fqdn' => '請輸入網域名稱（例如 node.example.com）以用於連線至守護程序。僅當您在此節點不使用 SSL 時，才可使用 IP 位址',
        'public' => '此節點應該設為公開嗎？請注意，將節點設為私有將會拒絕自動部署至此節點的功能。',
        'behind_proxy' => '您的 FQDN 是否位於代理伺服器後方？',
        'maintenance_mode' => '是否應啟用維護模式？',
        'memory' => '請輸入最大記憶體量',
        'memory_overallocate' => '請輸入要超額分配的記憶體量，-1 將停用檢查，0 將阻止建立新伺服器',
        'disk' => '請輸入最大磁碟空間量',
        'disk_overallocate' => '請輸入要超額分配的磁碟量，-1 將停用檢查，0 將阻止建立新伺服器',
        'cpu' => '請輸入最大 CPU 量',
        'cpu_overallocate' => '請輸入要超額分配的 CPU 量，-1 將停用檢查，0 將阻止建立新伺服器',
        'upload_size' => '請輸入最大上傳檔案大小',
        'daemonListen' => '請輸入守護程序監聽連接埠',
        'daemonConnect' => '請輸入守護程序連線連接埠（可以與監聽連接埠相同）',
        'daemonSFTP' => '請輸入守護程序 SFTP 監聽連接埠',
        'daemonSFTPAlias' => '請輸入守護程序 SFTP 別名（可為空）',
        'daemonBase' => '請輸入基本資料夾',
        'success' => '已成功建立名為 :name 的新節點，其 ID 為 :id',
    ],
    'node_config' => [
        'error_not_exist' => '所選的節點不存在。',
        'error_invalid_format' => '指定的格式無效。有效的選項為 yaml 與 json。',
    ],
    'key_generate' => [
        'error_already_exist' => '您似乎已經設定了應用程式加密金鑰。繼續此程序將覆寫該金鑰，並導致任何現有加密資料毀損。除非您知道自己在做什麼，否則請勿繼續。',
        'understand' => '我了解執行此指令的後果，並承擔加密資料遺失的所有責任。',
        'continue' => '您確定要繼續嗎？變更應用程式加密金鑰將導致資料遺失。',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => '沒有需要執行的伺服器排程任務。',
            'error_message' => '處理排程 :schedules 時發生錯誤',
        ],
    ],
];
