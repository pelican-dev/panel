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
        'name_helper' => '留空將自動產生一個隨機名稱',
        'username' => '使用者名稱',
        'password' => '密碼',
        'remote' => '允許的連線來源',
        'remote_helper' => '允許連線的來源。留空以允許來自任何地方的連線。',
        'max_connections' => '最大連線數',
        'created_at' => '建立於',
        'connection_string' => 'JDBC 連線字串',
    ],
    'error' => '連線到主機時發生錯誤',
    'host' => '主機',
    'host_help' => '從此 Panel 嘗試連線到此 MySQL 主機以建立新資料庫時應使用的 IP 地址或網域名稱。',
    'port' => '連接埠',
    'port_help' => '此主機上 MySQL 執行的連接埠。',
    'max_database' => '最大資料庫數量',
    'max_databases_help' => '可以在此主機上建立的資料庫的最大數量。如果達到限制，則無法在此主機上建立新資料庫。留空則無限制。',
    'display_name' => '顯示名稱',
    'display_name_help' => '應向最終使用者顯示的 IP 地址或網域名稱。',
    'username' => '使用者名稱',
    'username_help' => '具有足夠權限在系統上建立新使用者和資料庫的帳戶的使用者名稱。',
    'password' => '密碼',
    'password_help' => '該資料庫使用者的密碼。',
    'linked_nodes' => '關聯節點',
    'linked_nodes_help' => '只有在所選節點上向伺服器新增資料庫時，才會預設選擇此資料庫主機。',
    'connection_error' => '連線到資料庫主機時發生錯誤',
    'no_database_hosts' => '沒有資料庫主機',
    'no_nodes' => '沒有節點',
    'delete_help' => '資料庫主機擁有資料庫',
    'unlimited' => '無限制',
    'anywhere' => '任何地方',

    'rotate' => '輪換',
    'rotate_password' => '輪換密碼',
    'rotated' => '密碼已輪換',
    'rotate_error' => '密碼輪換失敗',
    'databases' => '資料庫',

    'setup' => [
        'preparations' => '準備工作',
        'database_setup' => '資料庫設定',
        'panel_setup' => 'Panel 設定',

        'note' => '目前，資料庫主機僅支援 MySQL/MariaDB 資料庫！',
        'different_server' => 'Panel 和資料庫是否<i>不在</i>同一台伺服器上？',

        'database_user' => '資料庫使用者',
        'cli_login' => '使用 <code>mysql -u root -p</code> 存取 mysql cli。',
        'command_create_user' => '建立使用者的命令',
        'command_assign_permissions' => '分配權限的命令',
        'cli_exit' => '要退出 mysql cli，請執行 <code>exit</code>。',
        'external_access' => '外部存取',
        'allow_external_access' => '
                                    <p>您可能需要允許對該 MySQL 實例進行外部存取，以允許伺服器連線到它。</p>
                                    <br>
                                    <p>為此，請打開 <code>my.cnf</code>，其位置因您的作業系統和 MySQL 安裝方式而異。您可以輸入 <code>find /etc -iname my.cnf</code> 來找到它。</p>
                                    <br>
                                    <p>打開 <code>my.cnf</code>，將下面的文字新增到檔案底部並儲存：<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>重新啟動 MySQL/MariaDB 以套用這些變更。這將覆蓋預設的 MySQL 設定（預設只接受來自 localhost 的請求）。更新此設定將允許所有介面上的連線，即外部連線。確保在您的防火牆中允許 MySQL 連接埠（預設為 3306）。</p>
                                ',
    ],
];
