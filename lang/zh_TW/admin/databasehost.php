<?php

return [
    'nav_title' => '資料庫主機',
    'model_label' => '資料庫主機',
    'model_label_plural' => '資料庫主機',
    'table' => [
        'database' => '資料庫',
        'name' => '名稱',
        'host' => '主機',
        'port' => '連接埠',
        'name_helper' => '將此欄位保留空白將自動生成名稱',
        'username' => '使用者名稱',
        'password' => '密碼',
        'remote' => '連線來自',
        'remote_helper' => '允許連接的地方。留空以允許任何地方的連接。',
        'max_connections' => '最大連線數',
        'created_at' => '建立於',
        'connection_string' => 'JDBC 連接字串',
    ],
    'error' => '連接到主機時發生錯誤',
    'host' => '主機',
    'host_help' => '嘗試從本面板連接到此 MySQL 主機以建立新資料庫時應使用的 IP 位址或網域名稱。',
    'port' => '連接埠',
    'port_help' => 'MySQL 在此主機的運行的連接埠。',
    'max_database' => '最大資料庫',
    'max_databases_help' => '可以在此主機上建立的資料庫的最大數量。 如果達到限制，無法在此主機上建立新的資料庫。空白是無限的。',
    'display_name' => '顯示名稱',
    'display_name_help' => '應該向終端用戶顯示的 IP 位址或網域名稱。',
    'username' => '使用者名稱',
    'username_help' => '具有足夠權限在系統上建立新使用者和資料庫的帳戶的使用者名稱。',
    'password' => '密碼',
    'password_help' => '資料庫使用者的密碼。',
    'linked_nodes' => '已連接的節點',
    'linked_nodes_help' => '此設定只在添加資料庫到所選節點上的伺服器時預設為此資料庫主機。',
    'connection_error' => '連接到資料庫主機時發生錯誤',
    'no_database_hosts' => '沒有資料庫主機',
    'no_nodes' => '沒有節點',
    'delete_help' => '資料庫主機有資料庫',
    'unlimited' => '無限制',
    'anywhere' => '任何地方',

    'rotate' => '旋轉',
    'rotate_password' => '更改你的密碼',
    'rotated' => '密碼已輪替',
    'rotate_error' => '密碼輪替失敗',
    'databases' => '資料庫',

    'setup' => [
        'preparations' => '準備工作',
        'database_setup' => '資料庫設定',
        'panel_setup' => '面板設定',

        'note' => '目前，資料庫主機僅支援 MySQL / MariaDB 資料庫！',
        'different_server' => '面板和資料庫<i>不</i>在同一個伺服器上嗎？',

        'database_user' => '資料庫使用者',
        'cli_login' => '使用 <code>mysql -u root -p</code> 存取 mysql cli。',
        'command_create_user' => '建立使用者的指令',
        'command_assign_permissions' => '指派權限的指令',
        'cli_exit' => '要離開 mysql cli，執行 <code>exit</code>。',
        'external_access' => '外部存取',
        'allow_external_access' => '
                                    <p>您可能需要允許外部存取此 MySQL 執行個體，以便允許伺服器連接到它。</p>
                                    <br>
                                    <p>為此，請開啟<code>my.cnf</code>，其位置根據您的作業系統和 MySQL 的安裝方式而異。您可以輸入 find <code>/etc -iname my.cnf</code> 來尋找它。</p>
                                    <br>
                                    <p>開啟 <code>my.cnf</code>，將以下文字新增到檔案底部並儲存：<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>重新啟動 MySQL / MariaDB 以套用這些變更。這會覆蓋預設 MySQL 設定，預設配置僅接受來自本機的請求。更新此配置後，將允許所有介面的連接，從而允許外部連接。請確保在防火牆中允許 MySQL 連接埠（預設為 3306）。</p>
                                ',
    ],
];
