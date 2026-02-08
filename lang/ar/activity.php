<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'فشل تسجيل الدخول',
        'success' => 'تم تسجيل الدخول',
        'password-reset' => 'إعادة تعيين كلمة المرور',
        'checkpoint' => 'تم طلب المصادقة الثنائية',
        'recovery-token' => 'تم استخدام رمز استعادة المصادقة الثنائية',
        'token' => 'تم حل تحدي المصادقة الثنائية',
        'ip-blocked' => 'تم حظر الطلب من عنوان IP غير مدرج لـ <b>:identifier</b>',
        'sftp' => [
            'fail' => 'فشل تسجيل الدخول عبر SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'تم تغيير اسم المستخدم من <b>:old</b> إلى <b>:new</b>',
            'email-changed' => 'تم تغيير البريد الإلكتروني من <b>:old</b> إلى <b>:new</b>',
            'password-changed' => 'تم تغيير كلمة المرور',
        ],
        'api-key' => [
            'create' => 'تم إنشاء مفتاح API جديد <b>:identifier</b>',
            'delete' => 'تم حذف مفتاح API <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'تمت إضافة مفتاح SSH <b>:fingerprint</b> إلى الحساب',
            'delete' => 'تمت إزالة مفتاح SSH <b>:fingerprint</b> من الحساب',
        ],
        'two-factor' => [
            'create' => 'تم تمكين المصادقة الثنائية',
            'delete' => 'تم تعطيل المصادقة الثنائية',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'تم تنفيذ الأمر "<b>:command</b>" على الخادم',
        ],
        'power' => [
            'start' => 'تم تشغيل الخادم',
            'stop' => 'تم إيقاف الخادم',
            'restart' => 'تم إعادة تشغيل الخادم',
            'kill' => 'تم إنهاء عملية الخادم',
        ],
        'backup' => [
            'download' => 'تم تنزيل النسخة الاحتياطية <b>:name</b>',
            'delete' => 'تم حذف النسخة الاحتياطية <b>:name</b>',
            'restore' => 'تمت استعادة النسخة الاحتياطية <b>:name</b> (تم حذف الملفات: <b>:truncate</b>)',
            'restore-complete' => 'تمت استعادة النسخة الاحتياطية <b>:name</b> بنجاح',
            'restore-failed' => 'فشلت استعادة النسخة الاحتياطية <b>:name</b>',
            'start' => 'تم بدء نسخة احتياطية جديدة <b>:name</b>',
            'complete' => 'تم تمييز النسخة الاحتياطية <b>:name</b> كمكتملة',
            'fail' => 'تم تمييز النسخة الاحتياطية <b>:name</b> كفاشلة',
            'lock' => 'تم قفل النسخة الاحتياطية <b>:name</b>',
            'unlock' => 'تم فك قفل النسخة الاحتياطية <b>:name</b>',
            'rename' => 'تم إعادة تسمية النسخة الاحتياطية من "<b>:old_name</b>" إلى "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'تم إنشاء قاعدة بيانات جديدة <b>:name</b>',
            'rotate-password' => 'تم تغيير كلمة مرور قاعدة البيانات <b>:name</b>',
            'delete' => 'تم حذف قاعدة البيانات <b>:name</b>',
        ],
        'file' => [
            'compress' => 'تم ضغط <b>:directory:files</b>|تم ضغط <b>:count</b> ملفات في <b>:directory</b>',
            'read' => 'تم عرض محتوى <b>:file</b>',
            'copy' => 'تم إنشاء نسخة من <b>:file</b>',
            'create-directory' => 'تم إنشاء المجلد <b>:directory:name</b>',
            'decompress' => 'تم فك ضغط <b>:file</b> في <b>:directory</b>',
            'delete' => 'تم حذف <b>:directory:files</b>|تم حذف <b>:count</b> ملفات في <b>:directory</b>',
            'download' => 'تم تنزيل <b>:file</b>',
            'pull' => 'تم تنزيل ملف عن بعد من <b>:url</b> إلى <b>:directory</b>',
            'rename' => 'تم نقل/إعادة تسمية <b>:from</b> إلى <b>:to</b>|تم نقل/إعادة تسمية <b>:count</b> ملفات في <b>:directory</b>',
            'write' => 'تمت كتابة محتوى جديد إلى <b>:file</b>',
            'upload' => 'تم بدء رفع ملف',
            'uploaded' => 'تم رفع <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'تم حظر الوصول إلى SFTP بسبب الأذونات',
            'create' => 'تم إنشاء <b>:files</b>|تم إنشاء <b>:count</b> ملفات جديدة',
            'write' => 'تم تعديل محتوى <b>:files</b>|تم تعديل محتوى <b>:count</b> ملفات',
            'delete' => 'تم حذف <b>:files</b>|تم حذف <b>:count</b> ملفات',
            'create-directory' => 'تم إنشاء المجلد <b>:files</b>|تم إنشاء <b>:count</b> مجلدات',
            'rename' => 'تمت إعادة تسمية <b>:from</b> إلى <b>:to</b>|تمت إعادة تسمية أو نقل <b>:count</b> ملفات',
        ],
        'allocation' => [
            'create' => 'تمت إضافة <b>:allocation</b> إلى الخادم',
            'notes' => 'تم تحديث الملاحظات لـ <b>:allocation</b> من "<b>:old</b>" إلى "<b>:new</b>"',
            'primary' => 'تم تعيين <b>:allocation</b> كالتخصيص الأساسي للخادم',
            'delete' => 'تم حذف التخصيص <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'تم إنشاء الجدولة <b>:name</b>',
            'update' => 'تم تحديث الجدولة <b>:name</b>',
            'execute' => 'تم تنفيذ الجدولة <b>:name</b> يدويًا',
            'delete' => 'تم حذف الجدولة <b>:name</b>',
        ],
        'task' => [
            'create' => 'تم إنشاء مهمة جديدة "<b>:action</b>" لجدولة <b>:name</b>',
            'update' => 'تم تحديث المهمة "<b>:action</b>" لجدولة <b>:name</b>',
            'delete' => 'تم حذف "<b>:action</b>" لمهمة الجدول <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'تمت إعادة تسمية الخادم من "<b>:old</b>" إلى "<b>:new</b>"',
            'description' => 'تم تغيير وصف الخادم من "<b>:old</b>" إلى "<b>:new</b>"',
            'reinstall' => 'تم إعادة تثبيت الخادم',
        ],
        'startup' => [
            'edit' => 'تم تغيير المتغير <b>:variable</b> من "<b>:old</b>" إلى "<b>:new</b>"',
            'image' => 'تم تحديث صورة Docker للخادم من <b>:old</b> إلى <b>:new</b>',
            'command' => 'تم تحديث أمر بدء التشغيل للخادم من <b>:old</b> إلى <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'تمت إضافة <b>:email</b> كمستخدم فرعي',
            'update' => 'تم تحديث أذونات المستخدم الفرعي <b>:email</b>',
            'delete' => 'تمت إزالة <b>:email</b> كمستخدم فرعي',
        ],
        'crashed' => 'تعطل الخادم',
    ],
];
