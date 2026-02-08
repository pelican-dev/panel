<?php

return [
    'user' => [
        'search_users' => 'أدخل اسم المستخدم، معرّف المستخدم، أو عنوان البريد الإلكتروني',
        'select_search_user' => 'معرّف المستخدم الذي سيتم حذفه (أدخل \'0\' لإعادة البحث)',
        'deleted' => 'تم حذف المستخدم بنجاح من اللوحة.',
        'confirm_delete' => 'هل أنت متأكد من أنك تريد حذف هذا المستخدم من اللوحة؟',
        'no_users_found' => 'لم يتم العثور على مستخدمين لمصطلح البحث المقدم.',
        'multiple_found' => 'تم العثور على عدة حسابات للمستخدم المقدم، لا يمكن حذف المستخدم بسبب علامة --no-interaction.',
        'ask_admin' => 'هل هذا المستخدم مدير؟',
        'ask_email' => 'عنوان البريد الإلكتروني',
        'ask_username' => 'اسم المستخدم',
        'ask_password' => 'كلمة المرور',
        'ask_password_tip' => 'إذا كنت ترغب في إنشاء حساب بكلمة مرور عشوائية يتم إرسالها بالبريد الإلكتروني للمستخدم، أعد تشغيل هذا الأمر (CTRL+C) ومرر علامة `--no-password`.',
        'ask_password_help' => 'يجب أن تكون كلمات المرور بطول 8 أحرف على الأقل وتحتوي على حرف كبير ورقم على الأقل.',
        '2fa_help_text' => 'سيؤدي هذا الأمر إلى تعطيل المصادقة ذات عاملين لحساب المستخدم إذا تم تمكينه. يجب استخدام هذا الأمر فقط كأمر باسترداد الحساب إذا كان المستخدم مغلق من حسابه. إذا لم يكن هذا ما تريد فعله، اضغط على CTRL+C للخروج من هذه العملية.',
        '2fa_disabled' => 'تم تعطيل التوثيق الثنائي لـ :email.',
    ],
    'schedule' => [
        'output_line' => 'يتم تنفيذ المهمة الأولى في `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'جاري حذف ملف النسخ الاحتياطي للخدمة :file.',
    ],
    'server' => [
        'rebuild_failed' => 'فشل طلب إعادة بناء ":name" (#:id) على العقدة ":node" مع الخطأ: :message',
        'reinstall' => [
            'failed' => 'فشل طلب إعادة تثبيت ":name" (#:id) على العقدة ":node" مع الخطأ: :message',
            'confirm' => 'أنت على وشك إعادة تثبيت مجموعة من الخوادم. هل ترغب في المتابعة؟',
        ],
        'power' => [
            'confirm' => 'أنت على وشك تنفيذ :action ضد :count خوادم. هل ترغب في المتابعة؟',
            'action_failed' => 'فشل طلب تنفيذ الطاقة لـ ":name" (#:id) على العقدة ":node" مع الخطأ: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'مضيف SMTP (مثل smtp.gmail.com)',
            'ask_smtp_port' => 'منفذ SMTP',
            'ask_smtp_username' => 'اسم مستخدم SMTP',
            'ask_smtp_password' => 'كلمة مرور SMTP',
            'ask_mailgun_domain' => 'نطاق Mailgun',
            'ask_mailgun_endpoint' => 'نقطة نهاية Mailgun',
            'ask_mailgun_secret' => 'سر Mailgun',
            'ask_mandrill_secret' => 'سر Mandrill',
            'ask_postmark_username' => 'مفتاح API Postmark',
            'ask_driver' => 'أي برنامج يجب استخدامه لإرسال الرسائل البريدية؟',
            'ask_mail_from' => 'عنوان البريد الإلكتروني الذي يجب أن تنشأ منه الرسائل',
            'ask_mail_name' => 'الاسم الذي يجب أن تظهر منه الرسائل',
            'ask_encryption' => 'طريقة التشفير المستخدمة',
        ],
    ],
];
