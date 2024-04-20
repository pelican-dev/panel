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
        'password-reset' => 'تم إعادة تعيين كلمة المرور',
        'reset-password' => 'طلب إعادة تعيين كلمة المرور',
        'checkpoint' => 'طلب التحقق ذو العاملين',
        'recovery-token' => 'استخدم رمز الاسترداد ذو العاملين',
        'token' => 'تم حل تحدي ذو العاملين',
        'ip-blocked' => 'تم حظر الطلب من عنوان IP غير مدرج لـ :identifier',
        'sftp' => [
            'fail' => 'فشل تسجيل الدخول عبر SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'تغيير البريد الإلكتروني من :old إلى :new',
            'password-changed' => 'تم تغيير كلمة المرور',
        ],
        'api-key' => [
            'create' => 'تم إنشاء مفتاح API جديد :identifier',
            'delete' => 'تم حذف مفتاح API :identifier',
        ],
        'ssh-key' => [
            'create' => 'تم إضافة مفتاح SSH :fingerprint إلى الحساب',
            'delete' => 'تم إزالة مفتاح SSH :fingerprint من الحساب',
        ],
        'two-factor' => [
            'create' => 'تم تفعيل التحقق ذو العاملين',
            'delete' => 'تم تعطيل التحقق ذو العاملين',
        ],
    ],
    'server' => [
        'reinstall' => 'تم إعادة تثبيت الخادم',
        'console' => [
            'command' => 'تنفيذ الأمر ":command" على الخادم',
        ],
        'power' => [
            'start' => 'تم تشغيل الخادم',
            'stop' => 'تم إيقاف الخادم',
            'restart' => 'تم إعادة تشغيل الخادم',
            'kill' => 'تم إنهاء عملية الخادم',
        ],
        'backup' => [
            'download' => 'تم تنزيل النسخة الاحتياطية :name',
            'delete' => 'تم حذف النسخة الاحتياطية :name',
            'restore' => 'تم استعادة النسخة الاحتياطية :name (تم حذف الملفات: :truncate)',
            'restore-complete' => 'تم إكمال استعادة النسخة الاحتياطية :name',
            'restore-failed' => 'فشل في إكمال استعادة النسخة الاحتياطية :name',
            'start' => 'تم بدء نسخة احتياطية جديدة :name',
            'complete' => 'تم وضع علامة على النسخة الاحتياطية :name كمكتملة',
            'fail' => 'تم وضع علامة على النسخة الاحتياطية :name كفاشلة',
            'lock' => 'تم قفل النسخة الاحتياطية :name',
            'unlock' => 'تم فتح قفل النسخة الاحتياطية :name',
        ],
        'database' => [
            'create' => 'تم إنشاء قاعدة بيانات جديدة :name',
            'rotate-password' => 'تم تغيير كلمة المرور لقاعدة البيانات :name',
            'delete' => 'تم حذف قاعدة البيانات :name',
        ],
        'file' => [
            'compress_one' => 'تم ضغط :directory:file',
            'compress_other' => 'تم ضغط :count ملف في :directory',
            'read' => 'تم عرض محتويات :file',
            'copy' => 'تم إنشاء نسخة من :file',
            'create-directory' => 'تم إنشاء الدليل :directory:name',
            'decompress' => 'تم فك ضغط :files في :directory',
            'delete_one' => 'تم حذف :directory:files.0',
            'delete_other' => 'تم حذف :count ملف في :directory',
            'download' => 'تم تنزيل :file',
            'pull' => 'تم تنزيل ملف من بعد من :url إلى :directory',
            'rename_one' => 'تم تغيير اسم :directory:files.0.from إلى :directory:files.0.to',
            'rename_other' => 'تم تغيير اسم :count ملف في :directory',
            'write' => 'تم كتابة محتوى جديد في :file',
            'upload' => 'بدء تحميل ملف',
            'uploaded' => 'تم رفع :directory:file',
        ],
        'sftp' => [
            'denied' => 'تم حظر الوصول عبر SFTP بسبب الأذونات',
            'create_one' => 'تم إنشاء :files.0',
            'create_other' => 'تم إنشاء :count ملف جديد',
            'write_one' => 'تم تعديل محتويات :files.0',
            'write_other' => 'تم تعديل محتويات :count ملف',
            'delete_one' => 'تم حذف :files.0',
            'delete_other' => 'تم حذف :count ملف',
            'create-directory_one' => 'تم إنشاء دليل :files.0',
            'create-directory_other' => 'تم إنشاء :count مجلد',
            'rename_one' => 'تم تغيير اسم :files.0.from إلى :files.0.to',
            'rename_other' => 'تم تغيير اسم أو نقل :count ملف',
        ],
        'allocation' => [
            'create' => 'تم إضافة :allocation إلى الخادم',
            'notes' => 'تم تحديث الملاحظات لـ :allocation من ":old" إلى ":new"',
            'primary' => 'تم تعيين :allocation كتخصيص أساسي للخادم',
            'delete' => 'تم حذف التخصيص :allocation',
        ],
        'schedule' => [
            'create' => 'تم إنشاء جدول :name',
            'update' => 'تم تحديث جدول :name',
            'execute' => 'تم تنفيذ جدول :name يدويًا',
            'delete' => 'تم حذف جدول :name',
        ],
        'task' => [
            'create' => 'تم إنشاء مهمة ":action" جديدة لجدول :name',
            'update' => 'تم تحديث مهمة ":action" لجدول :name',
            'delete' => 'تم حذف مهمة لجدول :name',
        ],
        'settings' => [
            'rename' => 'تم تغيير اسم الخادم من :old إلى :new',
            'description' => 'تم تغيير وصف الخادم من :old إلى :new',
        ],
        'startup' => [
            'edit' => 'تم تغيير متغير :variable من ":old" إلى ":new"',
            'image' => 'تم تحديث صورة Docker للخادم من :old إلى :new',
        ],
        'subuser' => [
            'create' => 'تم إضافة :email كمستخدم فرعي',
            'update' => 'تم تحديث أذونات المستخدم الفرعي لـ :email',
            'delete' => 'تم إزالة :email كمستخدم فرعي',
        ],
    ],
];
