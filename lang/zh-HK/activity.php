<?php

return [
    'auth' => [
        'fail' => '登入失敗',
        'success' => '登入成功',
        'password-reset' => '密碼重設',
        'checkpoint' => '請求雙因素認證',
        'recovery-token' => '使用了雙因素恢復令牌',
        'token' => '通過了雙因素挑戰',
        'ip-blocked' => '阻止了來自未列出IP地址<b>:identifier</b>的請求',
        'sftp' => [
            'fail' => 'SFTP登入失敗',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => '將電子郵件從<b>:old</b>更改為<b>:new</b>',
            'password-changed' => '更改了密碼',
        ],
        'api-key' => [
            'create' => '創建了新的API密鑰<b>:identifier</b>',
            'delete' => '刪除了API密鑰<b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => '向帳戶添加了SSH密鑰<b>:fingerprint</b>',
            'delete' => '從帳戶中移除了SSH密鑰<b>:fingerprint</b>',
        ],
        'two-factor' => [
            'create' => '啟用了雙因素認證',
            'delete' => '禁用了雙因素認證',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '在伺服器上執行了"<b>:command</b>"',
        ],
        'power' => [
            'start' => '啟動了伺服器',
            'stop' => '停止了伺服器',
            'restart' => '重啟了伺服器',
            'kill' => '強制終止了伺服器進程',
        ],
        'backup' => [
            'download' => '下載了備份<b>:name</b>',
            'delete' => '刪除了備份<b>:name</b>',
            'restore' => '還原了備份<b>:name</b>（刪除了文件: <b>:truncate</b>）',
            'restore-complete' => '完成了備份<b>:name</b>的還原',
            'restore-failed' => '未能完成備份<b>:name</b>的還原',
            'start' => '開始了新的備份<b>:name</b>',
            'complete' => '將備份<b>:name</b>標記為完成',
            'fail' => '將備份<b>:name</b>標記為失敗',
            'lock' => '鎖定了備份<b>:name</b>',
            'unlock' => '解鎖了備份<b>:name</b>',
        ],
        'database' => [
            'create' => '創建了新的數據庫<b>:name</b>',
            'rotate-password' => '為數據庫<b>:name</b>輪換了密碼',
            'delete' => '刪除了數據庫<b>:name</b>',
        ],
        'file' => [
            'compress' => '壓縮了<b>:directory:files</b>|壓縮了<b>:count</b>個文件在<b>:directory</b>中',
            'read' => '查看了文件<b>:file</b>的內容',
            'copy' => '創建了文件<b>:file</b>的副本',
            'create-directory' => '創建了目錄<b>:directory:name</b>',
            'decompress' => '解壓了文件<b>:file</b>到<b>:directory</b>',
            'delete' => '刪除了<b>:directory:files</b>|刪除了<b>:count</b>個文件在<b>:directory</b>中',
            'download' => '下載了文件<b>:file</b>',
            'pull' => '從<b>:url</b>下載了遠程文件到<b>:directory</b>',
            'rename' => '移動/重命名了文件<b>:from</b>到<b>:to</b>|移動/重命名了<b>:count</b>個文件在<b>:directory</b>中',
            'write' => '向文件<b>:file</b>寫入了新內容',
            'upload' => '開始了文件上傳',
            'uploaded' => '上傳了文件<b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => '由於權限不足，阻止了SFTP訪問',
            'create' => '創建了文件<b>:files</b>|創建了<b>:count</b>個新文件',
            'write' => '修改了文件<b>:files</b>的內容|修改了<b>:count</b>個文件的內容',
            'delete' => '刪除了文件<b>:files</b>|刪除了<b>:count</b>個文件',
            'create-directory' => '創建了目錄<b>:files</b>|創建了<b>:count</b>個目錄',
            'rename' => '將<b>:from</b>重命名為<b>:to</b>|重命名或移動了<b>:count</b>個文件',
        ],
        'allocation' => [
            'create' => '向伺服器添加了端口<b>:allocation</b>',
            'notes' => '將端口<b>:allocation</b>的註釋從"<b>:old</b>"更新為"<b>:new</b>"',
            'primary' => '將<b>:allocation</b>設置為伺服器的主端口',
            'delete' => '刪除了端口分配<b>:allocation</b>',
        ],
        'schedule' => [
            'create' => '創建了計劃任務<b>:name</b>',
            'update' => '更新了計劃任務<b>:name</b>',
            'execute' => '手動執行了計劃任務<b>:name</b>',
            'delete' => '刪除了計劃任務<b>:name</b>',
        ],
        'task' => [
            'create' => '為計劃任務<b>:name</b>創建了新任務"<b>:action</b>"',
            'update' => '更新了計劃任務<b>:name</b>的任務"<b>:action</b>"',
            'delete' => '刪除了計劃任務<b>:name</b>的任務"<b>:action</b>"',
        ],
        'settings' => [
            'rename' => '將伺服器名稱從"<b>:old</b>"更改為"<b>:new</b>"',
            'description' => '將伺服器描述從"<b>:old</b>"更改為"<b>:new</b>"',
            'reinstall' => '重新安裝了伺服器',
        ],
        'startup' => [
            'edit' => '將變量<b>:variable</b>從"<b>:old</b>"更改為"<b>:new</b>"',
            'image' => '將伺服器的Docker鏡像從<b>:old</b>更新為<b>:new</b>',
        ],
        'subuser' => [
            'create' => '添加了<b>:email</b>作為子用戶',
            'update' => '更新了子用戶<b>:email</b>的權限',
            'delete' => '移除了子用戶<b>:email</b>',
        ],
        'crashed' => '伺服器崩潰',
    ],
];