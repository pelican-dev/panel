<?php

return [
    'notices' => [
        'imported' => 'Bu Egg ve ilişkili değişkenleri başarıyla içe aktarıldı.',
        'updated_via_import' => 'Bu Egg sağlanan dosya kullanılarak güncellendi.',
        'deleted' => 'İstenen Egg panelden başarıyla silindi.',
        'updated' => 'Egg konfigürasyonu başarıyla güncellendi.',
        'script_updated' => 'Egg kurulum scripti güncellendi ve sunucular kurulduğunda çalıştırılacaktır..',
        'egg_created' => 'Yeni bir Egg başarıyla eklendi. Bu yeni Egg\'i uygulamak için çalışan tüm arka plan programlarını yeniden başlatmanız gerekecek.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => '":variable" değişkeni silindi ve yeniden oluşturulduktan sonra artık sunucular tarafından kullanılamayacak.',
            'variable_updated' => '":variable" değişkeni güncellendi. Değişiklikleri uygulamak için bu değişkeni kullanarak tüm sunucuları yeniden oluşturmanız gerekecektir.',
            'variable_created' => 'Yeni değişken başarıyla oluşturuldu ve bu Egg atandı.',
        ],
    ],
    'descriptions' => [
        'name' => 'Bu Egg için tanımlayıcı olarak kullanılacak basit ve okunabilir bir isim.',
        'description' => 'Bu Egg için gerekli oldukça Panelde gösterilecek bir açıklama.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'Bu Egg\'in bu sürümünün sahibi. Başkası tarafından yapılan bir Egg dosyası yüklenirse bu alan değişir.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'Bu Egg\'i kullanan sunucuların varsayılan başlatma komutu.',
        'docker_images' => 'Bu Egg\'i kullanan sunuculara mevcut Docker Image\'leri',
    ],
];
