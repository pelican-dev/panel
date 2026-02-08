<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => '請提供此面板匯出 Eggs 時所使用的寄件人電子郵件。這必須是一個有效的電子郵件。',
            'url' => '應用程式網址必須以 https:// 或 http:// 開頭，依照你是否使用 SSL 而定。如果未包含這個開頭，郵件與其他內容中的連結將會導向錯誤的位置。',
            'timezone' => '時區應符合 PHP 支援的時區格式。如不確定，請參考：https://php.net/manual/en/timezones.php。',
        ],
        'redis' => [
            'note' => '「你已為一個或多個選項選擇使用 Redis ，請在下方提供有效的連線資訊。大多數情況下，除非你有自行修改設定，否則可使用預設值。',
            'comment' => '預設情況下，Redis 伺服器執行於本機，無法從外部存取，使用者名稱為 default，且沒有密碼。如果你的情況也是如此，請直接按 Enter 鍵，不需輸入任何值。',
            'confirm' => '看起來 Redis 已經定義了 :field，你想要變更它嗎？',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => '強烈建議不要將資料庫主機設定為 \'localhost\'，因為這常會導致 Socket 連線問題。如果你要使用本機連線，應改用 "127.0.0.1"。',
        'DB_USERNAME_note' => '使用 root 帳號連接 MySQL 是極度不建議的做法，且本應用程式也不允許這麼做。你必須為此面板專門建立一個 MySQL 使用者帳號。',
        'DB_PASSWORD_note' => '你似乎已經設定了 MySQL 連線密碼，是否要變更它？',
        'DB_error_2' => '你的連線憑證尚未儲存。請先提供有效的連線資訊才能繼續。',
        'go_back' => '返回並重試',
    ],
    'make_node' => [
        'name' => '輸入一個簡短的識別名稱，用於區分此節點與其他節點',
        'description' => '輸入一段描述，用來辨識此節點',
        'scheme' => '請輸入 https 以使用 SSL，或輸入 http 以不使用 SSL',
        'fqdn' => '請輸入用來連接守護程式的網域名稱（例如：node.example.com）。若此節點未使用 SSL，也可以使用 IP 位址。',
        'public' => '此節點是否應設為公開？請注意，若設為私有，將無法自動部署至此節點。',
        'behind_proxy' => '你的 FQDN 是否在代理伺服器後運作？',
        'maintenance_mode' => '是否啟用維護模式？',
        'memory' => '輸入可用的最大記憶體容量',
        'memory_overallocate' => '輸入要超額分配的記憶體大小百分比。若要停用超額分配檢查，請輸入 -1；若輸入 0，則當可能超出此節點的記憶體總上限時，將會阻止建立新伺服器。',
        'disk' => '輸入可用的最大磁碟空間容量',
        'disk_overallocate' => '輸入要超額分配的磁碟空間容量百分比。若要停用超額分配檢查，請輸入 -1；若輸入 0，則當可能超出此節點的磁碟空間總上限時，將會阻止建立新伺服器。',
        'cpu' => '輸入可用的最大cpu使用率',
        'cpu_overallocate' => '輸入要超額分配的cpu使用率百分比。若要停用超額分配檢查，請輸入 -1；若輸入 0，則當可能超出此節點的cpu使用率總上限時，將會阻止建立新伺服器。',
        'upload_size' => '輸入最大上傳檔案大小',
        'daemonListen' => '輸入守護程式監聽的連接埠',
        'daemonConnect' => '輸入守護進程連接阜（可與監聽連接阜相同）',
        'daemonSFTP' => '輸入守護程式 SFTP 監聽的連接埠',
        'daemonSFTPAlias' => '輸入守護程式 SFTP 別名（可留空）',
        'daemonBase' => '輸入根資料夾',
        'success' => '成功建立名稱為 :name、ID 為 :id 的新節點',
    ],
    'node_config' => [
        'error_not_exist' => '所選的節點不存在。',
        'error_invalid_format' => '指定的格式無效。有效的選項為 yaml 和 json。',
    ],
    'key_generate' => [
        'error_already_exist' => '你已設定應用程式加密金鑰。繼續此流程將會覆寫該金鑰，並導致現有加密資料損毀。除非你完全了解後果，否則請勿繼續。',
        'understand' => '我了解執行此指令的後果，並願意承擔所有因加密資料遺失所產生的責任。',
        'continue' => '你確定要繼續嗎？更改應用程式加密金鑰將會導致資料遺失。',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => '目前沒有需要執行的伺服器排程任務。',
            'error_message' => '處理排程時發生錯誤： ',
        ],
    ],
];
