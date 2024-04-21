<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'デフォルトの割り当て以外に割り当てがないため、デフォルトの割り当てを削除できません。',
        'marked_as_failed' => 'このサーバーは前回、インストールに失敗しています。この状態で現在の状態を切り替えることはできません。',
        'bad_variable' => '変数「:name」の検証エラーが発生しました。',
        'daemon_exception' => 'HTTP/:code応答コードを生成するデーモンと通信しようとしたときに例外が発生しました。この例外はログ収集されました。(リクエストID: :request_id)',
        'default_allocation_not_found' => 'リクエストされたデフォルトの割り当てがこのサーバーの割り当てに見つかりませんでした。',
    ],
    'alerts' => [
        'startup_changed' => 'このサーバーの起動設定が更新されました。このサーバーのEggが変更された場合、再インストールが行われます。',
        'server_deleted' => 'サーバーを削除しました。',
        'server_created' => 'サーバーを作成しました。Daemonがこのサーバーを完全にインストールするまで数分間お待ちください。',
        'build_updated' => 'ビルド詳細を更新しました。一部の変更を有効にするには再起動が必要な場合があります。',
        'suspension_toggled' => 'サーバーの状態が:statusに変更されました。',
        'rebuild_on_boot' => 'このサーバーはDockerコンテナの再構築が必要です。サーバーが次回起動されたときに行われます。',
        'install_toggled' => 'このサーバーのインストールステータスが切り替わりました。',
        'server_reinstalled' => 'このサーバーは今から再インストールを開始するためキューに入れられています。',
        'details_updated' => 'サーバーの詳細が更新されました。',
        'docker_image_updated' => 'このサーバーで使用するデフォルトの Docker イメージを変更しました。この変更を適用するには再起動が必要です。',
        'node_required' => 'このパネルにノードを追加する前に、ロケーションを設定する必要があります。',
        'transfer_nodes_required' => 'サーバーを転送するには、2つ以上のノードが設定されている必要があります。',
        'transfer_started' => 'サーバー転送を開始しました。',
        'transfer_not_viable' => '選択したノードには、このサーバに対応するために必要なディスク容量またはメモリが足りません。',
    ],
];
