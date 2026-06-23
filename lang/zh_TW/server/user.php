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
        'activity_desc' => '允許使用者查看伺服器活動日誌。',

        'startup_title' => '啟動',
        'startup_desc' => '允許使用者查看此伺服器的啟動參數。',

        'settings_title' => '設定',
        'settings_desc' => '允許使用者修改此伺服器的設定。',

        'control_title' => '控制',
        'control_desc' => '允許使用者控制伺服器開關機與發送指令。',

        'user_title' => '使用者',
        'user_desc' => '允許使用者管理此伺服器其他子使用者的權限。他們將無法編輯自己的帳號，亦無法指派自己未擁有的權限。',

        'file_title' => '檔案',
        'file_desc' => '允許使用者修改此伺服器的檔案系統。',

        'allocation_title' => 'Allocation',
        'allocation_desc' => '允許使用者修改此伺服器的連接埠配置。',

        'database_title' => '資料庫',
        'database_desc' => '允許使用者存取此伺服器的資料庫管理。',

        'backup_title' => '備份',
        'backup_desc' => '允許使用者建立與管理伺服器備份。',

        'schedule_title' => '排程',
        'schedule_desc' => '允許使用者存取此伺服器的排程管理。',

        'startup_read' => '允許使用者查看伺服器的啟動變數。',
        'startup_update' => '允許使用者修改伺服器的啟動變數。',
        'startup_docker_image' => '允許使用者修改伺服器執行時所使用的 Docker 映像。',

        'settings_rename' => '允許使用者重新命名此伺服器。',
        'settings_description' => '允許使用者修改此伺服器的描述。',
        'settings_reinstall' => '允許使用者重新安裝此伺服器。',
        'settings_change_icon' => '允許使用者變更此伺服器的圖示。',

        'activity_read' => '允許使用者查看伺服器的活動紀錄。',

        'websocket_connect' => '允許使用者存取此伺服器的 WebSocket。',

        'control_console' => '允許使用者在伺服器主控台輸入指令。',
        'control_start' => '允許使用者啟動伺服器。',
        'control_stop' => '允許使用者停止伺服器。',
        'control_restart' => '允許使用者重啟伺服器。',
        'control_kill' => '允許使用者強制停止伺服器。',

        'user_create' => '允許使用者為伺服器建立新使用者帳號。',
        'user_read' => '允許使用者查看與此伺服器關聯的使用者。',
        'user_update' => '允許使用者修改與此伺服器關聯的其他使用者。',
        'user_delete' => '允許使用者刪除與此伺服器關聯的其他使用者。',

        'file_create' => '允許使用者建立新檔案和資料夾。',
        'file_read' => '允許使用者瀏覽資料夾內容，但無法查看或下載檔案。',
        'file_read_content' => '允許使用者查看指定檔案內容，並可下載該檔案。',
        'file_update' => '允許使用者更新與伺服器相關的檔案和資料夾。',
        'file_delete' => '允許使用者刪除檔案和資料夾。',
        'file_archive' => '允許使用者建立壓縮檔和解壓縮檔案。',
        'file_sftp' => '允許使用者使用 SFTP 用戶端執行上述檔案操作。',

        'allocation_read' => '允許使用者查看目前指派給此伺服器的所有分配。擁有此伺服器任何存取權限的使用者，皆可隨時查看主要分配。',
        'allocation_update' => '允許使用者變更主要伺服器分配，並為每個分配附加備註。',
        'allocation_delete' => '允許使用者從伺服器中刪除分配。',
        'allocation_create' => '允許使用者指派額外分配給伺服器。',

        'database_create' => '允許使用者為伺服器建立新的資料庫。',
        'database_read' => '允許使用者檢視伺服器上的資料庫。',
        'database_update' => '允許使用者修改資料庫。若未同時具備「檢視密碼」權限，將無法修改密碼。',
        'database_delete' => '允許使用者刪除資料庫實例。',
        'database_view_password' => '允許使用者檢視系統中的資料庫密碼。',

        'schedule_create' => '允許使用者為伺服器建立新的排程。',
        'schedule_read' => '允許使用者檢視伺服器的排程。',
        'schedule_update' => '允許使用者修改現有的伺服器排程。',
        'schedule_delete' => '允許使用者刪除伺服器排程。',

        'backup_create' => '允許使用者為此伺服器建立新的備份。',
        'backup_read' => '允許使用者查看此伺服器的所有備份。',
        'backup_delete' => '允許使用者從系統中刪除備份。',
        'backup_download' => '允許使用者下載伺服器備份。注意：此權限將讓使用者取得備份中伺服器的所有檔案。',
        'backup_restore' => '允許使用者還原伺服器備份。注意：此操作會刪除伺服器中的所有檔案。',

        'mount_title' => '掛載',
        'mount_desc' => '允許使用者管理此伺服器的掛載點。',
        'mount_read' => '允許使用者查看掛載點頁面並瀏覽可用的掛載點。',
        'mount_update' => '允許使用者啟用或停用此伺服器的掛載點。',
    ],
];
