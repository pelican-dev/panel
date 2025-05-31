<?php

return [
    'edit' => [
        'editing_title' => '正在編輯 :path',
        'save&close' => '儲存並關閉',
        'save' => '儲存',
        'file_saved' => '檔案已儲存',
        'cancel' => '取消',
        'syntax_highlight' => '語法標示',
        'too_large' => [
            'title' => '<code> :file </code> 檔案過大！',
            'body' => '最大值為 :max_size',
        ],
        'not_found' => [
            'title' => '<code> :file </code> 找不到！',
        ],
        'is_directory' => [
            'title' => '<code> :file </code> 是一個目錄',
        ],
        'ignorefile' => [
            'alert_title' => '您正在編輯 <code>.pelicanignore</code> 檔案！',
            'alert_body' => '此處列出的任何檔案或目錄將被排除在備份之外。支援使用星號 (<code>*</code>) 作為萬用字元。<br>您可以在規則前加上驚嘆號 (<code>!</code>) 來否定先前的規則。',
        ],
        'connection_error' => '無法連接到節點！',
    ],
    'list' => [
        'open' => '開啟',
        'rename' => '重新命名',
        'rename_file' => '檔案名稱',
        'file_renamed' => '檔案已重新命名',
        'file_copied' => '檔案已複製',
        'download' => '下載',
        'move' => '移動',
        'move_to' => '新位置',
        'move_to_desc' => '輸入此檔案或資料夾的新位置，相對於當前目錄。',
        'file_moved' => '檔案已移動',
        'file_permissions' => '權限',
        'notice_change_perms' => '權限已更改為 :mode',
        'archive' => '壓縮',
        'archive_name' => '壓縮檔名稱',
        'archive_created' => '壓縮檔已建立',
        'unarchive' => '解壓縮',
        'unarchive_completed' => '解壓縮完成',
        'deletefileconfirm' => '刪除檔案？',
        'bulkmove' => [
            'label' => '目標目錄',
            'description' => '輸入新目錄，相對於當前目錄。',
            'notice' => '已移動 :count 個檔案至 :destination',
        ],
        'bulkarchive' => [
            'label' => '壓縮檔名稱',
            'archive_created' => '壓縮檔已建立',
        ],
        'bulkdelete' => [
            'notice' => '已刪除 :count 個檔案。',
        ],
        'createfile' => [
            'new_file' => '新增檔案',
            'title' => '建立',
            'file_name' => '檔案名稱',
            'syntax_helper' => '語法高亮',
        ],
        'createfolder' => [
            'new_folder' => '新增資料夾',
            'folder_name' => '資料夾名稱',
        ],
        'upload' => [
            'label' => '上傳',
            'uploadfiles_select' => '上傳檔案',
            'uploadfiles_fromURL' => '從網址上傳',
        ],
        'search' => [
            'label' => '全域搜尋',
            'desc' => '輸入搜尋關鍵字，例如 *.txt',
        ],
    ],
    'search' => [
        'title' => '全域搜尋',
        'searchterm' => '搜尋 :term',
    ],
];