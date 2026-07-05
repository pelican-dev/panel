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
        'name_helper' => '留空將自動產生隨機名稱',
        'username' => '使用者名稱',
        'password' => '密碼',
        'remote' => '連線來源',
        'remote_helper' => '應允許連線的來源。留空以允許來自任何地方的連線。',
        'max_connections' => '最大連線數',
        'created_at' => '建立於',
        'connection_string' => 'JDBC 連線字串',
    ],
    'error' => '連線至主機時發生錯誤',
    'host' => '主機',
    'host_help' => '當嘗試從此面板連線至此 MySQL 主機以建立新資料庫時，應使用的 IP 位址或網域名稱。',
    'port' => '連接埠',
    'port_help' => '此主機上 MySQL 正在執行的連接埠。',
    'max_database' => '最大資料庫數量',
    'max_databases_help' => '可在此主機上建立的最大資料庫數量。如果達到限制，將無法在此主機上建立新的資料庫。留空表示無限制。',
    'display_name' => '顯示名稱',
    'display_name_help' => '應顯示給終端使用者的 IP 位址或網域名稱。',
    'username' => '使用者名稱',
    'username_help' => '擁有足夠權限在系統上建立新使用者與資料庫的帳號使用者名稱。',
    'password' => '密碼',
    'password_help' => '資料庫使用者的密碼。',
    'linked_nodes' => '已連結的節點',
    'linked_nodes_help' => '當新增資料庫至所選節點上的伺服器時，此設定僅會預設使用此資料庫主機。',
    'connection_error' => '連線至資料庫主機時發生錯誤',
    'no_database_hosts' => '沒有資料庫主機',
    'no_databases' => '沒有資料庫',
    'no_nodes' => '沒有節點',
    'nodes' => '節點',
    'delete_help' => '資料庫主機擁有資料庫',
    'unlimited' => '無限制',
    'anywhere' => '任何地方',

    'rotate' => '輪替',
    'rotate_password' => '輪替密碼',
    'rotated' => '密碼已輪替',
    'rotate_error' => '密碼輪替失敗',
    'databases' => '資料庫',

    'setup' => [
        'preparations' => '準備工作',
        'database_setup' => '資料庫設定',
        'panel_setup' => '面板設定',

        'note' => '目前，資料庫主機僅支援 MySQL 或 MariaDB 資料庫！',
        'different_server' => '面板與資料庫<i>是否不在</i>同一台伺服器上？',

        'database_user' => '資料庫使用者',
        'cli_login' => '使用 <code>mysql -u root -p</code> 存取 MySQL CLI。',
        'command_create_user' => '建立使用者的指令',
        'command_assign_permissions' => '指派權限的指令',
        'cli_exit' => '若要退出 MySQL CLI，請執行 <code>exit</code>。',
        'external_access' => '外部存取',
        'allow_external_access' => '
                                    <p>您很有可能需要允許對此 MySQL 實例進行外部存取，以便允許伺服器連線至該實例。</p>
                                    <br>
                                    <p>若要執行此操作，請開啟 <code>my.cnf</code>，其位置會因您的作業系統以及 MySQL 的安裝方式而異。您可以輸入 find <code>/etc -iname my.cnf</code> 來尋找它。</p>
                                    <br>
                                    <p>開啟 <code>my.cnf</code>，將下方文字新增至檔案底部並儲存：<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>重新啟動 MySQL 或 MariaDB 以套用這些變更。這將會覆寫預設的 MySQL 設定，該設定預設僅接受來自 localhost 的請求。更新此設定將允許所有介面上的連線，進而允許外部連線。請確保在您的防火牆中允許 MySQL 連接埠（預設為 3306）。</p>
                                ',
    ],
];
