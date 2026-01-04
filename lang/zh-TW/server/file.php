<?php

return [
    'title' => '檔案',
    'name' => '名稱',
    'size' => '大小',
    'modified_at' => '修改時間',
    'actions' => [
        'open' => '開啟',
        'download' => '下載',
        'copy' => [
            'title' => '複製',
            'notification' => '檔案已複製',
        ],
        'upload' => [
            'title' => '上傳',
            'from_files' => '上傳檔案',
            'from_url' => '從 URL 上傳',
            'url' => 'URL',
            'drop_files' => '拖曳檔案以上傳',
            'success' => '檔案上傳成功',
            'failed' => '上傳檔案失敗',
            'header' => '正在上傳檔案',
            'error' => '上傳時發生錯誤',
        ],
        'rename' => [
            'title' => '重新命名',
            'file_name' => '檔案名稱',
            'notification' => '檔案已重新命名',
        ],
        'move' => [
            'title' => '移動',
            'directory' => '目錄',
            'directory_hint' => '輸入新目錄，相對於目前目錄。',
            'new_location' => '新位置',
            'new_location_hint' => '輸入此檔案或資料夾的位置，相對於目前目錄。',
            'notification' => '檔案已移動',
            'bulk_notification' => ':count 個檔案已移動到 :directory',
        ],
        'permissions' => [
            'title' => '權限',
            'read' => '讀取',
            'write' => '寫入',
            'execute' => '執行',
            'owner' => '擁有者',
            'group' => '群組',
            'public' => '公開',
            'notification' => '權限已更改為 :mode',
        ],
        'archive' => [
            'title' => '壓縮',
            'archive_name' => '壓縮檔名稱',
            'notification' => '壓縮檔已建立',
            'extension' => '副檔名',
        ],
        'unarchive' => [
            'title' => '解壓縮',
            'notification' => '解壓縮完成',
        ],
        'new_file' => [
            'title' => '新檔案',
            'file_name' => '新檔案名稱',
            'syntax' => '語法突顯',
            'create' => '建立',
        ],
        'new_folder' => [
            'title' => '新資料夾',
            'folder_name' => '新資料夾名稱',
        ],
        'nested_search' => [
            'title' => '巢狀搜尋',
            'search_term' => '搜尋字詞',
            'search_term_placeholder' => '輸入搜尋字詞，例如 *.txt',
            'search' => '搜尋',
            'search_for_term' => '搜尋 :term',
        ],
        'delete' => [
            'notification' => '檔案已刪除',
            'bulk_notification' => ':count 個檔案已刪除',
        ],
        'edit' => [
            'title' => '正在編輯：:file',
            'save_close' => '儲存並關閉',
            'save' => '儲存',
            'cancel' => '取消',
            'notification' => '檔案已儲存',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> 太大了！',
            'body' => '最大為 :max',
        ],
        'file_not_found' => [
            'title' => '找不到 <code>:name</code>！',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> 是一個目錄',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> 已經存在！',
        ],
        'files_node_error' => [
            'title' => '無法載入檔案！',
        ],
        'pelicanignore' => [
            'title' => '您正在編輯 <code>.pelicanignore</code> 檔案！',
            'body' => '此處列出的任何檔案或目錄都將被排除在備份之外。支援使用星號 (<code>*</code>) 的萬用字元。<br>您可以透過在規則前加上驚嘆號 (<code>!</code>) 來否定先前的規則。',
        ],
    ],
];
