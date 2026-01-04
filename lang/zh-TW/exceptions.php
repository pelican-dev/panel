<?php

return [
    'daemon_connection_failed' => '嘗試與守護程序通訊時發生異常，導致回應代碼為 HTTP/:code。此異常已記錄。',
    'node' => [
        'servers_attached' => '節點必須沒有連結任何伺服器才能被刪除。',
        'error_connecting' => '連線到 :node 時發生錯誤',
        'daemon_off_config_updated' => '守護程序設定<strong>已更新</strong>，但在嘗試自動更新守護程序上的設定檔時發生錯誤。您需要手動更新守護程序的設定檔 (config.yml) 以套用這些變更。',
    ],
    'allocations' => [
        'server_using' => '目前有伺服器分配給此配置。只有在沒有分配伺服器的情況下才能刪除配置。',
        'too_many_ports' => '不支援一次在單個範圍內新增超過 1000 個連接埠。',
        'invalid_mapping' => '為 :port 提供的映射無效，無法處理。',
        'cidr_out_of_range' => 'CIDR 表示法僅允許 /25 到 /32 之間的遮罩。',
        'port_out_of_range' => '配置中的連接埠必須大於或等於 1024 且小於或等於 65535。',
    ],
    'egg' => [
        'delete_has_servers' => '無法從面板中刪除連結了活動伺服器的 Egg。',
        'invalid_copy_id' => '選擇用於複製腳本的 Egg 不存在，或者正在複製腳本本身。',
        'has_children' => '此 Egg 是一個或多個其他 Egg 的父級。請在刪除此 Egg 之前刪除那些 Egg。',
    ],
    'variables' => [
        'env_not_unique' => '環境變數 :name 對於此 Egg 必須是唯一的。',
        'reserved_name' => '環境變數 :name 受保護，無法分配給變數。',
        'bad_validation_rule' => '驗證規則 ":rule" 對於此應用程式不是有效的規則。',
    ],
    'importer' => [
        'json_error' => '嘗試解析 JSON 檔案時發生錯誤：:error。',
        'file_error' => '提供的 JSON 檔案無效。',
        'invalid_json_provided' => '提供的 JSON 檔案格式無法識別。',
    ],
    'subusers' => [
        'editing_self' => '不允許編輯您自己的子使用者帳戶。',
        'user_is_owner' => '您不能將伺服器擁有者新增為此伺服器的子使用者。',
        'subuser_exists' => '擁有該電子郵件地址的使用者已被指派為此伺服器的子使用者。',
    ],
    'databases' => [
        'delete_has_databases' => '無法刪除連結了活動資料庫的資料庫主機伺服器。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '連鎖任務的最大間隔時間為 15 分鐘。',
    ],
    'locations' => [
        'has_nodes' => '無法刪除連結了活動節點的位置。',
    ],
    'users' => [
        'is_self' => '無法刪除您自己的使用者帳戶。',
        'has_servers' => '無法刪除帳戶連結了活動伺服器的使用者。請在繼續之前刪除他們的伺服器。',
        'node_revocation_failed' => '無法撤銷 <a href=":link">節點 #:node</a> 上的金鑰。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到滿足自動部署指定要求的節點。',
        'no_viable_allocations' => '找不到滿足自動部署要求的配置。',
    ],
    'api' => [
        'resource_not_found' => '請求的資源在此伺服器上不存在。',
    ],
    'mount' => [
        'servers_attached' => '掛載點必須沒有連結任何伺服器才能被刪除。',
    ],
    'server' => [
        'marked_as_failed' => '此伺服器尚未完成安裝程序，請稍後再試。',
    ],
];
