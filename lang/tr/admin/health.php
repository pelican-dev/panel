<?php

return [
    'title' => 'Sağlık',
    'results_refreshed' => 'Sağlık raporları güncellendi.',
    'checked' => ':time tarihinden itibaren sonuçlar kontrol edildi',
    'refresh' => 'Yenile',
    'results' => [
        'cache' => [
            'label' => 'Önbellek',
            'ok' => 'Tamam',
            'failed_retrieve' => 'Uygulama önbellek değeri ayarlanamadı veya alınamadı.',
            'failed' => 'Uygulama önbelleği ile ilgili bir istisna oluştu: :error',
        ],
        'database' => [
            'label' => 'Veritabanı',
            'ok' => 'Tamam',
            'failed' => 'Veritabanına bağlanılamadı: :error',
        ],
        'debugmode' => [
            'label' => 'Hata Ayıklama Modu',
            'ok' => 'Hata ayıklama modu devre dışı',
            'failed' => 'Hata ayıklama modunun :expected olması bekleniyordu, ancak :actual olarak ayarlı',
        ],
        'environment' => [
            'label' => 'Ortam',
            'ok' => 'Tamam, :actual olarak ayarlı',
            'failed' => 'Ortam :actual olarak ayarlı, Beklenen: :expected',
        ],
        'nodeversions' => [
            'label' => 'Node Sürümleri',
            'ok' => 'Node\'lar güncel',
            'failed' => ':outdated/:all Node güncel değil',
            'no_nodes_created' => 'Node oluşturulmadı',
            'no_nodes' => 'Node yok',
            'all_up_to_date' => 'Tümü güncel',
            'outdated' => ':outdated/:all güncel değil',
        ],
        'panelversion' => [
            'label' => 'Panel Sürümü',
            'ok' => 'Panel güncel',
            'failed' => 'Yüklü sürüm: :currentVersion, ancak en son sürüm: :latestVersion',
            'up_to_date' => 'Güncel',
            'outdated' => 'Güncel Değil',
        ],
        'schedule' => [
            'label' => 'Zamanlama',
            'ok' => 'Tamam',
            'failed_last_ran' => 'Zamanlamanın son çalıştırılması :time dakikadan daha önce gerçekleşti',
            'failed_not_ran' => 'Zamanlama henüz çalıştırılmadı.',
        ],
        'useddiskspace' => [
            'label' => 'Disk Alanı',
        ],
    ],
    'checks' => [
        'successful' => 'Başarılı',
        'failed' => 'Başarısız',
    ],
];
