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
        'checkpoint' => '2 aşamalı doğrulama talep edildi',
        'recovery-token' => '2 aşamalı doğrulama',
        'token' => '2 aşamalı doğrulama',
        'ip-blocked' => ':identifier olarak listelenmiş IP adresinden gelen istek engellendi',
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
            'create' => '2 aşamalı doğrulama etkinleştirildi',
            'delete' => '2 aşamalı doğrulama devre dışı bırakıldı',
        ],
    ],
    'server' => [
        'reinstall' => 'Sunucu yeniden indirildi',
        'console' => [
            'command' => 'Sunucuda :command komutu kullanıldı',
        ],
        'power' => [
            'start' => 'Sunucu başlatıldı',
            'stop' => 'Sunucu durduruldu',
            'restart' => 'Sunucu yeniden başlatıldı',
            'kill' => 'Sunucu işlemi',
        ],
        'backup' => [
            'download' => 'Yedek :name indirildi',
            'delete' => 'Yedek :name silindi',
            'restore' => 'Yedek :name yüklendi (silinen dosyalar: :truncate)',
            'restore-complete' => ':name adlı yedeğin yüklenmesi sona erdi',
            'restore-failed' => ':name adlı yedeğin yüklenmesi başarısız oldu',
            'start' => 'Yeni yedek :name başlatıldı',
            'complete' => ':name yedeği başarılı olarak kaydedildi',
            'fail' => 'Marked the :name backup as failed',
            'lock' => 'Locked the :name backup',
            'unlock' => 'Unlocked the :name backup',
        ],
        'database' => [
            'create' => 'Created new database :name',
            'rotate-password' => 'Password rotated for database :name',
            'delete' => 'Deleted database :name',
        ],
        'file' => [
            'compress_one' => 'Compressed :directory:file',
            'compress_other' => 'Compressed :count files in :directory',
            'read' => 'Viewed the contents of :file',
            'copy' => 'Created a copy of :file',
            'create-directory' => 'Created directory :directory:name',
            'decompress' => 'Decompressed :files in :directory',
            'delete_one' => 'Deleted :directory:files.0',
            'delete_other' => 'Deleted :count files in :directory',
            'download' => 'Downloaded :file',
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
            'create_other' => 'Created :count new files',
            'write_one' => ':files.0 dosyasının içeriği değiştirildi',
            'write_other' => ':count dosyanın içeriği değiştirildi',
            'delete_one' => ':files.0 silindi',
            'delete_other' => ':count dosya silindi',
            'create-directory_one' => ':files.0 klasörü oluşturuldu',
            'create-directory_other' => ':count klasör oluşturuldu',
            'rename_one' => ':directory:files.0.from :directory:files.0.to olarak yeniden adlandırıldı',
            'rename_other' => 'Renamed or moved :count files',
        ],
        'allocation' => [
            'create' => ':allocation lokasyonuna sunucu eklendi',
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
