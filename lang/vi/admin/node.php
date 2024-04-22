<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'FQDN hoặc địa chỉ IP được cung cấp không cho ra địa chỉ IP hợp lệ.',
        'fqdn_required_for_ssl' => 'Một tên miền đầy đủ có thể cho ra một địa chỉ IP công cộng là cần thiết để sử dụng SSL cho node này.',
    ],
    'notices' => [
        'allocations_added' => 'Allocations have successfully been added to this node.',
        'node_deleted' => 'Node đã được xóa thành công khỏi bảng điều khiển.',
        'node_created' => 'Successfully created new node. You can automatically configure the daemon on this machine by visiting the \'Configuration\' tab. <strong>Before you can add any servers you must first allocate at least one IP address and port.</strong>',
        'node_updated' => 'Node information has been updated. If any daemon settings were changed you will need to reboot it for those changes to take effect.',
        'unallocated_deleted' => 'Deleted all un-allocated ports for <code>:ip</code>.',
    ],
];
