<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'เข้าระบบไม่สำเร็จ',
        'success' => 'เข้าสู่ระบบแล้ว',
        'password-reset' => 'ตั้งรหัสผ่านใหม่',
        'reset-password' => 'ขอตั้งรหัสผ่านใหม่สำเร็จ',
        'checkpoint' => 'ขอเปิดใช้งานการยืนยันตัวตนแบบ 2 ขั้นตอนแล้ว',
        'recovery-token' => 'ใช้รหัสกู้คืินการยืนยันตัวตนแบบ 2 ขั้นตอนแล้ว',
        'token' => 'ผ่านการยืนยันตัวตนแบบ 2 ขั้นตอนแล้ว',
        'ip-blocked' => 'IP :identifier ถูกบล็อกเนื่องจากไม่ได้อยู่ในรายการ',
        'sftp' => [
            'fail' => 'เข้าสู่ระบบ SFTP ไม่สำเร็จ',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'เปลี่ยนอีเมลจาก :old เป็น :new สำเร็จ',
            'password-changed' => 'เปลี่ยนรหัสผ่านสำเร็จ',
        ],
        'api-key' => [
            'create' => 'สร้าง API key ใหม่แล้ว :identifier',
            'delete' => 'ลบ API key :identifier สำเร็จ',
        ],
        'ssh-key' => [
            'create' => 'ผูก SSH key :fingerprint กับบัญชีสำเจ็จ',
            'delete' => 'ลบ SSH key :fingerprint จากบัญชีสำเร็จ',
        ],
        'two-factor' => [
            'create' => 'เปิดการยืนยันตัวตนแบบ 2 ขั้นตอนแล้ว',
            'delete' => 'ปิดการยืนยันตัวตนแบบ 2 ขั้นตอนแล้ว',
        ],
    ],
    'server' => [
        'reinstall' => 'ติดตั้งเซิฟเวอร์ใหม่สำเร็จ',
        'console' => [
            'command' => 'ใช้คำสั้ง ":command" บนเซิฟเวอร์',
        ],
        'power' => [
            'start' => 'เปิดเซิฟเวอร์แล้ว',
            'stop' => 'ปิดเซิฟเวอร์แล้ว',
            'restart' => 'รีสตาร์ทเซิฟเวอร์แล้ว',
            'kill' => 'ฆ่าโปรเซสของเซิฟเวอร์แล้ว',
        ],
        'backup' => [
            'download' => 'ดาวโหลดข้อมูลสำรอง :name แล้ว',
            'delete' => 'ลบข้อมูลสำรอง :name แล้ว',
            'restore' => 'กู้คืนข้อมูลสำรอง :name (ลบไฟล์ :truncate)',
            'restore-complete' => 'กู้คืนข้อมูลจากข้อมูลสำรอง :name สมบูรณ์',
            'restore-failed' => 'กู้คืนข้อมูลจากข้อมูลสำรอง :name ไม่สำเร็จ',
            'start' => 'เริ่มสำรองข้อมูล :name',
            'complete' => 'ทำเครื่องหมายว่าสำรองข้อมูล :name แล้ว',
            'fail' => 'ทำเครื่องหมายว่าสำรองข้อมูล :name ไม่สำเร็จ',
            'lock' => 'ล็อกข้อมูลสำรอง :name แล้ว',
            'unlock' => 'ปลดล็อกข้อมูลสำรอง :name แล้ว',
        ],
        'database' => [
            'create' => 'สร้างฐานข้อมูลใหม่ :name แล้ว',
            'rotate-password' => 'หมุนเปลี่ยนรหัสผ่านฐานข้อมูล :name แล้ว',
            'delete' => 'ลบฐานข้อมูลชื่อ :name แล้ว',
        ],
        'file' => [
            'compress_one' => 'บีบอัด :directory:file แล้ว',
            'compress_other' => 'บีบอัด :count ไฟล์ ใน :directory สำเร็จ',
            'read' => 'ดูเนื้อหาของไฟล์ :file',
            'copy' => 'คัดลอกไฟล์ :file แล้ว',
            'create-directory' => 'สร้างโฟลเดอร์ :directory:name แล้ว',
            'decompress' => 'ดีคอมเพรสไฟล์ :file ในโฟลเดอร์ :directory แล้ว',
            'delete_one' => 'ลบไฟล์ :directory:file.0 แล้ว',
            'delete_other' => 'ลบไฟล์จำนวน :count ในโฟล์เดอร์ :directory',
            'download' => 'ดาวน์โหลดไฟล์ :file แล้ว',
            'pull' => 'ดาวน์โหลดไฟล์จากรีโมท :url ไปที่ :directory แล้ว',
            'rename_one' => 'เปลี่ยนชื่อจาก :directory:files.0.from เป็น :directory:files.0 แล้ว',
            'rename_other' => 'เปลี่ยนชื่อ :count ไฟล์ในโฟล์เดอร์ :directory แล้ว',
            'write' => 'เขียนเนื้อหาในไฟล์ :file แล้ว',
            'upload' => 'เริ่มต้นอับโหลดไฟล์แล้ว',
            'uploaded' => 'Uploaded :directory:file',
        ],
        'sftp' => [
            'denied' => 'Blocked SFTP access due to permissions',
            'create_one' => 'Created :files.0',
            'create_other' => 'Created :count new files',
            'write_one' => 'Modified the contents of :files.0',
            'write_other' => 'Modified the contents of :count files',
            'delete_one' => 'Deleted :files.0',
            'delete_other' => 'Deleted :count files',
            'create-directory_one' => 'Created the :files.0 directory',
            'create-directory_other' => 'Created :count directories',
            'rename_one' => 'Renamed :files.0.from to :files.0.to',
            'rename_other' => 'Renamed or moved :count files',
        ],
        'allocation' => [
            'create' => 'Added :allocation to the server',
            'notes' => 'Updated the notes for :allocation from ":old" to ":new"',
            'primary' => 'Set :allocation as the primary server allocation',
            'delete' => 'Deleted the :allocation allocation',
        ],
        'schedule' => [
            'create' => 'Created the :name schedule',
            'update' => 'Updated the :name schedule',
            'execute' => 'Manually executed the :name schedule',
            'delete' => 'Deleted the :name schedule',
        ],
        'task' => [
            'create' => 'Created a new ":action" task for the :name schedule',
            'update' => 'Updated the ":action" task for the :name schedule',
            'delete' => 'Deleted a task for the :name schedule',
        ],
        'settings' => [
            'rename' => 'Renamed the server from :old to :new',
            'description' => 'Changed the server description from :old to :new',
        ],
        'startup' => [
            'edit' => 'Changed the :variable variable from ":old" to ":new"',
            'image' => 'Updated the Docker Image for the server from :old to :new',
        ],
        'subuser' => [
            'create' => 'Added :email as a subuser',
            'update' => 'Updated the subuser permissions for :email',
            'delete' => 'Removed :email as a subuser',
        ],
    ],
];
