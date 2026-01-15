<?php

return [
    'daemon_connection_failed' => 'Daemon ile iletişim kurulmaya çalışılırken bir istisna oluştu ve HTTP/:code yanıt kodu alındı. Bu istisna kaydedildi.',
    'node' => [
        'servers_attached' => 'Bir düğümün silinebilmesi için hiçbir sunucuya bağlı olmaması gerekir.',
        'error_connecting' => ':node ile bağlantı kurulurken hata oluştu',
        'daemon_off_config_updated' => 'Daemon yapılandırması <strong>güncellendi</strong>, ancak Daemon üzerindeki yapılandırma dosyasını otomatik olarak güncellemeye çalışırken bir hata oluştu. Bu değişiklikleri uygulamak için yapılandırma dosyasını (config.yml) manuel olarak güncellemeniz gerekecek.',
    ],
    'allocations' => [
        'server_using' => 'Bu tahsis, şu anda bir sunucuya atanmış durumda. Bir tahsis yalnızca hiçbir sunucuya atanmamışsa silinebilir.',
        'too_many_ports' => 'Tek seferde 1000\'den fazla port eklemek desteklenmiyor.',
        'invalid_mapping' => ':port için sağlanan eşleme geçersiz ve işlenemedi.',
        'cidr_out_of_range' => 'CIDR notasyonu yalnızca /25 ile /32 arasındaki maskelere izin verir.',
        'port_out_of_range' => 'Bir tahsisdeki portlar 1024\'ten büyük veya eşit ve 65535\'ten küçük veya eşit olmalıdır.',
    ],
    'egg' => [
        'delete_has_servers' => 'Üzerinde aktif sunucular bulunan bir Egg, Panel\'den silinemez.',
        'invalid_copy_id' => 'Komut dosyası kopyalamak için seçilen Egg ya mevcut değil ya da kendisi bir komut dosyası kopyalıyor.',
        'has_children' => 'Bu Egg, bir veya daha fazla Egg\'in ebeveynidir. Lütfen bu Egg\'i silmeden önce diğer Egg\'leri silin.',
    ],
    'variables' => [
        'env_not_unique' => ':name ortam değişkeni bu Egg için benzersiz olmalıdır.',
        'reserved_name' => ':name ortam değişkeni korumalıdır ve bir değişkene atanamaz.',
        'bad_validation_rule' => '":rule" doğrulama kuralı bu uygulama için geçerli bir kural değildir.',
    ],
    'importer' => [
        'json_error' => 'JSON dosyası ayrıştırılırken bir hata oluştu: :error.',
        'file_error' => 'Sağlanan JSON dosyası geçerli değil.',
        'invalid_json_provided' => 'Sağlanan JSON dosyası tanınabilir bir biçimde değil.',
    ],
    'subusers' => [
        'editing_self' => 'Kendi alt kullanıcı hesabınızı düzenlemenize izin verilmez.',
        'user_is_owner' => 'Sunucu sahibini bu sunucu için alt kullanıcı olarak ekleyemezsiniz.',
        'subuser_exists' => 'Bu e-posta adresine sahip bir kullanıcı zaten bu sunucuya alt kullanıcı olarak atanmış.',
    ],
    'databases' => [
        'delete_has_databases' => 'Üzerinde aktif veritabanları bulunan bir veritabanı sunucusu silinemez.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Zincirleme bir görev için maksimum aralık süresi 15 dakikadır.',
    ],
    'locations' => [
        'has_nodes' => 'Üzerinde aktif düğümler bulunan bir konum silinemez.',
    ],
    'users' => [
        'is_self' => 'Kendi kullanıcı hesabınızı silemezsiniz.',
        'has_servers' => 'Hesabında aktif sunucular bulunan bir kullanıcı silinemez. Lütfen devam etmeden önce sunucularını silin.',
        'node_revocation_failed' => '<a href=":link">#:node Düğümü</a> üzerinde anahtarlar iptal edilemedi. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Otomatik dağıtım için belirtilen gereksinimleri karşılayan hiçbir düğüm bulunamadı.',
        'no_viable_allocations' => 'Otomatik dağıtım için gereksinimleri karşılayan hiçbir tahsis bulunamadı.',
    ],
    'api' => [
        'resource_not_found' => 'İstenen kaynak bu sunucuda bulunamadı.',
    ],
    'mount' => [
        'servers_attached' => 'Bir bağlama noktasının silinebilmesi için hiçbir sunucuya bağlı olmaması gerekir.',
    ],
    'server' => [
        'marked_as_failed' => 'Bu sunucu henüz kurulum sürecini tamamlamadı, lütfen daha sonra tekrar deneyin.',
    ],
];
