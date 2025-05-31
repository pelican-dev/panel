<?php

return [
    'daemon_connection_failed' => '嘗試與守護程序通信時發生異常，返回HTTP/:code響應碼。此異常已記錄。',
    'node' => [
        'servers_attached' => '節點必須沒有連結的伺服器才能被刪除。',
        'error_connecting' => '連接節點:node時出錯',
        'daemon_off_config_updated' => '守護程序配置<strong>已更新</strong>，但在嘗試自動更新守護程序上的配置文件時遇到錯誤。您需要手動更新配置文件(config.yml)以應用這些更改。',
    ],
    'allocations' => [
        'server_using' => '當前有伺服器分配到此端口。只有在沒有伺服器分配時才能刪除端口。',
        'too_many_ports' => '不支持一次添加超過1000個端口範圍。',
        'invalid_mapping' => '為:port提供的映射無效，無法處理。',
        'cidr_out_of_range' => 'CIDR表示法只允許掩碼在/25到/32之間。',
        'port_out_of_range' => '分配中的端口必須大於或等於1024且小於或等於65535。',
    ],
    'egg' => [
        'delete_has_servers' => '無法從面板中刪除連結了活動伺服器的Egg。',
        'invalid_copy_id' => '選擇用於複製腳本的Egg不存在，或者它本身正在複製腳本。',
        'has_children' => '此Egg是一個或多個其他Egg的父級。請先刪除這些Egg，然後再刪除此Egg。',
    ],
    'variables' => [
        'env_not_unique' => '環境變量:name必須對此Egg唯一。',
        'reserved_name' => '環境變量:name受保護，不能分配給變量。',
        'bad_validation_rule' => '驗證規則":rule"對此應用程序無效。',
    ],
    'importer' => [
        'json_error' => '嘗試解析JSON文件時出錯: :error。',
        'file_error' => '提供的JSON文件無效。',
        'invalid_json_provided' => '提供的JSON文件格式無法識別。',
    ],
    'subusers' => [
        'editing_self' => '不允許編輯您自己的子用戶帳戶。',
        'user_is_owner' => '您不能將伺服器所有者添加為此伺服器的子用戶。',
        'subuser_exists' => '具有該電子郵件地址的用戶已分配為此伺服器的子用戶。',
    ],
    'databases' => [
        'delete_has_databases' => '無法刪除連結了活動數據庫的數據庫主機伺服器。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '鏈式任務的最大間隔時間為15分鐘。',
    ],
    'locations' => [
        'has_nodes' => '無法刪除連結了活動節點的位置。',
    ],
    'users' => [
        'is_self' => '無法刪除您自己的用戶帳戶。',
        'has_servers' => '無法刪除連結了活動伺服器的用戶。請先刪除其伺服器，然後再繼續。',
        'node_revocation_failed' => '在<a href=":link">節點#:node</a>上撤銷密鑰失敗。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到滿足自動部署要求的節點。',
        'no_viable_allocations' => '找不到滿足自動部署要求的端口分配。',
    ],
    'api' => [
        'resource_not_found' => '請求的資源在此伺服器上不存在。',
    ],
    'mount' => [
        'servers_attached' => '掛載點必須沒有連結的伺服器才能被刪除。',
    ],
    'server' => [
        'marked_as_failed' => '此伺服器尚未完成安裝過程，請稍後再試。',
    ],
];