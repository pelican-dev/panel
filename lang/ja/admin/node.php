<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => '提供された FQDN または IP アドレスは、有効な IP アドレスには解決しません。',
        'fqdn_required_for_ssl' => 'このノードにSSLを使用するには、ドメイン名が必要です。',
    ],
    'notices' => [
        'allocations_added' => 'このノードに割り当てを追加しました。',
        'node_deleted' => 'ノードがパネルから削除されました。',
        'node_created' => '正常に新しいノードを作成しました。「設定」タブでデーモンを自動的に設定できます。 <strong>サーバーを追加する前に、最初に少なくとも1つのIPアドレスとポートを割り当てる必要があります。</strong>',
        'node_updated' => 'ノード情報が更新されました。デーモンの設定が変更された場合は、変更を反映するために再起動する必要があります。',
        'unallocated_deleted' => '<code>:ip</code> に割り当てられていないポートをすべて削除しました。',
    ],
];
