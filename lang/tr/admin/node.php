<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Sağlanan FQDN veya IP adresi geçerli bir IP adresine çözümlenmiyor.',
        'fqdn_required_for_ssl' => 'Bu düğüm için SSL kullanmak amacıyla genel bir IP adresine çözümlenen tam nitelikli bir alan adı gereklidir.',
    ],
    'notices' => [
        'allocations_added' => 'Tahsisler bu node\'a başarıyla eklendi.',
        'node_deleted' => 'Node has been successfully removed from the panel.',
        'node_created' => 'Successfully created new node. You can automatically configure the daemon on this machine by visiting the \'Configuration\' tab. <strong>Before you can add any servers you must first allocate at least one IP address and port.</strong>',
        'node_updated' => 'Node information has been updated. If any daemon settings were changed you will need to reboot it for those changes to take effect.',
        'unallocated_deleted' => 'Deleted all un-allocated ports for <code>:ip</code>.',
    ],
];
