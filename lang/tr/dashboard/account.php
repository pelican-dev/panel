<?php

return [
    'title' => 'Hesaba Genel Bakış',
    'email' => [
        'title' => 'E-posta Adresini Güncelle',
        'button' => 'E-posta Güncelle',
        'updated' => 'Birincil e-posta adresiniz güncellenmiştir.',
    ],
    'password' => [
        'title' => 'Şifreyi Güncelle',
        'button' => 'Şifreyi Güncelle',
        'requirements' => 'Yeni şifreniz en az 8 karakter uzunluğunda ve bu siteye özel olmalıdır.',
        'validation' => [
            'account_password' => 'Hesap şifrenizi girmeniz gerekiyor.',
            'current_password' => 'Güncel şifrenizi girmeniz gerekiyor.',
            'password_confirmation' => 'Şifre doğrulama girdiğiniz şifre ile eşleşmiyor.',
        ],
        'updated' => 'Parolan güncellendi.',
    ],
    'two_factor' => [
        'title' => 'İki Aşamalı Doğrulama',
        'button' => 'İki faktörlü doğrulamayı yapılandır.',
        'disabled' => 'Hesabınızda iki faktörlü kimlik doğrulama devre dışı bırakıldı. Artık oturum açarken bir jeton sağlamanız istenmeyecek.',
        'enabled' => 'Hesabınızda iki faktörlü kimlik doğrulama etkinleştirildi! Artık giriş yaparken cihazınız tarafından oluşturulan kodu girmeniz istenecektir.',
        'invalid' => 'Sağlanan token geçersiz.',
        'enable' => [
            'help' => 'Şu anda hesabınızda 2 aşamalı doğrulama etkin değil. Ayarlamaya başlamak için aşağıdaki butona tıklayın.',
            'button' => '2 Aşamayı Etkinleştir',
        ],
        'disable' => [
            'help' => '2 aşamalı doğrulama şu anda hesabınızda aktif.',
            'title' => 'İki faktörlü kimlik doğrulamayı devre dışı bırak',
            'field' => 'Token gir',
            'button' => '2 Aşamayı Kapat',
        ],
        'setup' => [
            'title' => '2 Aşamalı Doğrulamayı Etkinleştir',
            'subtitle' => 'Hesabınızı izinsiz erişimlerden koruyun. Her giriş yaptığında bir doğrulama kodu istenecek.',
            'help' => 'Yukarıdaki QR kodu herhangi bir kimlik doğrulama uygulaması ile okutun. Ardından, oluşturulan 6 haneli rakamı aşağıdaki alana girin.',
        ],

        'required' => [
            'title' => '2 Aşamalı Doğrulama Zorunlu',
            'description' => 'Devam etmek için hesabınızda 2 aşamalı doğrulamanın etkin olması gerekiyor.',
        ],
    ],
];
