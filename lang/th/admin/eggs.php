<?php

return [
    'notices' => [
        'imported' => 'นำเข้าไข่นี้และตัวแปรที่เกี่ยวข้องเรียบร้อยแล้ว',
        'updated_via_import' => 'ไข่นี้ได้รับการอัปเดตโดยใช้ไฟล์ที่นำเข้ามา',
        'deleted' => 'ลบไข่ออกจากแผงควบคุมเรียบร้อยแล้ว',
        'updated' => 'อัปเดตการตั้งค่าไข่สำเร็จแล้ว',
        'script_updated' => 'สคริปต์การติดตั้งไข่ได้รับการอัปเดตแล้ว และจะทำงานทุกครั้งที่ติดตั้งเซิร์ฟเวอร์',
        'egg_created' => 'วางไข่ใหม่เรียบร้อยแล้ว คุณจะต้องรีสตาร์ทดีมอนที่กำลังวิ่งอยู่เพื่อใช้ไข่ใหม่นี้',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'ตัวแปร ":variable" ถูกลบแล้ว และจะไม่สามารถใช้ได้อีกต่อไปสำหรับเซิร์ฟเวอร์เมื่อสร้างใหม่แล้ว',
            'variable_updated' => 'อัปเดตตัวแปร ":variable" แล้ว คุณจะต้องสร้างเซิร์ฟเวอร์ใหม่โดยใช้ตัวแปรนี้เพื่อใช้การเปลี่ยนแปลง',
            'variable_created' => 'สร้างตัวแปรใหม่และกำหนดให้กับไข่นี้สำเร็จแล้ว',
        ],
    ],
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
