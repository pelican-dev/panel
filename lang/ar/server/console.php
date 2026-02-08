<?php

return [
    'title' => 'وحدة التحكم',
    'command' => 'اكتب أمر...',
    'command_blocked' => 'الخادم غير متصل...',
    'command_blocked_title' => 'لا يمكن إرسال أمر عندما يكون الخادم غير متصل',
    'open_in_admin' => 'فتح في لوحة المسؤول',
    'power_actions' => [
        'start' => 'تشغيل',
        'stop' => 'إيقاف',
        'restart' => 'إعادة تشغيل',
        'kill' => 'قتل',
        'kill_tooltip' => 'يمكن أن يؤدي هذا إلى فساد البيانات و/أو فقدان البيانات!',
    ],
    'labels' => [
        'cpu' => 'المعالج',
        'memory' => 'الذاكرة',
        'network' => 'الشبكة',
        'disk' => 'القرص',
        'name' => 'الاسم',
        'status' => 'الحالة',
        'address' => 'العنوان',
        'unavailable' => 'غير متاح',
    ],
    'status' => [
        'created' => 'تم الإنشاء',
        'starting' => 'جار التشغيل',
        'running' => 'قيد التشغيل',
        'restarting' => 'يتم إعادة التشغيل',
        'exited' => 'تم الخروج',
        'paused' => 'متوقف مؤقتاً',
        'dead' => 'ميت',
        'removing' => 'جار الإزالة',
        'stopping' => 'جار الإيقاف',
        'offline' => 'غير مُتصل',
        'missing' => 'مفقود',
    ],
    'websocket_error' => [
        'title' => 'تعذر الاتصال ب websocket!',
        'body' => 'تحقق من وحدة التحكم في المتصفح الخاص بك للحصول على مزيد من التفاصيل.',
    ],
];
