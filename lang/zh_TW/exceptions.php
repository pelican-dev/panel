<?php

return [
    'daemon_connection_failed' => '嘗試與守護程序通訊時發生例外狀況，產生了 HTTP/:code 回應代碼。此例外狀況已記錄。',
    'node' => [
        'servers_attached' => '必須沒有連結至此節點的伺服器，才能刪除該節點。',
        'error_connecting' => '連線至 :node 時發生錯誤',
        'daemon_off_config_updated' => '守護程序設定<strong>已更新</strong>，但在嘗試自動更新守護程序上的設定檔時發生錯誤。您必須手動更新守護程序的設定檔（config.yml）以套用這些變更。',
    ],
    'allocations' => [
        'server_using' => '目前有伺服器被指派至此分配。只有在目前未指派伺服器時，才能刪除分配。',
        'too_many_ports' => '不支援一次在單一範圍內新增超過 1000 個連接埠。',
        'invalid_mapping' => '為 :port 提供的對應無效，且無法處理。',
        'cidr_out_of_range' => 'CIDR 表示法僅允許介於 /25 與 /32 之間的遮罩。',
        'port_out_of_range' => '分配中的連接埠必須大於或等於 1024 且小於或等於 65535。',
    ],
    'egg' => [
        'delete_has_servers' => '無法從面板中刪除有連接使用中伺服器的 Egg。',
        'invalid_copy_id' => '選擇用來複製腳本的 Egg 不存在，或者該 Egg 本身也在複製腳本。',
        'has_children' => '此 Egg 是一個或多個其他 Egg 的父級。在刪除此 Egg 之前，請先刪除那些 Egg。',
    ],
    'variables' => [
        'env_not_unique' => '環境變數 :name 在此 Egg 中必須是唯一的。',
        'reserved_name' => '環境變數 :name 受保護，無法指派給變數。',
        'bad_validation_rule' => '驗證規則「:rule」不適用於此應用程式。',
    ],
    'importer' => [
        'json_error' => '嘗試解析 JSON 檔案時發生錯誤：:error。',
        'file_error' => '提供的 JSON 檔案無效。',
        'invalid_json_provided' => '提供的 JSON 檔案格式無法辨識。',
    ],
    'subusers' => [
        'editing_self' => '不允許編輯您自己的子使用者帳號。',
        'user_is_owner' => '您無法將伺服器擁有者新增為此伺服器的子使用者。',
        'subuser_exists' => '擁有該電子郵件位址的使用者已被指派為此伺服器的子使用者。',
    ],
    'databases' => [
        'delete_has_databases' => '無法刪除有連結使用中資料庫的資料庫主機伺服器。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '鏈結任務的最大間隔時間為 15 分鐘。',
    ],
    'locations' => [
        'has_nodes' => '無法刪除有連接使用中節點的位置。',
    ],
    'users' => [
        'is_self' => '無法刪除您自己的使用者帳號。',
        'has_servers' => '無法刪除帳號有連接使用中伺服器的使用者。在繼續之前，請先刪除他們的伺服器。',
        'node_revocation_failed' => '無法撤銷 <a href=":link">節點 #:node</a> 上的金鑰。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到符合自動部署指定要求的節點。',
        'no_viable_allocations' => '找不到符合自動部署要求的分配。',
    ],
    'api' => [
        'resource_not_found' => '請求的資源不存在於此伺服器上。',
    ],
    'mount' => [
        'servers_attached' => '必須沒有連接至此掛載點的伺服器，才能刪除該掛載點。',
    ],
    'server' => [
        'marked_as_failed' => '此伺服器尚未完成其安裝程序，請稍後再試。',
        'file_too_large' => '您嘗試開啟的檔案太大，無法在檔案編輯器中檢視。',
        'state_conflict' => '此伺服器目前處於不支援的狀態，請稍後再試。',
        'suspended' => '此伺服器目前已暫停，且請求的功能無法使用。',
        'maintenance' => '此伺服器的節點目前正在維護中，且請求的功能無法使用。',
        'restoring_backup' => '此伺服器目前正在從備份還原，請稍後再試。',
        'transferring' => '此伺服器目前正在轉移至新機器，請稍後再試。',
    ],
];
