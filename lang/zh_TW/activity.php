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
        'success' => '已登入',
        'password-reset' => '重置密碼',
        'checkpoint' => '已請求雙重驗證',
        'recovery-token' => '已使用雙重驗證恢復碼',
        'token' => '已通過雙重驗證',
        'ip-blocked' => '已封鎖來自未列出 IP 位址對 <b>:identifier</b> 的請求',
        'sftp' => [
            'fail' => 'SFTP 登入失敗',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => '將使用者名稱從 <b>:old</b> 變更為 <b>:new</b>',
            'email-changed' => '將電子郵件從 <b>:old</b> 變更為 <b>:new</b>',
            'password-changed' => '修改密碼',
        ],
        'api-key' => [
            'create' => '已建立新的 API 金鑰 <b>:identifier</b>',
            'delete' => '已刪除 API 金鑰 <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => '已將 SSH 金鑰 <b>:fingerprint</b> 新增至帳戶',
            'delete' => '已將 SSH 金鑰 <b>:fingerprint</b> 從帳戶刪除',
        ],
        'two-factor' => [
            'create' => '已啟用雙重認證',
            'delete' => '已禁用雙重認證',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '已在伺服器上執行 "<b>:command</b>" 指令',
        ],
        'power' => [
            'start' => '啟動伺服器',
            'stop' => '停止伺服器',
            'restart' => '重新啟動伺服器',
            'kill' => '停止伺服器進程',
        ],
        'backup' => [
            'download' => '已下載備份 <b>:name</b>',
            'delete' => '已刪除備份 <b>:name</b>',
            'restore' => '已還原 <b>:name</b> 備份檔（刪除現存檔案：<b>:truncate</b>）',
            'restore-complete' => '已完成 <b>:name</b> 備份的還原',
            'restore-failed' => '無法完成 <b>:name</b> 備份的還原',
            'start' => '已開始新的備份 <b>:name</b>',
            'complete' => '已將 <b>:name</b> 備份標記為已完成',
            'fail' => '已將 <b>:name</b> 備份標記為失敗',
            'lock' => '已鎖定 <b>:name</b> 備份',
            'unlock' => '已解鎖 <b>:name</b> 備份',
            'rename' => '已將備份從 "<b>:old_name</b>" 重新命名為 "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => '已建立新的資料庫 <b>:name</b>',
            'rotate-password' => '已更換資料庫 <b>:name</b> 的密碼',
            'delete' => '已刪除資料庫 <b>:name</b>',
        ],
        'file' => [
            'compress' => '已壓縮 <b>:directory:files</b>|已將 <b>:directory</b> 中的 <b>:count</b> 個檔案壓縮',
            'read' => '查看了 <b>:file</b> 的內容',
            'copy' => '已複製 <b>:file</b>',
            'create-directory' => '已建立資料夾 <b>:directory:name</b>',
            'decompress' => '已解壓縮 <b>:directory</b> 中的 <b>:file</b>',
            'delete' => '已刪除 <b>:directory:files</b>|已將 <b>:directory</b> 中的 <b>:count</b> 個檔案刪除',
            'download' => '已下載 <b>:file</b>',
            'pull' => '已將遠端檔案從 <b>:url</b> 下載到 <b>:directory</b>',
            'rename' => '已移動或重新命名 <b>:from</b> 至 <b>:to</b>|已將 <b>:directory</b> 中的 <b>:count</b> 個檔案移動或重新命名',
            'write' => '已將新內容寫入 <b>:file</b>',
            'upload' => '已開始上傳檔案',
            'uploaded' => '已上傳 <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => '由於權限問題，SFTP 存取被拒絕',
            'create' => '已建立 <b>:files</b>|已建立 <b>:count</b> 個新檔案',
            'write' => '已修改 <b>:files</b> 的內容|已修改 <b>:count</b> 個檔案的內容',
            'delete' => '已刪除 <b>:files</b>|已刪除 <b>:count</b> 個檔案',
            'create-directory' => '已建立 <b>:files</b> 目錄|已建立 <b>:count</b> 個目錄',
            'rename' => '已將 <b>:from</b> 重新命名為 <b>:to</b>|已重新命名或移動 <b>:count</b> 個檔案',
        ],
        'allocation' => [
            'create' => '已將 <b>:allocation</b> 新增至伺服器',
            'notes' => '已將 <b>:allocation</b> 的備註從 "<b>:old</b>" 更新為 "<b>:new</b>"',
            'primary' => '已將 <b>:allocation</b> 設定為主伺服器分配',
            'delete' => '已刪除 <b>:allocation</b> 分配',
        ],
        'schedule' => [
            'create' => '已建立排程 <b>:name</b>',
            'update' => '已更新排程 <b>:name</b>',
            'execute' => '已手動執行排程 <b>:name</b>',
            'delete' => '已刪除排程 <b>:name</b>',
        ],
        'task' => [
            'create' => '已為排程 <b>:name</b> 建立新的「<b>:action</b>」任務',
            'update' => '已更新排程 <b>:name</b> 的「<b>:action</b>」任務',
            'delete' => '已刪除排程 <b>:name</b> 的「<b>:action</b>」任務',
        ],
        'settings' => [
            'rename' => '已將伺服器名稱從「<b>:old</b>」變更為「<b>:new</b>」',
            'description' => '已將伺服器描述從「<b>:old</b>」變更為「<b>:new</b>」',
            'reinstall' => '已重新安裝伺服器',
        ],
        'startup' => [
            'edit' => '已將變數 <b>:variable</b> 從「<b>:old</b>」變更為「<b>:new</b>」',
            'image' => '已將伺服器的 Docker 映像從 <b>:old</b> 更新為 <b>:new</b>',
            'command' => '已將伺服器的啟動命令從 <b>:old</b> 更新為 <b>:new</b>',
        ],
        'subuser' => [
            'create' => '已將 <b>:email</b> 新增為子使用者',
            'update' => '已更新 <b>:email</b> 的子使用者權限',
            'delete' => '已將 <b>:email</b> 從子使用者中移除',
        ],
        'mount' => [
            'update' => '已更新伺服器的掛載',
        ],
        'crashed' => '伺服器已崩潰',
    ],
];
