<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'FQDN หรือที่อยู่ IP ที่ระบุไม่สามารถติดต่อได้หรือที่อยู่ IP ที่ถูกต้อง',
        'fqdn_required_for_ssl' => 'สำหรับการใช้ SSL กับโหนดนี้ จำเป็นต้องมีชื่อโดเมนที่ระบุอย่างเต็มรูปแบบซึ่งสามารถแปลเป็นที่อยู่ IP สาธารณะ',
    ],
    'notices' => [
        'allocations_added' => 'เพิ่มพอร์ตไปยังโหนดนี้สำเร็จแล้ว',
        'node_deleted' => 'โหนดถูกลบออกจากแผงเรียบร้อยแล้ว',
        'node_created' => 'Successfully created new node. You can automatically configure the daemon on this machine by visiting the \'Configuration\' tab. <strong>Before you can add any servers you must first allocate at least one IP address and port.</strong>',
        'node_updated' => 'Node information has been updated. If any daemon settings were changed you will need to reboot it for those changes to take effect.',
        'unallocated_deleted' => 'Deleted all un-allocated ports for <code>:ip</code>.',
    ],
];
