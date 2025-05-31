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
        'name_helper' => '留空將自動生成隨機名稱',
        'username' => '使用者名稱',
        'password' => '密碼',
        'remote' => '允許連線來源',
        'remote_helper' => '設定允許連線的來源。留空則允許從任何地方連線。',
        'max_connections' => '最大連線數',
        'created_at' => '建立時間',
        'connection_string' => 'JDBC 連接字串',
    ],
    'error' => '連線至主機時發生錯誤',
    'host' => '主機',
    'host_help' => '此面板用於建立新資料庫時，嘗試連線到此 MySQL 主機所使用的 IP 位址或網域名稱。',
    'port' => '連接埠',
    'port_help' => '此主機上 MySQL 運行的連接埠。',
    'max_database' => '最大資料庫數量',
    'max_databases_help' => '此主機上可建立的資料庫數量上限。若達到上限，將無法在此主機上建立新資料庫。留空表示無限制。',
    'display_name' => '顯示名稱',
    'display_name_help' => '顯示給終端使用者看的 IP 位址或網域名稱。',
    'username' => '使用者名稱',
    'username_help' => '具有足夠權限在系統上建立新使用者和資料庫的帳號名稱。',
    'password' => '密碼',
    'password_help' => '資料庫使用者的密碼。',
    'linked_nodes' => '連結節點',
    'linked_nodes_help' => '此設定僅在選定節點上為伺服器添加資料庫時，預設使用此資料庫主機。',
    'connection_error' => '連線至資料庫主機時發生錯誤',
    'no_database_hosts' => '無資料庫主機',
    'no_nodes' => '無節點',
    'delete_help' => '資料庫主機包含資料庫',
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
        'panel_setup' => '面板設定',

        'note' => '目前資料庫主機僅支援 MySQL/MariaDB 資料庫！',
        'different_server' => '面板和資料庫<i>不在</i>同一台伺服器上？',

        'database_user' => '資料庫使用者',
        'cli_login' => '使用 <code>mysql -u root -p</code> 登入 mysql 命令行介面。',
        'command_create_user' => '建立使用者的指令',
        'command_assign_permissions' => '分配權限的指令',
        'cli_exit' => '輸入 <code>exit</code> 可退出 mysql 命令行介面。',
        'external_access' => '外部存取',
        'allow_external_access' => '
                                    <p>您可能需要允許此 MySQL 實例的外部存取，以便伺服器能夠連線至它。</p>
                                    <br>
                                    <p>為此，請開啟 <code>my.cnf</code> 文件，其位置因作業系統和 MySQL 安裝方式而異。您可以輸入 <code>find /etc -iname my.cnf</code> 來定位它。</p>
                                    <br>
                                    <p>開啟 <code>my.cnf</code>，在文件末尾添加以下內容並保存：<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>重新啟動 MySQL/MariaDB 以套用變更。這將覆蓋預設的 MySQL 配置，預設情況下 MySQL 僅接受來自本機的請求。更新此配置將允許所有介面的連線，從而允許外部連線。請確保在防火牆中允許 MySQL 連接埠（預設為 3306）。</p>
                                ',
    ],
];