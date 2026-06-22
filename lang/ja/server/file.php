<?php

return [
    'title' => 'ファイル',
    'name' => '名前',
    'size' => 'サイズ',
    'modified_at' => '更新日',
    'actions' => [
        'open' => '開く',
        'download' => 'ダウンロード',
        'copy' => [
            'title' => 'コピー',
            'notification' => 'ファイルがコピーされました',
        ],
        'upload' => [
            'title' => 'アップロード',
            'from_files' => 'ファイルをアップロード',
            'from_url' => 'URLからアップロード',
            'url' => 'URL',
            'drop_files' => 'ドラッグアンドドロップでアップロード',
            'success' => 'ファイルのアップロードに成功しました',
            'failed' => 'ファイルのアップロードに失敗しました',
            'header' => 'ファイルをアップロード中',
            'error' => 'アップロード中にエラーが発生しました',
        ],
        'rename' => [
            'title' => '名前の変更',
            'file_name' => 'ファイル名',
            'notification' => 'ファイルの名前を変更しました',
        ],
        'move' => [
            'title' => '移動',
            'directory' => 'フォルダ',
            'directory_hint' => '現在のフォルダからの相対パスでフォルダを入力してください',
            'new_location' => '新しい場所',
            'new_location_hint' => '現在のディレクトリからの相対パスで、このファイルまたはフォルダの場所を入力してください。',
            'notification' => 'ファイルを移動しました',
            'bulk_notification' => ':count 個のファイルを :directory に移動しました',
        ],
        'permissions' => [
            'title' => '権限',
            'read' => '読み込み',
            'write' => '書き込み',
            'execute' => '実行',
            'owner' => 'オーナー',
            'group' => 'グループ',
            'public' => 'パブリック',
            'notification' => '権限を :mode に変更しました',
        ],
        'archive' => [
            'title' => '圧縮',
            'archive_name' => '圧縮名',
            'notification' => '圧縮ファイルを作成しました',
            'extension' => '拡張子',
        ],
        'unarchive' => [
            'title' => '解凍',
            'notification' => '解凍が完了しました',
        ],
        'new_file' => [
            'title' => '新規ファイル',
            'file_name' => '新規ファイル名',
            'syntax' => 'シンタックスハイライト',
            'create' => '作成',
        ],
        'new_folder' => [
            'title' => '新規フォルダー',
            'folder_name' => '新規フォルダー名',
        ],
        'nested_search' => [
            'title' => '再帰検索',
            'search_term' => '検索ワード',
            'search_term_placeholder' => '検索ワードを入力（例: *.txt）',
            'search' => '検索',
            'search_for_term' => ':term を検索',
        ],
        'delete' => [
            'notification' => 'ファイルを削除しました',
            'bulk_notification' => ':count 個のファイルを削除しました',
        ],
        'edit' => [
            'title' => '編集中: :file',
            'save_close' => '保存して閉じる',
            'save' => '保存',
            'cancel' => 'キャンセル',
            'notification' => 'ファイルを保存しました',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> が大きすぎます！',
            'body' => '最大: :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> が見つかりません！',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> はディレクトリです',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> はすでに存在します！',
        ],
        'files_node_error' => [
            'title' => 'ファイルを読み込めませんでした！',
        ],
        'pelicanignore' => [
            'title' => '<code>.pelicanignore</code> ファイルを編集しています！',
            'body' => 'ここに記載されたファイルやディレクトリはバックアップから除外されます。アスタリスク（<code>*</code>）を使ったワイルドカードがサポートされています。<br>感嘆符（<code>!</code>）を前に付けることで、前のルールを否定できます。',
        ],
    ],
];
