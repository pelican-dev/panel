<?php

return [
    'notices' => [
        'imported' => 'このEggと関連する変数を正常にインポートしました。',
        'updated_via_import' => 'ファイルを使用してこのEggを更新しました。',
        'deleted' => 'Eggを削除しました。',
        'updated' => 'Eggの設定を更新しました。',
        'script_updated' => 'Eggのインストールスクリプトが更新されました。サーバーのインストール時に実行されます。',
        'egg_created' => 'Eggを作成しました。このEggを適用するには、実行中のDaemonを再起動してください。',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => '変数「:variable」を削除しました。再起動後は使用できなくなります。',
            'variable_updated' => '変数「:variable」を更新しました。再起動後に適用されます。',
            'variable_created' => '変数が作成され、このEggに割り当てられました。',
        ],
    ],
    'descriptions' => [
        'name' => 'このEggの識別子として使用する、シンプルな名前です。',
        'description' => '必要に応じてPanelに表示されるEggの説明です。',
        'uuid' => 'Wingsが識別子として使用する、Eggの識別子です。',
        'author' => 'このバージョンのEggの作者です。別の作成者から新しいEgg構成をアップロードすると、変更されます。',
        'force_outgoing_ip' => 'すべての発信ネットワークトラフィックの送信元IPが、サーバーのプライマリ割り当てIPのIPにNAT変換されるように強制します。 ノードに複数のパブリックIPアドレスがある場合、特定のゲームが正しく動作するために必要です。
このオプションを有効にすると、このエッグを使用するサーバーの内部ネットワークが無効になり、 同じノード上の他のサーバーに内部的にアクセスできなくなります。 ',
        'startup' => 'このEggで作成された新しいサーバーに使用するデフォルトの起動コマンドです。',
        'docker_images' => 'このEggを使用するサーバーで使用できるDockerイメージです。',
    ],
];
