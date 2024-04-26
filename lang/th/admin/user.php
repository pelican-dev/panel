<?php

return [
    'exceptions' => [
        'user_has_servers' => 'ไม่สามารถลบผู้ใช้ที่มีเซิร์ฟเวอร์ที่ใช้งานอยู่แนบอยู่กับบัญชีของพวกเขาได้ โปรดลบเซิร์ฟเวอร์ของพวกเขาก่อนดำเนินการต่อ',
        'user_is_self' => 'ไม่สามารถลบบัญชีผู้ใช้ของคุณเองได้',
    ],
    'notices' => [
        'account_created' => 'สร้างบัญชีสำเร็จแล้ว',
        'account_updated' => 'บัญชีได้รับการอัปเดตเรียบร้อยแล้ว',
    ],
    'last_admin' => [
        'hint' => 'This is the last root administrator!',
        'helper_text' => 'You must have at least one root administrator in your system.',
    ],
    'root_admin' => 'Administrator (Root)',
    'language' => [
        'helper_text1' => 'Your language (:state) has not been translated yet!\nBut never fear, you can help fix that by',
        'helper_text2' => 'contributing directly here',
    ],
];
