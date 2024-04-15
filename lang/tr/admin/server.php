<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Bu sunucunun varsayılan lokasyonunu silmeye çalışıyorsunuz ancak kullanılacak bir yedek lokasyon yok.',
        'marked_as_failed' => 'Bu sunucu önceki yüklemede başarısız olarak işaretlendi. Bu durumda mevcut durum değiştirilemez.',
        'bad_variable' => ':name değişkeninde bir hata oluştu.',
        'daemon_exception' => 'Arka plan programıyla iletişim kurmaya çalışırken bir HTTP/:code yanıt koduyla sonuçlanan bir hata oluştu. Bu haya günlüğe kaydedildi. (istek kimliği: :request_id)',
        'default_allocation_not_found' => 'İstenen varsayılan tahsis, bu sunucunun tahsislerinde bulunamadı.',
    ],
    'alerts' => [
        'startup_changed' => 'Bu sunucunun başlangıç yapılandırması güncellendi. Bu sunucunun Egg\'i değiştirildiyse şimdi yeniden yükleme gerçekleştirilecek.',
        'server_deleted' => 'Sunucu sistemden başarıyla silindi.',
        'server_created' => 'Sunucu panelde başarıyla oluşturuldu. Lütfen arka plan programının bu sunucuyu tamamen kurması için birkaç dakika bekleyin.',
        'build_updated' => 'Bu sunucunun yapı ayrıntıları güncellendi. Bazı değişikliklerin geçerli olması için yeniden başlatma gerekebilir.',
        'suspension_toggled' => 'Sunucunun askıya alınma durumu :status olarak değiştirildi.',
        'rebuild_on_boot' => 'Bu sunucu, Docker Container\'ın yeniden oluşturulmasını gerektiriyor olarak işaretlendi. Bu, sunucunun bir sonraki başlatılışında gerçekleşecektir.',
        'install_toggled' => 'Bu sunucunun kurulum durumu değiştirildi.',
        'server_reinstalled' => 'Bu sunucu şu andan itibaren yeniden kurulum için sıraya alındı.',
        'details_updated' => 'Sunucu ayrıntıları başarıyla güncellendi.',
        'docker_image_updated' => 'Bu sunucu için kullanılacak varsayılan Docker görüntüsü başarıyla değiştirildi. Bu değişikliğin uygulanması için yeniden başlatma gereklidir.',
        'node_required' => 'Bu panele sunucu ekleyebilmeniz için en az bir node yapılandırılmış olması gerekir.',
        'transfer_nodes_required' => 'Sunucuları aktarabilmeniz için en az iki node yapılandırılmış olması gerekir.',
        'transfer_started' => 'Sunucu transferi başlatılmıştır.',
        'transfer_not_viable' => 'Seçtiğiniz node, bu sunucuyu barındırmak için gerekli disk alanına veya belleğe sahip değil.',
    ],
];
