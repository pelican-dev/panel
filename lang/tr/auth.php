<?php

return [
    'return_to_login' => 'Girişe Geri Dön',
    'failed' => 'Bu kimlik bilgileriyle eşleşen hesap bulunamadı.',

    'login' => [
        'title' => 'Devam etmek için Giriş yapın',
        'button' => 'Giriş yap',
        'required' => [
            'username_or_email' => 'Bir kullanıcı adı ve ya e-posta belirtilmeli.',
            'password' => 'Lütfen hesap şifrenizi girin.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Şi̇fre Sıfırlama isteği gönder',
        'label' => 'Şifremi Unuttum',
        'label_help' => 'Şifrenizi sıfırlama talimatlarını almak için e-posta adresinizi giriniz.',
        'button' => 'E-posta gönder',
        'required' => [
            'email' => 'Devam etmek için geçerli bir e-posta adresi belirtilmeli.',
        ],
    ],

    'reset_password' => [
        'title' => 'Şifreyi Sıfırla',
        'button' => 'Şifreyi Sıfırla',
        'new_password' => 'Yeni Şifre',
        'confirm_new_password' => 'Yeni Şifreyi Onayla',
        'requirement' => [
            'password' => 'Şifre en az 8 karakter uzunluğunda olmalı.',
        ],
        'required' => [
            'password' => 'Yeni bir şifre gerekli.',
            'password_confirmation' => 'Yeni şifreniz eşleşmiyor.',
        ],
        'validation' => [
            'password' => 'Yeni şifreniz en az 8 karakter uzunluğunda olmalı.',
            'password_confirmation' => 'Yeni şifreniz eşleşmiyor.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Cihaz Kontrol Noktası',
        'recovery_code' => 'Kurtarma Kodu',
        'recovery_code_description' => 'Devam etmek için 2 Aşamalı Doğrulama\'yı etkinleştirirken aldığınız kurtarma kodlarından birini girin.',
        'authentication_code' => 'Doğrulama Kodu',
        'authentication_code_description' => 'Cihazınız tarafından oluşturulan 2 Aşamalı Doğrulama kodunu girin.',
        'button' => 'Devam et',
        'lost_device' => 'Cihazımı Kaybettim',
        'have_device' => 'Cihazıma Sahibim',
    ],

    'two_factor' => [
        'label' => 'İki Faktörlü Doğrulama Tokeni',
        'label_help' => 'Bu hesabın devam edebilmesi için ikinci bir kimlik doğrulama katmanı gerekiyor. Bu girişi tamamlamak için lütfen cihazınız tarafından oluşturulan kodu girin.',
        'checkpoint_failed' => 'İki faktörlü kimlik doğrulama jetonu geçersiz.',
    ],

    'throttle' => 'Çok fazla hatalı giriş yaptınız. Lütfen :seconds saniye sonra tekrar deneyiniz.',
    'password_requirements' => 'Şifre en az 8 karakter uzunluğunda olmalı.',
    '2fa_must_be_enabled' => 'Yönetici, Paneli kullanabilmeniz için hesabınızda 2 Faktörlü Kimlik Doğrulamanın etkinleştirilmesini zorunlu kılmıştır.',
];
