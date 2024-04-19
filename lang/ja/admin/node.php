<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => '入力されたFQDNまたはIPアドレスは、有効なIPアドレスに解決しません。',
        'fqdn_required_for_ssl' => 'このノードにSSLを使用するには、公開IPアドレスに解決するFQDNが必要です。',
    ],
    'notices' => [
        'allocations_added' => '割り当てを追加しました。',
        'node_deleted' => 'ノードを削除しました。',
        'node_created' => 'ノードを作成しました。「設定」タブでDaemonを自動的に設定できます。 <strong>サーバーを追加する前に、割り当てを追加してください。</strong>',
        'node_updated' => 'ノードを更新しました。Deamon設定を変更した場合、適用のため再起動が必要です。',
        'unallocated_deleted' => '<code>:ip</code>に割り当てられていないポートをすべて削除しました。',
    ],
];
