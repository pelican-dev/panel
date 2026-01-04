<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => '登入失敗',
        'success' => '登入成功',
        'password-reset' => '密碼重置',
        'checkpoint' => '要求兩步驟驗證',
        'recovery-token' => '使用兩步驟驗證恢復代碼',
        'token' => '完成兩步驟驗證挑戰',
        'ip-blocked' => '已封鎖來自未列出的 IP 位址 <b>:identifier</b> 的請求',
        'sftp' => [
            'fail' => 'SFTP 登入失敗',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => '將使用者名稱從 <b>:old</b> 更改為 <b>:new</b>',
            'email-changed' => '將電子郵件從 <b>:old</b> 更改為 <b>:new</b>',
            'password-changed' => '更改了密碼',
        ],
        'api-key' => [
            'create' => '建立了新的 API 金鑰 <b>:identifier</b>',
            'delete' => '刪除了 API 金鑰 <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => '將 SSH 金鑰 <b>:fingerprint</b> 新增到帳戶',
            'delete' => '從帳戶中移除 SSH 金鑰 <b>:fingerprint</b>',
        ],
        'two-factor' => [
            'create' => '啟用兩步驟驗證',
            'delete' => '停用兩步驟驗證',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '在伺服器上執行了 "<b>:command</b>"',
        ],
        'power' => [
            'start' => '啟動了伺服器',
            'stop' => '停止了伺服器',
            'restart' => '重新啟動了伺服器',
            'kill' => '強制終止了伺服器程序',
        ],
        'backup' => [
            'download' => '下載了 <b>:name</b> 備份',
            'delete' => '刪除了 <b>:name</b> 備份',
            'restore' => '還原了 <b>:name</b> 備份（已刪除檔案：<b>:truncate</b>）',
            'restore-complete' => '完成還原 <b>:name</b> 備份',
            'restore-failed' => '無法完成還原 <b>:name</b> 備份',
            'start' => '開始新的備份 <b>:name</b>',
            'complete' => '將 <b>:name</b> 備份標記為完成',
            'fail' => '將 <b>:name</b> 備份標記為失敗',
            'lock' => '鎖定了 <b>:name</b> 備份',
            'unlock' => '解鎖了 <b>:name</b> 備份',
            'rename' => '將備份從 "<b>:old_name</b>" 重新命名為 "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => '建立了新資料庫 <b>:name</b>',
            'rotate-password' => '已為資料庫 <b>:name</b> 輪替密碼',
            'delete' => '刪除了資料庫 <b>:name</b>',
        ],
        'file' => [
            'compress' => '壓縮了 <b>:directory:files</b>|壓縮了 <b>:directory</b> 中的 <b>:count</b> 個檔案',
            'read' => '查看了 <b>:file</b> 的內容',
            'copy' => '建立了 <b>:file</b> 的副本',
            'create-directory' => '建立了目錄 <b>:directory:name</b>',
            'decompress' => '在 <b>:directory</b> 解壓縮了 <b>:file</b>',
            'delete' => '刪除了 <b>:directory:files</b>|刪除了 <b>:directory</b> 中的 <b>:count</b> 個檔案',
            'download' => '下載了 <b>:file</b>',
            'pull' => '從 <b>:url</b> 下載遠端檔案到 <b>:directory</b>',
            'rename' => '將 <b>:from</b> 移動/重新命名為 <b>:to</b>|移動/重新命名了 <b>:directory</b> 中的 <b>:count</b> 個檔案',
            'write' => '寫入新內容到 <b>:file</b>',
            'upload' => '開始檔案上傳',
            'uploaded' => '上傳了 <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => '因權限問題封鎖 SFTP 存取',
            'create' => '建立了 <b>:files</b>|建立了 <b>:count</b> 個新檔案',
            'write' => '修改了 <b>:files</b> 的內容|修改了 <b>:count</b> 個檔案的內容',
            'delete' => '刪除了 <b>:files</b>|刪除了 <b>:count</b> 個檔案',
            'create-directory' => '建立了 <b>:files</b> 目錄|建立了 <b>:count</b> 個目錄',
            'rename' => '將 <b>:from</b> 重新命名為 <b>:to</b>|重新命名或移動了 <b>:count</b> 個檔案',
        ],
        'allocation' => [
            'create' => '新增了 <b>:allocation</b> 到伺服器',
            'notes' => '將 <b>:allocation</b> 的備註從 "<b>:old</b>" 更新為 "<b>:new</b>"',
            'primary' => '將 <b>:allocation</b> 設定為主要伺服器分配',
            'delete' => '刪除了 <b>:allocation</b> 分配',
        ],
        'schedule' => [
            'create' => '建立了 <b>:name</b> 排程',
            'update' => '更新了 <b>:name</b> 排程',
            'execute' => '手動執行了 <b>:name</b> 排程',
            'delete' => '刪除了 <b>:name</b> 排程',
        ],
        'task' => [
            'create' => '為 <b>:name</b> 排程建立了新的 "<b>:action</b>" 任務',
            'update' => '更新了 <b>:name</b> 排程的 "<b>:action</b>" 任務',
            'delete' => '刪除了 <b>:name</b> 排程的 "<b>:action</b>" 任務',
        ],
        'settings' => [
            'rename' => '將伺服器名稱從 "<b>:old</b>" 更改為 "<b>:new</b>"',
            'description' => '將伺服器描述從 "<b>:old</b>" 更改為 "<b>:new</b>"',
            'reinstall' => '重新安裝伺服器',
        ],
        'startup' => [
            'edit' => '將 <b>:variable</b> 變數從 "<b>:old</b>" 更改為 "<b>:new</b>"',
            'image' => '將伺服器的 Docker 映像檔從 <b>:old</b> 更新為 <b>:new</b>',
            'command' => '將伺服器的啟動指令從 <b>:old</b> 更新為 <b>:new</b>',
        ],
        'subuser' => [
            'create' => '將 <b>:email</b> 新增為子使用者',
            'update' => '更新了 <b>:email</b> 的子使用者權限',
            'delete' => '移除了 <b>:email</b> 子使用者',
        ],
        'crashed' => '伺服器崩潰',
    ],
];
