<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => '提供された FQDN または IP アドレスは、有効な IP アドレスには解決しません。',
        'fqdn_required_for_ssl' => 'このノードにSSLを使用するには、ドメイン名が必要です。',
    ],
    'notices' => [
        'allocations_added' => 'このノードに割り当てを追加しました。',
        'node_deleted' => 'Node has been successfully removed from the panel.',
        'node_created' => 'Successfully created new node. You can automatically configure the daemon on this machine by visiting the \'Configuration\' tab. <strong>Before you can add any servers you must first allocate at least one IP address and port.</strong>',
        'node_updated' => 'Node information has been updated. If any daemon settings were changed you will need to reboot it for those changes to take effect.',
        'unallocated_deleted' => 'Deleted all un-allocated ports for <code>:ip</code>.',
    ],
];
