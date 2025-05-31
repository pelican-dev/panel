<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => '提供此面板導出的Eggs應使用的電子郵件地址。此地址應為有效的電子郵件地址。',
            'url' => '應用程序URL必須以https://或http://開頭，具體取決於是否使用SSL。如果不包含協議，您的電子郵件和其他內容將鏈接到錯誤的位置。',
            'timezone' => "時區應與PHP支持的時區之一匹配。如果不確定，請參考https://php.net/manual/en/timezones.php。",
        ],
        'redis' => [
            'note' => '您已為一個或多個選項選擇了Redis驅動程序，請提供有效的連接信息。在大多數情況下，除非您修改了設置，否則可以使用提供的默認值。',
            'comment' => '默認情況下，Redis服務器實例的用戶名為default且沒有密碼，因為它在本地運行且無法從外部訪問。如果是這種情況，只需按Enter鍵而不輸入任何值。',
            'confirm' => '似乎Redis的:field已經定義，您要更改它嗎？',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => '強烈建議不要使用"localhost"作為數據庫主機，因為我們經常遇到套接字連接問題。如果您想使用本地連接，應使用"127.0.0.1"。',
        'DB_USERNAME_note' => "使用root帳戶進行MySQL連接不僅強烈不推薦，而且此應用程序也不允許。您需要為此軟件創建一個MySQL用戶。",
        'DB_PASSWORD_note' => '似乎您已經定義了MySQL連接密碼，您要更改它嗎？',
        'DB_error_2' => '您的連接憑證未保存。在繼續之前，您需要提供有效的連接信息。',
        'go_back' => '返回並重試',
    ],
    'make_node' => [
        'name' => '輸入一個簡短的標識符，用於區分此節點與其他節點',
        'description' => '輸入描述以標識節點',
        'scheme' => '請輸入https（使用SSL）或http（非SSL連接）',
        'fqdn' => '輸入用於連接守護程序的域名（例如node.example.com）。只有在不使用SSL時才能使用IP地址',
        'public' => '此節點是否公開？請注意，將節點設置為私有將拒絕自動部署到此節點。',
        'behind_proxy' => '您的FQDN是否在代理後面？',
        'maintenance_mode' => '是否啟用維護模式？',
        'memory' => '輸入最大內存',
        'memory_overallocate' => '輸入要超配的內存，-1將禁用檢查，0將阻止創建新伺服器',
        'disk' => '輸入最大磁盤空間',
        'disk_overallocate' => '輸入要超配的磁盤空間，-1將禁用檢查，0將阻止創建新伺服器',
        'cpu' => '輸入最大CPU',
        'cpu_overallocate' => '輸入要超配的CPU，-1將禁用檢查，0將阻止創建新伺服器',
        'upload_size' => '輸入最大文件上傳大小',
        'daemonListen' => '輸入守護程序監聽端口',
        'daemonSFTP' => '輸入守護程序SFTP監聽端口',
        'daemonSFTPAlias' => '輸入守護程序SFTP別名（可為空）',
        'daemonBase' => '輸入基礎文件夾',
        'success' => '成功創建了新節點，名為:name，ID為:id',
    ],
    'node_config' => [
        'error_not_exist' => '所選節點不存在。',
        'error_invalid_format' => '指定的格式無效。有效選項為yaml和json。',
    ],
    'key_generate' => [
        'error_already_exist' => '似乎您已經配置了應用程序加密密鑰。繼續此過程將覆蓋該密鑰並導致任何現有加密數據損壞。除非您知道自己在做什麼，否則不要繼續。',
        'understand' => '我了解執行此命令的後果，並對加密數據的丟失承擔全部責任。',
        'continue' => '您確定要繼續嗎？更改應用程序加密密鑰將導致數據丟失。',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => '沒有需要運行的計劃任務。',
            'error_message' => '處理計劃任務時遇到錯誤：',
        ],
    ],
    'upgrade' => [
        'integrity' => '此命令不驗證下載資源的完整性。請確保您信任下載源，然後再繼續。如果您不想下載存檔，請使用--skip-download標誌或在下面回答"no"。',
        'source_url' => '下載源（使用--url=設置）：',
        'php_version' => '無法執行自我升級過程。最低要求的PHP版本為7.4.0，您當前的版本為',
        'skipDownload' => '您要下載並解壓最新版本的存檔文件嗎？',
        'webserver_user' => '檢測到您的Web服務器用戶為<fg=blue>[{:user}]:</>，是否正確？',
        'name_webserver' => '請輸入運行Web服務器進程的用戶名。這因系統而異，但通常是"www-data"、"nginx"或"apache"。',
        'group_webserver' => '檢測到您的Web服務器組為<fg=blue>[{:group}]:</>，是否正確？',
        'group_webserver_question' => '請輸入運行Web服務器進程的組名。通常這與您的用戶名相同。',
        'are_your_sure' => '您確定要為面板運行升級過程嗎？',
        'terminated' => '升級過程已被用戶終止。',
        'success' => '面板已成功升級。請確保您還更新了任何守護程序實例。',
    ],
];