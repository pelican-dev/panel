<?php

return [
    'daemon_connection_failed' => 'HTTP/:code応答コードを生成するデーモンと通信しようとしたときに例外が発生しました。この例外はログ収集されました。',
    'node' => [
        'servers_attached' => '削除するには、ノードにサーバーがリンクされていない必要があります。',
        'daemon_off_config_updated' => '<strong>デーモンの設定が更新されました。</strong>しかし、デーモンの設定ファイルを自動的に更新しようとする際にエラーが発生しました。 これらの変更を適用するには、デーモンの設定ファイル(config.yml)を手動で更新する必要があります。',
    ],
    'allocations' => [
        'server_using' => '現在サーバーは割り当てられています。割り当てはサーバーが現在割り当てられていない場合にのみ削除できます。',
        'too_many_ports' => '一度に1000以上のポートを追加することはできません。',
        'invalid_mapping' => ':port のマッピングは無効で、処理することができませんでした。',
        'cidr_out_of_range' => 'CIDR表記では/25から/32までのマスクのみ使用できます。',
        'port_out_of_range' => '割り当てのポートは 1024 以上、65535 以下である必要があります。',
    ],
    'egg' => [
        'delete_has_servers' => 'アクティブなサーバーがアタッチされたEggはパネルから削除できません。',
        'invalid_copy_id' => 'スクリプトをコピーするために選択されたEggが存在しないか、スクリプト自体をコピーしています。',
        'has_children' => 'このEggは、1つ以上の他のEggの親になっています。このEggを削除する前に、それらのEggを削除してください。',
    ],
    'variables' => [
        'env_not_unique' => '環境変数「:name」はこの卵に固有でなければなりません。',
        'reserved_name' => '環境変数「:name」は保護されているため、変数に割り当てることはできません。',
        'bad_validation_rule' => '検証ルール「:rule」は、このアプリケーションの有効なルールではありません。',
    ],
    'importer' => [
        'json_error' => 'JSON ファイルの解析中にエラーが発生しました: :error',
        'file_error' => '指定された JSON ファイルは無効です。',
        'invalid_json_provided' => '指定された JSON ファイルは認識可能な形式ではありません。',
    ],
    'subusers' => [
        'editing_self' => '自分のサブユーザーアカウントの編集は許可されていません。',
        'user_is_owner' => 'このサーバーのサブユーザーとしてサーバーの所有者を追加することはできません。',
        'subuser_exists' => 'そのメールアドレスを持つユーザーは、このサーバーのサブユーザーとしてすでに割り当てられています。',
    ],
    'databases' => [
        'delete_has_databases' => 'アクティブなデータベースがリンクされているデータベースサーバーは削除できません。',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'チェーンタスクの最大インターバルは15分です。',
    ],
    'locations' => [
        'has_nodes' => 'アクティブなノードがアタッチされている場所は削除できません。',
    ],
    'users' => [
        'node_revocation_failed' => '<a href=":link">Node #:node</a>のキーの取り消しに失敗しました。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '自動デプロイメントのために指定された要件を満たすノードは見つかりませんでした。',
        'no_viable_allocations' => '自動デプロイの要件を満たす割り当ては見つかりませんでした。',
    ],
    'api' => [
        'resource_not_found' => 'リクエストされたリソースはこのサーバーに存在しません。',
    ],
];
