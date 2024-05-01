<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Този FQDN или IP адрес не resolve-ва до валиден IP адрес.',
        'fqdn_required_for_ssl' => 'Изисква се fully qualified домейн който resolve-ва до публичен IP адрес за да се използва SSL за този node.',
    ],
    'notices' => [
        'allocations_added' => 'Алокации успешно бе добавени на този node.',
        'node_deleted' => 'Този node успешно бе премахнат от панела.',
        'node_created' => 'Successfully created new node. You can automatically configure the daemon on this machine by visiting the \'Configuration\' tab. <strong>Before you can add any servers you must first allocate at least one IP address and port.</strong>',
        'node_updated' => 'Node information has been updated. If any daemon settings were changed you will need to reboot it for those changes to take effect.',
        'unallocated_deleted' => 'Deleted all un-allocated ports for <code>:ip</code>.',
    ],
];
