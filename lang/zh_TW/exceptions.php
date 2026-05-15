<?php

return [
    'daemon_connection_failed' => '嘗試與守護程序 (daemon) 通訊時發生異常，導致 HTTP/:code 回應代碼。此異常已記錄。',
    'node' => [
        'servers_attached' => '節點必須沒有連結的伺服器才能被刪除。',
        'error_connecting' => '連線到 :node 時發生錯誤',
        'daemon_off_config_updated' => '守護程序設定<strong>已更新</strong>，但在嘗試自動更新守護程序上的設定檔時遇到錯誤。您需要手動更新守護程序的設定檔 (config.yml) 才能套用這些變更。',
    ],
    'allocations' => [
        'server_using' => '目前有伺服器分配到了該連接埠 (allocation)。僅當沒有伺服器分配時，才能刪除連接埠分配。',
        'too_many_ports' => '不支援一次在單個範圍內新增超過 1000 個連接埠。',
        'invalid_mapping' => '為 :port 提供的映射無效且無法處理。',
        'cidr_out_of_range' => 'CIDR 表示法僅允許 /25 和 /32 之間的遮罩。',
        'port_out_of_range' => '連接埠分配必須大於或等於 1024 且小於或等於 65535。',
    ],
    'egg' => [
        'delete_has_servers' => '無法從 Panel 刪除附加了活動伺服器的 Egg。',
        'invalid_copy_id' => '選擇用於複製腳本的 Egg 不存在，或者其本身正在複製腳本。',
        'has_children' => '此 Egg 是一個或多個其他 Egg 的父級。請在刪除此 Egg 之前刪除那些 Egg。',
    ],
    'variables' => [
        'env_not_unique' => '環境變數 :name 必須對此 Egg 唯一。',
        'reserved_name' => '環境變數 :name 受保護，無法分配給變數。',
        'bad_validation_rule' => '驗證規則 ":rule" 對此應用程式不是有效的規則。',
    ],
    'importer' => [
        'json_error' => '嘗試解析 JSON 檔案時發生錯誤：:error。',
        'file_error' => '提供的 JSON 檔案無效。',
        'invalid_json_provided' => '提供的 JSON 檔案格式無法識別。',
    ],
    'subusers' => [
        'editing_self' => '不允許編輯您自己的子使用者帳戶。',
        'user_is_owner' => '您不能將伺服器擁有者新增為此伺服器的子使用者。',
        'subuser_exists' => '具有該電子郵件地址的使用者已被分配為此伺服器的子使用者。',
    ],
    'databases' => [
        'delete_has_databases' => '無法刪除連結了活動資料庫的資料庫主機伺服器。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '鏈式任務的最大間隔時間為 15 分鐘。',
    ],
    'locations' => [
        'has_nodes' => '無法刪除附加了活動節點的地理位置。',
    ],
    'users' => [
        'is_self' => '無法刪除您自己的使用者帳戶。',
        'has_servers' => '無法刪除其帳戶下附加了活動伺服器的使用者。請在繼續之前刪除他們的伺服器。',
        'node_revocation_failed' => '無法在 <a href=":link">節點 #:node</a> 上撤銷金鑰。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到滿足自動部署指定要求的節點。',
        'no_viable_allocations' => '找不到滿足自動部署要求的連接埠分配 (allocations)。',
    ],
    'api' => [
        'resource_not_found' => '請求的資源不在此伺服器上存在。',
    ],
    'mount' => [
        'servers_attached' => '掛載 (mount) 必須沒有附加的伺服器才能被刪除。',
    ],
    'server' => [
        'marked_as_failed' => '此伺服器尚未完成其安裝過程，請稍後再試。',
    ],
];
