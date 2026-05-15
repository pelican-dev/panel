<?php

return [
    'title' => '使用者',
    'username' => '使用者名稱',
    'email' => '電子郵件',
    'assign_all' => '分配全部',
    'invite_user' => '邀請使用者',
    'action' => '邀請',
    'remove' => '移除使用者',
    'edit' => '編輯使用者',
    'editing' => '正在編輯 :user',
    'delete' => '刪除使用者',
    'notification_add' => '使用者已邀請！',
    'notification_edit' => '使用者已更新！',
    'notification_delete' => '使用者已刪除！',
    'notification_failed' => '邀請使用者失敗！',
    'permissions' => [
        'title' => '權限',

        'activity_title' => '活動',
        'activity_desc' => '控制使用者存取伺服器活動日誌的權限。',

        'startup_title' => '啟動',
        'startup_desc' => '控制使用者查看此伺服器啟動參數的能力的權限。',

        'settings_title' => '設定',
        'settings_desc' => '控制使用者修改此伺服器設定的能力的權限。',

        'control_title' => '控制',
        'control_desc' => '控制使用者控制伺服器電源狀態或發送命令的能力的權限。',

        'user_title' => '使用者',
        'user_desc' => '允許使用者管理伺服器上其他子使用者的權限。他們永遠無法編輯自己的帳戶，也無法分配他們自己沒有的權限。',

        'file_title' => '檔案',
        'file_desc' => '控制使用者修改此伺服器檔案系統的能力的權限。',

        'allocation_title' => '分配',
        'allocation_desc' => '控制使用者修改此伺服器的連接埠分配的能力的權限。',

        'database_title' => '資料庫',
        'database_desc' => '控制使用者存取此伺服器的資料庫管理的權限。',

        'backup_title' => '備份',
        'backup_desc' => '控制使用者產生和管理伺服器備份的能力的權限。',

        'schedule_title' => '排程',
        'schedule_desc' => '控制使用者存取此伺服器的排程管理的權限。',

        'startup_read' => '允許使用者查看伺服器的啟動變數。',
        'startup_update' => '允許使用者修改伺服器的啟動變數。',
        'startup_docker_image' => '允許使用者修改執行伺服器時使用的 Docker 映像檔。',

        'settings_rename' => '允許使用者重新命名此伺服器。',
        'settings_description' => '允許使用者變更此伺服器的描述。',
        'settings_reinstall' => '允許使用者觸發此伺服器的重新安裝。',
        'settings_change_icon' => '允許使用者變更此伺服器的圖示。',

        'activity_read' => '允許使用者查看伺服器的活動日誌。',

        'websocket_connect' => '允許使用者存取此伺服器的 websocket。',

        'control_console' => '允許使用者將資料發送到伺服器控制台。',
        'control_start' => '允許使用者啟動伺服器執行個體。',
        'control_stop' => '允許使用者停止伺服器執行個體。',
        'control_restart' => '允許使用者重新啟動伺服器執行個體。',
        'control_kill' => '允許使用者強制停止伺服器執行個體。',

        'user_create' => '允許使用者為伺服器建立新使用者帳戶。',
        'user_read' => '允許使用者查看與此伺服器關聯的使用者。',
        'user_update' => '允許使用者修改與此伺服器關聯的其他使用者。',
        'user_delete' => '允許使用者刪除與此伺服器關聯的其他使用者。',

        'file_create' => '允許使用者建立新檔案和目錄。',
        'file_read' => '允許使用者查看目錄的內容，但不能查看檔案內容或下載檔案。',
        'file_read_content' => '允許使用者查看給定檔案的內容。這也將允許使用者下載檔案。',
        'file_update' => '允許使用者更新與伺服器關聯的檔案和資料夾。',
        'file_delete' => '允許使用者刪除檔案和目錄。',
        'file_archive' => '允許使用者建立檔案封存和解壓縮現有封存。',
        'file_sftp' => '允許使用者使用 SFTP 用戶端執行上述檔案操作。',

        'allocation_read' => '允許使用者查看目前分配給此伺服器的所有分配。對該伺服器具有任何級別存取權限的使用者始終可以查看主要分配。',
        'allocation_update' => '允許使用者變更主要伺服器分配並將備註附加到每個分配。',
        'allocation_delete' => '允許使用者從伺服器中刪除分配。',
        'allocation_create' => '允許使用者為伺服器分配其他分配。',

        'database_create' => '允許使用者為伺服器建立新資料庫。',
        'database_read' => '允許使用者查看伺服器資料庫。',
        'database_update' => '允許使用者對資料庫進行修改。如果使用者沒有「查看密碼」權限，他們將無法修改密碼。',
        'database_delete' => '允許使用者刪除資料庫執行個體。',
        'database_view_password' => '允許使用者在系統中查看資料庫密碼。',

        'schedule_create' => '允許使用者為伺服器建立新排程。',
        'schedule_read' => '允許使用者查看伺服器的排程。',
        'schedule_update' => '允許使用者對現有伺服器排程進行修改。',
        'schedule_delete' => '允許使用者刪除伺服器的排程。',

        'backup_create' => '允許使用者為此伺服器建立新備份。',
        'backup_read' => '允許使用者查看此伺服器存在的所有備份。',
        'backup_delete' => '允許使用者從系統中刪除備份。',
        'backup_download' => '允許使用者下載伺服器的備份。危險：這允許使用者存取備份中伺服器的所有檔案。',
        'backup_restore' => '允許使用者恢復伺服器的備份。危險：這允許使用者在此過程中刪除所有伺服器檔案。',
        'mount_desc' => '控制使用者管理此伺服器掛載的能力的權限。',
        'mount_read' => '允許使用者查看掛載頁面並查看可用的掛載。',
        'mount_update' => '允許使用者為伺服器開啟或關閉掛載。',
    ],
];
