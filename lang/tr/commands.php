<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Bu Panel tarafından dışa aktarılan yumurtaların (eggs) hangi e-posta adresinden gönderileceğini belirtin. Bu, geçerli bir e-posta adresi olmalıdır.',
            'url' => 'Uygulama URL\'si, SSL kullanıp kullanmadığınıza bağlı olarak https:// veya http:// ile başlamalıdır. Şema eklemezseniz, e-postalarınız ve diğer içerikler yanlış konuma bağlanacaktır.',
            'timezone' => "Saat dilimi, PHP'nin desteklediği saat dilimlerinden biriyle eşleşmelidir. Emin değilseniz, lütfen https://php.net/manual/en/timezones.php adresine bakın.",
        ],
        'redis' => [
            'note' => 'Bir veya daha fazla seçenek için Redis sürücüsünü seçtiniz, lütfen aşağıya geçerli bağlantı bilgilerini girin. Çoğu durumda, kurulumunuzu değiştirmediyseniz sağlanan varsayılanları kullanabilirsiniz.',
            'comment' => 'Varsayılan olarak bir Redis sunucusu örneği, kullanıcı adı olarak "default" kullanır ve yerel olarak çalıştığı ve dış dünyaya erişilemediği için şifre gerektirmez. Bu durumda, bir değer girmeden Enter tuşuna basmanız yeterlidir.',
            'confirm' => 'Redis için bir :field zaten tanımlanmış gibi görünüyor, değiştirmek ister misiniz?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Veritabanı ana bilgisayarı olarak "localhost" kullanmanız şiddetle tavsiye edilmez çünkü sık sık soket bağlantı sorunları yaşanmaktadır. Yerel bir bağlantı kullanmak istiyorsanız "127.0.0.1" kullanmalısınız.',
        'DB_USERNAME_note' => 'MySQL bağlantıları için root hesabı kullanmak sadece tavsiye edilmez, aynı zamanda bu uygulama tarafından da izin verilmez. Bu yazılım için bir MySQL kullanıcısı oluşturmuş olmanız gerekiyor.',
        'DB_PASSWORD_note' => 'Görünüşe göre zaten bir MySQL bağlantı şifreniz tanımlanmış, değiştirmek ister misiniz?',
        'DB_error_2' => 'Bağlantı bilgileriniz KAYDEDİLMEDİ. Devam etmeden önce geçerli bağlantı bilgileri sağlamanız gerekecek.',
        'go_back' => 'Geri dön ve tekrar dene',
    ],
    'make_node' => [
        'name' => 'Bu düğümü diğerlerinden ayırt etmek için kısa bir tanımlayıcı girin',
        'description' => 'Düğümü tanımlamak için bir açıklama girin',
        'scheme' => 'Lütfen SSL için https veya SSL olmayan bağlantılar için http girin',
        'fqdn' => 'Daemona bağlanmak için kullanılacak bir alan adı girin (örneğin node.example.com). Bu düğüm için SSL kullanmıyorsanız yalnızca bir IP adresi kullanabilirsiniz.',
        'public' => 'Bu düğüm herkese açık olmalı mı? Bir düğümü özel olarak ayarlamak, bu düğüme otomatik dağıtım yapma yeteneğini reddedecektir.',
        'behind_proxy' => 'FQDN\'niz bir proxy arkasında mı?',
        'maintenance_mode' => 'Bakım modu etkinleştirilsin mi?',
        'memory' => 'Maksimum bellek miktarını girin',
        'memory_overallocate' => 'Fazla tahsis edilecek bellek miktarını girin, -1 kontrolü devre dışı bırakır ve 0 yeni sunucu oluşturmayı engeller',
        'disk' => 'Maksimum disk alanı miktarını girin',
        'disk_overallocate' => 'Fazla tahsis edilecek disk miktarını girin, -1 kontrolü devre dışı bırakır ve 0 yeni sunucu oluşturmayı engeller',
        'cpu' => 'Maksimum CPU miktarını girin',
        'cpu_overallocate' => 'Fazla tahsis edilecek CPU miktarını girin, -1 kontrolü devre dışı bırakır ve 0 yeni sunucu oluşturmayı engeller',
        'upload_size' => 'Maksimum dosya yükleme boyutunu girin',
        'daemonListen' => 'Daemon dinleme portunu girin',
        'daemonSFTP' => 'Daemon SFTP dinleme portunu girin',
        'daemonSFTPAlias' => 'Daemon SFTP takma adını girin (boş bırakılabilir)',
        'daemonBase' => 'Temel klasörü girin',
        'success' => ':name adında yeni bir düğüm başarıyla oluşturuldu ve kimliği :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Seçilen düğüm mevcut değil.',
        'error_invalid_format' => 'Geçersiz format belirtildi. Geçerli seçenekler yaml ve json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Görünüşe göre zaten bir uygulama şifreleme anahtarı yapılandırmışsınız. Bu işleme devam etmek, bu anahtarı üzerine yazacak ve mevcut şifrelenmiş veriler için veri bozulmasına neden olacaktır. NE YAPTIĞINIZI BİLMİYORSANIZ DEVAM ETMEYİN.',
        'understand' => 'Bu komutu çalıştırmanın sonuçlarını anlıyorum ve şifrelenmiş verilerin kaybından tamamen sorumluluğu kabul ediyorum.',
        'continue' => 'Devam etmek istediğinizden emin misiniz? Uygulama şifreleme anahtarını değiştirmek VERİ KAYBINA NEDEN OLACAKTIR.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Çalıştırılması gereken zamanlanmış görevler bulunmamaktadır.',
            'error_message' => 'Zamanlanmış görev işlenirken bir hata oluştu: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Bu komut, indirilen varlıkların bütünlüğünü doğrulamaz. Devam etmeden önce indirme kaynağına güvendiğinizden emin olun. Bir arşiv indirmek istemiyorsanız, lütfen --skip-download bayrağını kullanarak veya aşağıdaki soruya "hayır" yanıtı vererek belirtin.',
        'source_url' => 'İndirme Kaynağı (--url= ile ayarlayın):',
        'php_version' => 'Kendi kendine güncelleme işlemi çalıştırılamıyor. Gereken minimum PHP sürümü 7.4.0, sizde olan sürüm:',
        'skipDownload' => 'En son sürüm için arşiv dosyalarını indirip açmak ister misiniz?',
        'webserver_user' => 'Web sunucu kullanıcınız <fg=blue>[{:user}]:</> olarak algılandı, bu doğru mu?',
        'name_webserver' => 'Lütfen web sunucu işlemini çalıştıran kullanıcının adını girin. Bu sistemden sisteme değişir, ancak genellikle "www-data", "nginx" veya "apache" olur.',
        'group_webserver' => 'Web sunucu grubunuz <fg=blue>[{:group}]:</> olarak algılandı, bu doğru mu?',
        'group_webserver_question' => 'Lütfen web sunucu işlemini çalıştıran grubun adını girin. Normalde bu, kullanıcınızla aynıdır.',
        'are_your_sure' => 'Paneliniz için güncelleme işlemini çalıştırmak istediğinizden emin misiniz?',
        'terminated' => 'Güncelleme işlemi kullanıcı tarafından sonlandırıldı.',
        'success' => 'Panel başarıyla güncellendi. Lütfen herhangi bir Daemon örneğini de güncellediğinizden emin olun.',

    ],
];
