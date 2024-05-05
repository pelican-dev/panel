<?php

return [
    'user' => [
        'search_users' => 'กรอกชื่อผู้ใช้, ID ผู้ใช้, หรืออีเมล',
        'select_search_user' => 'ID ของผู้ใช้ที่จะลบ (ป้อน \'0\' เพื่อค้นหาอีกครั้ง)',
        'deleted' => 'ลบผู้ใช้ออกจากแผงควบคุมสำเร็จแล้ว',
        'confirm_delete' => 'คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้รายนี้ออกจากแผงควบคุม?',
        'no_users_found' => 'ไม่พบผู้ใช้สำหรับคำค้นหาที่ให้ไว้',
        'multiple_found' => 'พบหลายบัญชีสำหรับผู้ใช้ที่ระบุ ไม่สามารถลบผู้ใช้ได้เนื่องจากการตั้งค่าสถานะ --no-interaction',
        'ask_admin' => 'ผู้ใช้รายนี้เป็นแอดมินระบบหรือไม่?',
        'ask_email' => 'อีเมล',
        'ask_username' => 'ชื่อผู้ใช้',
        'ask_name_first' => 'ชื่อจริง',
        'ask_name_last' => 'นามสกุล',
        'ask_password' => 'รหัสผ่าน',
        'ask_password_tip' => 'หากคุณต้องการสร้างบัญชีด้วยรหัสผ่านแบบสุ่มที่ส่งอีเมลถึงผู้ใช้ ให้รันคำสั่งนี้อีกครั้ง (CTRL+C) และส่งแฟล็ก `--no-password`',
        'ask_password_help' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร และประกอบด้วยตัวพิมพ์ใหญ่และตัวเลขอย่างน้อยหนึ่งตัว',
        '2fa_help_text' => [
            'คำสั่งนี้จะปิดใช้งาน 2FA สำหรับบัญชีผู้ใช้หากเปิดใช้งานอยู่ สิ่งนี้ควรใช้เป็นคำสั่งการกู้คืนบัญชีหากผู้ใช้ถูกล็อคออกจากบัญชีเท่านั้น',
            'หากนี่ไม่ใช่สิ่งที่คุณต้องการทำ ให้กด CTRL+C เพื่อออกจากกระบวนการนี้',
        ],
        '2fa_disabled' => '2FA ถูกปิดใช้งานสำหรับ :email',
    ],
    'schedule' => [
        'output_line' => 'กำลังจัดส่งงานสำหรับงานแรกใน `:schedule` (:hash)',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'กำลังลบไฟล์สำรองข้อมูลบริการ :file',
    ],
    'server' => [
        'rebuild_failed' => 'สร้างคำขอใหม่สำหรับ ":name" (#:id) บนโหนด ":node" ล้มเหลว โดยมีข้อผิดพลาด: :message',
        'reinstall' => [
            'failed' => 'คำขอติดตั้งใหม่สำหรับ ":name" (#:id) บนโหนด ":node" ล้มเหลวโดยมีข้อผิดพลาด: :message',
            'confirm' => 'คุณกำลังจะติดตั้งกลุ่มเซิร์ฟเวอร์ใหม่ คุณต้องการดำเนินการต่อหรือไม่?',
        ],
        'power' => [
            'confirm' => 'คุณกำลังจะดำเนินการ :action กับ :count เซิร์ฟเวอร์คุณต้องการดำเนินการต่อหรือไม่?',
            'action_failed' => 'Power action request for ":name" (#:id) on node ":node" failed with error: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Host (e.g. smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Port',
            'ask_smtp_username' => 'SMTP Username',
            'ask_smtp_password' => 'SMTP Password',
            'ask_mailgun_domain' => 'Mailgun Domain',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint',
            'ask_mailgun_secret' => 'Mailgun Secret',
            'ask_mandrill_secret' => 'Mandrill Secret',
            'ask_postmark_username' => 'Postmark API Key',
            'ask_driver' => 'Which driver should be used for sending emails?',
            'ask_mail_from' => 'Email address emails should originate from',
            'ask_mail_name' => 'Name that emails should appear from',
            'ask_encryption' => 'Encryption method to use',
        ],
    ],
];
