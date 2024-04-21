<?php

return [
    'email' => [
        'title' => 'E-Postanı güncelle',
        'updated' => 'E-Posta adresin güncellendi.',
    ],
    'password' => [
        'title' => 'Parolanı değiştir.',
        'requirements' => 'Yeni parolan 8 karakterden az olamaz.',
        'updated' => 'Parolan güncellendi.',
    ],
    'two_factor' => [
        'button' => 'İki faktörlü doğrulamayı yapılandır.',
        'disabled' => 'Hesabınızda iki faktörlü kimlik doğrulama devre dışı bırakıldı. Artık oturum açarken bir jeton sağlamanız istenmeyecek.',
        'enabled' => 'Hesabınızda iki faktörlü kimlik doğrulama etkinleştirildi! Artık giriş yaparken cihazınız tarafından oluşturulan kodu girmeniz istenecektir.',
        'invalid' => 'Sağlanan token geçersiz.',
        'setup' => [
            'title' => 'İki faktörlü doğrulamayı kur',
            'help' => 'Kodu tarayamıyor musunuz? Aşağıdaki kodu :application girin',
            'field' => 'Token gir',
        ],
        'disable' => [
            'title' => 'İki faktörlü kimlik doğrulamayı devre dışı bırak',
            'field' => 'Token gir',
        ],
    ],
];
