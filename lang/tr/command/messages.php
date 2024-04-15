<?php

return [
    'user' => [
        'search_users' => 'Lütfen Kullanıcı Adı, Kullancı ID veya E-posta girin',
        'select_search_user' => 'Silinecek kullanıcının ID\'si (Yeniden aramak için \'0\' girin',
        'deleted' => 'Kullanıcı başarılı şekilde Panelden silindi.',
        'confirm_delete' => 'Bu kullanıcıyı Panelden silmek istediğinizden emin misiniz?',
        'no_users_found' => 'Arama kayıtlarına göre kullanıcı bulunamadı.',
        'multiple_found' => 'Bulunan kullanıcı için birden fazla hesap bulundu; --no-interaction işareti nedeniyle bir kullanıcı silinemedi.',
        'ask_admin' => 'Kullanıcı yönetici olarak mı eklensin?',
        'ask_email' => 'E-Posta',
        'ask_username' => 'Kullanıcı Adı',
        'ask_name_first' => 'Adı',
        'ask_name_last' => 'Soyadı',
        'ask_password' => 'Parola',
        'ask_password_tip' => 'If you would like to create an account with a random password emailed to the user, re-run this command (CTRL+C) and pass the `--no-password` flag.',
        'ask_password_help' => 'Passwords must be at least 8 characters in length and contain at least one capital letter and number.',
        '2fa_help_text' => [
            'This command will disable 2-factor authentication for a user\'s account if it is enabled. This should only be used as an account recovery command if the user is locked out of their account.',
            'If this is not what you wanted to do, press CTRL+C to exit this process.',
        ],
        '2fa_disabled' => '2-Factor authentication has been disabled for :email.',
    ],
    'schedule' => [
        'output_line' => 'Dispatching job for first task in `:schedule` (:hash).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Deleting service backup file :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Rebuild request for ":name" (#:id) on node ":node" failed with error: :message',
        'reinstall' => [
            'failed' => 'Reinstall request for ":name" (#:id) on node ":node" failed with error: :message',
            'confirm' => 'You are about to reinstall against a group of servers. Do you wish to continue?',
        ],
        'power' => [
            'confirm' => 'You are about to perform a :action against :count servers. Do you wish to continue?',
            'action_failed' => 'Power action request for ":name" (#:id) on node ":node" failed with error: :message',
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
            'ask_encryption' => 'Encryption method to use',
        ],
    ],
];
