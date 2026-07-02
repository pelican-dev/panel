<?php

return [
    'nav_title' => '角色',
    'model_label' => '角色',
    'model_label_plural' => '角色',
    'no_roles' => '沒有角色',
    'name' => '角色名稱',
    'permissions' => '權限',
    'in_use' => '使用中',
    'all' => '全部',
    'root_admin' => ':role 擁有所有權限。',
    'root_admin_delete' => '無法刪除最高管理員',
    'users' => '使用者',
    'nodes' => '節點',
    'nodes_hint' => '留空以允許存取所有節點。',

    // Permission section headings (RolePermissionModels enum + special permission groups)
    'models' => [
        'apiKey' => 'API 金鑰',
        'allocation' => '分配',
        'databaseHost' => '資料庫主機',
        'database' => '資料庫',
        'egg' => 'Egg',
        'mount' => '掛載點',
        'node' => '節點',
        'role' => '角色',
        'server' => '伺服器',
        'user' => '使用者',
        'webhook' => 'Webhook',
        'settings' => '設定',
        'health' => '健康狀態',
        'activityLog' => '活動日誌',
        'panelLog' => '面板日誌',
        'plugin' => '外掛',
    ],

    // Permission checkbox labels (RolePermissionPrefixes enum + model-specific/special prefixes)
    'permissions_list' => [
        'viewList' => '檢視清單',
        'view' => '檢視',
        'create' => '建立',
        'update' => '更新',
        'delete' => '刪除',
        'import' => '匯入',
        'export' => '匯出',
        'seeIps' => '查看 IP',
    ],
];
