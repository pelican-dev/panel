<?php

return [
    'daemon_connection_failed' => 'Arka plan programıyla iletişim kurmaya çalışırken bir HTTP/:code yanıt koduyla sonuçlanan bir hata oluştu. Bu hata günlüğe kaydedildi.',
    'node' => [
        'servers_attached' => 'Bir node\'un silinebilmesi için kendisine bağlı hiçbir sunucunun olmaması gerekir.',
        'daemon_off_config_updated' => 'Daemon yapılandırması <strong>güncellendi</strong>, ancak Daemon\'daki yapılandırma dosyası otomatik olarak güncellenmeye çalışılırken bir hatayla karşılaşıldı. Bu değişiklikleri uygulamak için arka plan programının yapılandırma dosyasını (config.yml) manuel olarak güncellemeniz gerekecektir.',
    ],
    'allocations' => [
        'server_using' => 'Şu anda bu lokasyon bir sunucu atanmış. Bir lokasyon yalnızca şu anda hiçbir sunucu atanmamışsa silinebilir.',
        'too_many_ports' => 'Tek bir aralığa 1000\'den fazla port (Bağlantı noktası) aynı anda eklenmesi desteklenmez.',
        'invalid_mapping' => ':port için sağlanan eşleme geçersizdi ve uyhulanmadı.',
        'cidr_out_of_range' => 'CIDR gösterimi yalnızca /25 ile /32 arasındaki maskelere izin verir.',
        'port_out_of_range' => 'Bir tahsisteki bağlantı noktaları 1024\'ten büyük ve 65535\'ten küçük veya ona eşit olmalıdır.',
    ],
    'egg' => [
        'delete_has_servers' => 'Aktif sunucuların bağlı olduğu bir Node Panelden silinemez.',
        'invalid_copy_id' => 'Bir komut dosyasını kopyalamak için seçilen Node mevcut değil veya bir komut dosyasının kendisini kopyalıyor.',
        'has_children' => 'Bu Node bir veya daha fazla Node\'un ebeveynidir. Lütfen bu Node\'u silmeden önce bu Yumurtaları silin.',
    ],
    'variables' => [
        'env_not_unique' => ':name ortam değişkeni bu Egg\'e özgü olmalıdır.',
        'reserved_name' => 'Ortam değişkeni :name korunur ve bir değişkene atanamaz.',
        'bad_validation_rule' => 'Doğrulama kuralı ":rule" bu uygulama için geçerli bir kural değil.',
    ],
    'importer' => [
        'json_error' => 'JSON dosyası ayrıştırılmaya çalışılırken bir hata oluştu: :error.',
        'file_error' => 'Sağlanan JSON dosyası geçerli değildi.',
        'invalid_json_provided' => 'Sağlanan JSON dosyası tanınabilecek bir formatta değil.',
    ],
    'subusers' => [
        'editing_self' => 'Kendi alt kullanıcı hesabınızı düzenlemenize izin verilmez.',
        'user_is_owner' => 'Sunucu sahibini bu sunucu için alt kullanıcı olarak ekleyemezsiniz.',
        'subuser_exists' => 'Bu e-posta adresine sahip bir kullanıcı zaten bu sunucu için bir alt kullanıcı olarak atanmış.',
    ],
    'databases' => [
        'delete_has_databases' => 'Kendisine bağlı etkin veritabanları bulunan bir veritabanı ana bilgisayar sunucusu silinemez.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Zincirleme bir görev için maksimum aralık süresi 15 dakikadır.',
    ],
    'locations' => [
        'has_nodes' => 'Etkin nodeların eklendiği konum silinemez.',
    ],
    'users' => [
        'node_revocation_failed' => '<a href=":link">Node #:node</a>\'daki anahtarlar iptal edilemedi. :hata',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Otomatik dağıtım için belirtilen gereksinimleri karşılayan node bulunamadı.',
        'no_viable_allocations' => 'Otomatik dağıtım gereksinimlerini karşılayan hiçbir ayırma bulunamadı.',
    ],
    'api' => [
        'resource_not_found' => 'İstenen kaynak bu sunucuda mevcut değil.',
    ],
];
