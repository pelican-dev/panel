<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Giriş yapılamadı',
        'success' => 'Giriş yapıldı',
        'password-reset' => 'Şifre sıfırlama',
        'reset-password' => 'Şifre sıfırlama istendi',
        'checkpoint' => 'İki faktörlü kimlik doğrulama istendi',
        'recovery-token' => 'İki faktörlü kurtarma tokeni kullanıldı',
        'token' => 'İki faktörlü doğrulama çözüldü',
        'ip-blocked' => ':identifier olarak listelenmemiş IP adresinden gelen istek engellendi',
        'sftp' => [
            'fail' => 'SFTP girişi yapılamadı',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Email :old yerine :new ile değiştirildi',
            'password-changed' => 'Şifre değiştirildi',
        ],
        'api-key' => [
            'create' => 'API anahtarı :fingerprint hesaba eklendi',
            'delete' => 'API anahtarı :identifier kaldırıldı',
        ],
        'ssh-key' => [
            'create' => 'SSH anahtarı :fingerprint hesaba eklendi',
            'delete' => 'SSH anahtarı :fingerprint kaldırıldı',
        ],
        'two-factor' => [
            'create' => 'İki Adımlı Doğrulama etkinleştirildi',
            'delete' => 'İki Adımlı Doğrulama devre dışı bırakıldı',
        ],
    ],
    'server' => [
        'reinstall' => 'Sunucu yeniden kuruldu',
        'console' => [
            'command' => 'Sunucuda :command komutu çalıştırıldı',
        ],
        'power' => [
            'start' => 'Sunucu başlatıldı',
            'stop' => 'Sunucu durduruldu',
            'restart' => 'Sunucu yeniden başlatıldı',
            'kill' => 'Sunucu zorla kapatıldı',
        ],
        'backup' => [
            'download' => 'Yedek :name indirildi',
            'delete' => 'Yedek :name silindi',
            'restore' => 'Yedek :name yüklendi (silinen dosyalar: :truncate)',
            'restore-complete' => ':name adlı yedeğin yüklenmesi sona erdi',
            'restore-failed' => ':name adlı yedeğin yüklenmesi başarısız oldu',
            'start' => 'Yeni yedek :name başlatıldı',
            'complete' => ':name yedeği başarılı olarak kaydedildi',
            'fail' => ':name yedeklemesi başarısız olarak işaretlendi',
            'lock' => ':name yedeği kilitlendi',
            'unlock' => ':name yedeklemesinin kilidi açıldı',
        ],
        'database' => [
            'create' => ':name veritabanı oluşturuldu',
            'rotate-password' => ':name veritabanı için şifre değiştirildi',
            'delete' => ':name veritabanı silindi',
        ],
        'file' => [
            'compress_one' => ':directory:file sıkıştırıldı',
            'compress_other' => ':directory \'deki :count dosya sıkıştırıldı',
            'read' => ':files. dosyasının içeriği gösterildi',
            'copy' => ':file belgenin kopyası oluşturuldu',
            'create-directory' => ':directory:name klasör oluşturuldu.',
            'decompress' => ':files dosyası :directory içinde çıkartıldı',
            'delete_one' => ':directory:files.0 silindi',
            'delete_other' => ':directory klasöründe :count belge silindi',
            'download' => ':file indirildi',
            'pull' => ':directory klasörüne :url bağlantısından dosya indirildi',
            'rename_one' => ':directory:files.0.from :directory:files.0.to olarak yeniden adlandırıldı',
            'rename_other' => ':directory klasöründe :count dosyanın adı değiştirildi',
            'write' => ':file dosyasına yeni içerik eklendi',
            'upload' => 'Dosya yüklemesi başlatıldı',
            'uploaded' => ':directory:file yüklendi',
        ],
        'sftp' => [
            'denied' => 'SFTP erişimi izinler yüzünden engellendi',
            'create_one' => ':files.0 oluşturuldu',
            'create_other' => ':count belge oluşturuldu',
            'write_one' => ':files.0 dosyasının içeriği değiştirildi',
            'write_other' => ':count dosyanın içeriği değiştirildi',
            'delete_one' => ':files.0 silindi',
            'delete_other' => ':count dosya silindi',
            'create-directory_one' => ':files.0 klasörü oluşturuldu',
            'create-directory_other' => ':count klasör oluşturuldu',
            'rename_one' => ':directory:files.0.from :directory:files.0.to olarak yeniden adlandırıldı',
            'rename_other' => ':count belge yeniden isimlendirildi veya taşındı',
        ],
        'allocation' => [
            'create' => ':allocation lokasyonuna sunucu eklendi',
            'notes' => ':allocation notları ":old" yerine ":new" olarak güncellendi',
            'primary' => ':allocation birincil sunucu lokasyonu seçildi',
            'delete' => ':allocation lokasyonu silindi',
        ],
        'schedule' => [
            'create' => ':name programı oluşturuldu',
            'update' => ':name programı güncellendi',
            'execute' => ':name zamanlaması manuel olarak yürütüldü',
            'delete' => ':name programı silindi',
        ],
        'task' => [
            'create' => ':name planı için yeni bir ":action" görevi oluşturuldu',
            'update' => ':name zamanlaması için ":action" görevi güncellendi',
            'delete' => ':name planı için bir görev silindi',
        ],
        'settings' => [
            'rename' => 'Sunucunun adı :old\'den :new olarak değiştirildi',
            'description' => 'Sunucu açıklamasını :old yerine :new olarak değiştirdik',
        ],
        'startup' => [
            'edit' => ':variable değişkeni ":old" yerine ":new" olarak değiştirildi',
            'image' => 'Sunucunun Docker Görüntüsü :old\'den :new\'ye güncellendi',
        ],
        'subuser' => [
            'create' => 'Alt kullanıcı olarak :email eklendi',
            'update' => ':email için alt kullanıcı izinleri güncellendi',
            'delete' => ':email kullanıcısı silindi',
        ],
    ],
];
