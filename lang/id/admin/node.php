<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'FQDN atau alamat IP yang diberikan tidak sesuai dengan alamat IP yang valid.',
        'fqdn_required_for_ssl' => 'Sebuah nama domain absolut yang tertuju pada suatu alamat IP Publik diperlukan untuk menggunakan SSL pada node ini.',
    ],
    'notices' => [
        'allocations_added' => 'Alokasi telah berhasil ditambahkan ke node ini.',
        'node_deleted' => 'Node telah berhasil dihapus dari panel.',
        'node_created' => 'Node baru berhasil dibuat. Anda dapat secara otomatis mengonfigurasi daemon pada mesin ini dengan mengunjungi tab \'Konfigurasi\'. <strong>Sebelum Anda dapat menambahkan server, Anda harus terlebih dahulu mengalokasikan setidaknya satu alamat IP dan port.</strong>',
        'node_updated' => 'Informasi node telah diperbarui. Jika ada pengaturan daemon yang diubah, Anda perlu melakukan reboot agar perubahan tersebut dapat diterapkan.',
        'unallocated_deleted' => 'Berhasil Menghapus semua port yang tidak dialokasikan untuk <code>:ip</code>.',
    ],
];
