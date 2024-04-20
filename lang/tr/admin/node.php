<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Sağlanan FQDN veya IP adresi geçerli bir IP adresine çözümlenmiyor.',
        'fqdn_required_for_ssl' => 'Bu düğüm için SSL kullanmak amacıyla genel bir IP adresine çözümlenen tam nitelikli bir alan adı gereklidir.',
    ],
    'notices' => [
        'allocations_added' => 'Tahsisler bu node\'a başarıyla eklendi.',
        'node_deleted' => 'Node başarılı şekilde kaldırıldı.',
        'node_created' => 'Yeni node başarıyla oluşturuldu. \'Yapılandırma\' sekmesini ziyaret ederek bu makinedeki arka plan programını otomatik olarak yapılandırabilirsiniz. <strong>Herhangi bir sunucu ekleyebilmeniz için öncelikle en az bir IP adresi ve bağlantı noktası ayırmanız gerekir.</strong>',
        'node_updated' => 'Node bilgileri güncellendi. Herhangi bir daemon ayarı değiştirildiyse, bu değişikliklerin etkili olması için onu yeniden başlatmanız gerekecektir.',
        'unallocated_deleted' => '<code>:ip</code> için ayrılmamış tüm portlar silindi.',
    ],
];
