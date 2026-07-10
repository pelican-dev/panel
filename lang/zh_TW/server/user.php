<?php

return [
    'title' => '使用者',
    'username' => '使用者名稱',
    'email' => '電子郵件',
    'assign_all' => '指派全部',
    'invite_user' => '邀請使用者',
    'action' => '邀請',
    'remove' => '移除使用者',
    'edit' => '編輯使用者',
    'editing' => '正在編輯 :user',
    'delete' => '刪除使用者',
    'notification_add' => '使用者已受邀！',
    'notification_edit' => '使用者已更新！',
    'notification_delete' => '使用者已刪除！',
    'notification_failed' => '無法邀請使用者！',
    'permissions' => [
        'title' => '權限',

        'activity_title' => '活動',
        'activity_desc' => '控制使用者存取伺服器活動日誌的權限。',

        'startup_title' => '啟動',
        'startup_desc' => '控制使用者檢視此伺服器啟動參數能力的權限。',

        'settings_title' => '設定',
        'settings_desc' => '控制使用者修改此伺服器設定能力的權限。',

        'control_title' => '控制',
        'control_desc' => '控制使用者控制伺服器電源狀態或傳送指令能力的權限。',

        'user_title' => '使用者',
        'user_desc' => '允許使用者管理伺服器上其他子使用者的權限。他們將永遠無法編輯自己的帳號，或指派他們自己沒有的權限。',

        'file_title' => '檔案',
        'file_desc' => '控制使用者修改此伺服器檔案系統能力的權限。',

        'allocation_title' => '分配',
        'allocation_desc' => '控制使用者修改此伺服器連接埠分配能力的權限。',

        'database_title' => '資料庫',
        'database_desc' => '控制使用者存取此伺服器資料庫管理能力的權限。',

        'backup_title' => '備份',
        'backup_desc' => '控制使用者產生與管理伺服器備份能力的權限。',

        'schedule_title' => '排程',
        'schedule_desc' => '控制使用者存取此伺服器排程管理能力的權限。',

        'startup_read' => '允許使用者檢視伺服器的啟動變數。',
        'startup_update' => '允許使用者修改伺服器的啟動變數。',
        'startup_docker_image' => '允許使用者修改執行伺服器時所使用的 Docker 映像檔。',

        'settings_rename' => '允許使用者重新命名此伺服器。',
        'settings_description' => '允許使用者變更此伺服器的說明。',
        'settings_reinstall' => '允許使用者觸發此伺服器的重新安裝。',
        'settings_change_icon' => '允許使用者變更此伺服器的圖示。',

        'activity_read' => '允許使用者檢視伺服器的活動日誌。',

        'websocket_connect' => '允許使用者存取此伺服器的 WebSocket。',

        'control_console' => '允許使用者傳送資料至伺服器主控台。',
        'control_start' => '允許使用者啟動伺服器執行個體。',
        'control_stop' => '允許使用者停止伺服器執行個體。',
        'control_restart' => '允許使用者重新啟動伺服器執行個體。',
        'control_kill' => '允許使用者強制終止伺服器執行個體。',

        'user_create' => '允許使用者為伺服器建立新的使用者帳號。',
        'user_read' => '允許使用者擁有檢視與此伺服器相關聯之使用者的權限。',
        'user_update' => '允許使用者修改與此伺服器相關聯的其他使用者。',
        'user_delete' => '允許使用者刪除與此伺服器相關聯的其他使用者。',

        'file_create' => '允許使用者擁有建立新檔案與目錄的權限。',
        'file_read' => '允許使用者檢視目錄內容，但不能檢視檔案內容或下載檔案。',
        'file_read_content' => '允許使用者檢視指定檔案的內容。這也將允許使用者下載檔案。',
        'file_update' => '允許使用者更新與伺服器相關聯的檔案與資料夾。',
        'file_delete' => '允許使用者刪除檔案與目錄。',
        'file_archive' => '允許使用者建立壓縮檔並解壓縮現有的壓縮檔。',
        'file_sftp' => '允許使用者使用 SFTP 用戶端執行上述檔案動作。',

        'allocation_read' => '允許使用者檢視目前指派給此伺服器的所有分配。對此伺服器擁有任何存取層級的使用者皆能檢視主要分配。',
        'allocation_update' => '允許使用者變更主要伺服器分配並在每個分配附加備註。',
        'allocation_delete' => '允許使用者從伺服器刪除分配。',
        'allocation_create' => '允許使用者指派額外的分配給伺服器。',

        'database_create' => '允許使用者擁有為伺服器建立新資料庫的權限。',
        'database_read' => '允許使用者擁有檢視伺服器資料庫的權限。',
        'database_update' => '允許使用者擁有對資料庫進行修改的權限。如果使用者同時沒有「檢視密碼」權限，他們將無法修改密碼。',
        'database_delete' => '允許使用者擁有刪除資料庫執行個體的權限。',
        'database_view_password' => '允許使用者擁有在系統中檢視資料庫密碼的權限。',

        'schedule_create' => '允許使用者為伺服器建立新的排程。',
        'schedule_read' => '允許使用者擁有檢視伺服器排程的權限。',
        'schedule_update' => '允許使用者擁有對現有伺服器排程進行修改的權限。',
        'schedule_delete' => '允許使用者刪除伺服器的排程。',

        'backup_create' => '允許使用者為此伺服器建立新備份。',
        'backup_read' => '允許使用者檢視此伺服器現有的所有備份。',
        'backup_delete' => '允許使用者從系統移除備份。',
        'backup_download' => '允許使用者下載伺服器的備份。危險：這允許使用者在備份中存取該伺服器的所有檔案。',
        'backup_restore' => '允許使用者還原伺服器的備份。危險：這允許使用者在過程中刪除伺服器的所有檔案。',

        'mount_title' => '掛載點',
        'mount_desc' => '控制使用者管理此伺服器掛載點能力的權限。',
        'mount_read' => '允許使用者檢視掛載點頁面並查看可用的掛載點。',
        'mount_update' => '允許使用者為伺服器切換開啟或關閉掛載點。',
    ],
];
