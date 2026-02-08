<?php

return [
    'title' => 'الصحة',
    'results_refreshed' => 'تم تحديث نتائج فحص الصحة',
    'checked' => 'تم التحقق من النتائج منذ :time',
    'refresh' => 'تحديث',
    'results' => [
        'cache' => [
            'label' => 'التخزين المؤقت',
            'ok' => 'موافق',
            'failed_retrieve' => 'تعذر تعيين أو استرجاع قيمة التخزين المؤقت للتطبيق.',
            'failed' => 'حدث استثناء في التخزين المؤقت للتطبيق: :error',
        ],
        'database' => [
            'label' => 'قاعدة البيانات',
            'ok' => 'موافق',
            'failed' => 'تعذر الاتصال بقاعدة البيانات: :error',
        ],
        'debugmode' => [
            'label' => 'وضع التصحيح',
            'ok' => 'وضع التصحيح معطل',
            'failed' => 'كان من المتوقع أن يكون وضع التصحيح :expected، لكنه كان :actual',
        ],
        'environment' => [
            'label' => 'البيئة',
            'ok' => 'موافق، تم التعيين إلى :actual',
            'failed' => 'تم تعيين البيئة إلى :actual، بينما كان المتوقع :expected',
        ],
        'nodeversions' => [
            'label' => 'إصدارات العقد',
            'ok' => 'العقد محدثة',
            'failed' => ':outdated/:all من العقد قديمة',
            'no_nodes_created' => 'لم يتم إنشاء أي عقد',
            'no_nodes' => 'لا توجد عقد',
            'all_up_to_date' => 'جميعها محدثة',
            'outdated' => ':outdated/:all قديمة',
        ],
        'panelversion' => [
            'label' => 'إصدار اللوحة',
            'ok' => 'اللوحة محدثة',
            'failed' => 'الإصدار المثبت هو :currentVersion بينما الأحدث هو :latestVersion',
            'up_to_date' => 'محدث',
            'outdated' => 'قديم',
        ],
        'schedule' => [
            'label' => 'الجدولة',
            'ok' => 'موافق',
            'failed_last_ran' => 'آخر تشغيل للجدولة كان قبل أكثر من :time دقيقة',
            'failed_not_ran' => 'لم يتم تشغيل الجدولة بعد.',
        ],
        'useddiskspace' => [
            'label' => 'مساحة القرص',
        ],
    ],
    'checks' => [
        'successful' => 'ناجح',
        'failed' => 'فشل',
    ],
];
