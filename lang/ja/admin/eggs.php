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
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
