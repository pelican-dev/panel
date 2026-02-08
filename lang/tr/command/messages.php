<?php

return [
    'user' => [
        'search_users' => 'Lütfen Kullanıcı Adı, Kullancı ID veya E-posta girin',
        'select_search_user' => 'Silinecek kullanıcının ID\'si (Yeniden aramak için \'0\' girin)',
        'deleted' => 'Kullanıcı başarılı şekilde Panelden silindi.',
        'confirm_delete' => 'Bu kullanıcıyı Panelden silmek istediğinizden emin misiniz?',
        'no_users_found' => 'Arama kayıtlarına göre kullanıcı bulunamadı.',
        'multiple_found' => 'Belirtilen kullanıcı için birden fazla hesap bulundu; --no-interaction işareti nedeniyle bir kullanıcı silinemedi.',
        'ask_admin' => 'Kullanıcı yönetici olarak mı eklensin?',
        'ask_email' => 'E-posta Adresi',
        'ask_username' => 'Kullanıcı Adı',
        'ask_password' => 'Parola',
        'ask_password_tip' => 'Kullanıcıya e-postayla gönderilen rastgele bir parolayla bir hesap oluşturmak istiyorsanız, bu komutu (CTRL+C) yeniden çalıştırın ve "--no-password" işaretini iletin.',
        'ask_password_help' => 'Şifreler en az 8 karakter uzunluğunda olmalı ve en az bir büyük harf ve rakam içermelidir.',
        '2fa_help_text' => 'Bu komut, etkin ise kullanıcının hesabındaki 2 faktörlü kimlik doğrulamayı devre dışı bırakır. Bu işlem, yalnızca kullanıcı hesabına erişemiyorsa bir hesap kurtarma yöntemi olarak kullanılmalıdır. Yapmak istediğiniz işlem bu değilse, çıkmak için CTRL+C tuşlarına basın.',
        '2fa_disabled' => ':email kullanıcısına ait iki adımlı doğrulama devredışı bırakıldı.',
    ],
    'schedule' => [
        'output_line' => '`:schedule` (:id) içindeki ilk görev için iş gönderiliyor.',
    ],
    'maintenance' => [
        'deleting_service_backup' => ':file adlı servis yedeği silindi.',
    ],
    'server' => [
        'rebuild_failed' => '":node" düğümünde ":name" (#:id) için yeniden oluşturma isteği şu hatayla başarısız oldu: :message',
        'reinstall' => [
            'failed' => '":name" (#:id) için ":node" düğümüne yeniden yükleme isteği şu hata ile başarısız oldu: :message',
            'confirm' => 'Bir grup sunucuya yeniden kurulum yapmak üzeresiniz. Devam etmek istiyor musunuz?',
        ],
        'power' => [
            'confirm' => ':count sunucularına karşı bir :action gerçekleştirmek üzeresiniz. Devam etmek ister misiniz?',
            'action_failed' => '":node" düğümündeki ":name" (#:id) için güç eylemi isteği şu hata ile başarısız oldu: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Sağlayıcı (örn. smtp.google.com)',
            'ask_smtp_port' => 'SMTP Portu',
            'ask_smtp_username' => 'SMTP Kullanıcı Adı',
            'ask_smtp_password' => 'SMTP Parolası',
            'ask_mailgun_domain' => 'Mailgun Sunucusu',
            'ask_mailgun_endpoint' => 'Mailgun Uçnoktası',
            'ask_mailgun_secret' => 'Mailgun Gizli Anahtarı',
            'ask_mandrill_secret' => 'Mandrill Gizli Anahtar',
            'ask_postmark_username' => 'Postmark API Anahtarı',
            'ask_driver' => 'Hangi servis ile E-Posta gönderilsin?',
            'ask_mail_from' => 'E-posta adresi e-postaları şu kaynaktan gelmelidir:',
            'ask_mail_name' => 'E-postalarda görünecek ad',
            'ask_encryption' => 'Kullanılacak şifreleme yöntemi',
        ],
    ],
];
