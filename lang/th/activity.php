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
            'uploaded' => 'อับโหลด :directory:file แล้ว',
        ],
        'sftp' => [
            'denied' => 'บล็อกการเข้าถึง SFTP เนื่องจากไม่มีสิทธื์เข้าถึงแล้ว',
            'create_one' => 'สร้างไฟล์ :files.0 แล้ว',
            'create_other' => 'สร้างไฟล์จำนวน :files ไฟล์',
            'write_one' => 'แก้ไขเนื้อหาของไฟล์ :files.0 แล้ว',
            'write_other' => 'แก้ไขเนื้อของ :count ไฟล์แล้ว',
            'delete_one' => 'ลบ :files.0 แล้ว',
            'delete_other' => 'ลบ :count ไฟล์แล้ว',
            'create-directory_one' => 'สร้างไฟล์ :files.0 ในโฟลเดอร์แล้ว',
            'create-directory_other' => 'สร้าง :count โฟลเดอร์แล้ว',
            'rename_one' => 'ไปเปลี่ยนชื่อไฟล์จาก :files.0.from เป็น :files.0.to แล้ว',
            'rename_other' => 'เปลี่ยนชื่อหรือย้าย :count ไฟล์แล้ว',
        ],
        'allocation' => [
            'create' => 'เพิ่มพอร์ต :alloction สำหรับเซิฟเวอร์แล้ว',
            'notes' => 'ปรับปรุงเนื้อหาโน๊ตของ :allocation จาก :old เป็น :new แล้ว',
            'primary' => 'ตั้งพอร์ต :allocation เป็นพอร์ตหลักแล้ว',
            'delete' => 'ลบพอร์ต :allocation แล้ว',
        ],
        'schedule' => [
            'create' => 'สร้างกำหนดการ :name แล้ว',
            'update' => 'แก้ไขกำหนดการ :name แล้ว',
            'execute' => 'สั่งดำเนินการด้วยมือ กำหนดการ :name แล้ว',
            'delete' => 'ลบกำหนดการ :name แล้ว',
        ],
        'task' => [
            'create' => 'สร้างงาน :action สำหรับกำหนดการ :name แล้ว',
            'update' => 'ปรังปรุงงาน :action สำหรับกำหนดการ :name แล้ว',
            'delete' => 'ลบงานจากกำหนดการ :name แล้ว',
        ],
        'settings' => [
            'rename' => 'เปลี่ยนชื่อเซิฟเวอร์จาก :old เป็น :new แล้ว',
            'description' => 'เปลี่ยนคำอธิบายเซิฟเวอร์จาก :old เป็น :new แล้ว',
        ],
        'startup' => [
            'edit' => 'เปลี่ยนตัวแปล :variable จาก :old เป็น :new แล้ว',
            'image' => 'อับเดตดอกเกอร์อิมเมจสำหรับเซิฟเวอร์จาก :old เป็น :new แล้ว',
        ],
        'subuser' => [
            'create' => 'เพิ่มอีเมลล์ :email เป็นผู้ใช้ย่อยแล้ว',
            'update' => 'ปรับปรุงสิทธิ์ของผู้ใช้ย่อย :email แล้ว',
            'delete' => 'ลบ :email จากการเป็นผู้ใช้ย่อย',
        ],
    ],
];
