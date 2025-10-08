<?php

return [
    'daemon_connection_failed' => '嘗試與守護程式通訊時發生意外情況，導致 HTTP/:code 回應碼。此意外已被記錄。',
    'node' => [
        'servers_attached' => '節點必須沒有連結任何伺服器才能刪除。',
        'error_connecting' => '連接到節點 :node 時發生錯誤',
        'daemon_off_config_updated' => '守護程式設定<strong>已更新</strong>，但嘗試自動更新守護程式上的設定檔時發生錯誤。你需要手動更新守護程式的設定檔（config.yml）以套用這些變更。',
    ],
    'allocations' => [
        'server_using' => '此配置目前已指派給伺服器。只有在沒有伺服器指派時，才能刪除配置。',
        'too_many_ports' => '不支援一次性在單一範圍內新增超過 1000 個連接埠。',
        'invalid_mapping' => '提供的 :port 映射無效，無法處理。',
        'cidr_out_of_range' => 'CIDR 表示法僅允許遮罩在 /25 到 /32 之間。',
        'port_out_of_range' => '配置中的連接埠必須大於或等於 1024，且小於或等於 65535。',
    ],
    'egg' => [
        'delete_has_servers' => '已使用在活躍伺服器的 Egg 無法從面板刪除。',
        'invalid_copy_id' => '選擇用來複製腳本的 Egg 不存在，或該 Egg 本身正在複製腳本。',
        'has_children' => '此 Egg 是一個或多個其他 Egg 的父項。請先刪除那些 Egg，才能刪除此 Egg。',
    ],
    'variables' => [
        'env_not_unique' => '環境變數 :name 在此 Egg 中必須是唯一的。',
        'reserved_name' => '環境變數 :name 受到保護，無法被指派為變數。',
        'bad_validation_rule' => '驗證規則「:rule」並非本應用程式有效的規則。',
    ],
    'importer' => [
        'json_error' => '嘗試解析 JSON 檔案時發生錯誤：:error。',
        'file_error' => '提供的 JSON 檔案無效。',
        'invalid_json_provided' => '提供的 JSON 檔案格式無法被識別。',
    ],
    'subusers' => [
        'editing_self' => '不允許編輯自己的子使用者帳號。',
        'user_is_owner' => '無法將伺服器擁有者新增為該伺服器的子使用者。',
        'subuser_exists' => '已有使用該電子郵件地址的使用者被指派為此伺服器的子使用者。',
    ],
    'databases' => [
        'delete_has_databases' => '無法刪除仍有連結活躍資料庫的資料庫主機。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '鏈式任務的最大間隔時間為 15 分鐘。',
    ],
    'locations' => [
        'has_nodes' => '無法刪除仍有活躍節點連結的地點。',
    ],
    'users' => [
        'is_self' => '無法刪除自己的使用者帳號。',
        'has_servers' => '無法刪除擁有活躍伺服器的使用者帳號。請先刪除其伺服器後再繼續。',
        'node_revocation_failed' => '撤銷 <a href=":link">節點 #:node</a> 上的金鑰失敗。錯誤訊息：:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到符合自動部署要求的節點。',
        'no_viable_allocations' => '找不到符合自動部署需求的配置。',
    ],
    'api' => [
        'resource_not_found' => '請求的資源在此伺服器上不存在。',
    ],
    'mount' => [
        'servers_attached' => '掛載點必須沒有連結任何伺服器才能刪除。',
    ],
    'server' => [
        'marked_as_failed' => '此伺服器尚未完成安裝程序，請稍後再試。',
    ],
];
