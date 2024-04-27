<?php

return [
    'notices' => [
        'imported' => 'Berhasil mengimport Egg dan variabel terkaitnya.',
        'updated_via_import' => 'Egg ini telah diperbarui menggunakan file yang disediakan.',
        'deleted' => 'Berhasil menghapus Egg dari Panel.',
        'updated' => 'Konfigurasi Egg ini telah berhasil diperbarui.',
        'script_updated' => 'Pemasangan Script Egg telah diperbarui dan akan dijalankan setiap kali server dipasang.',
        'egg_created' => 'Telur baru berhasil dikeluarkan. Anda perlu memulai ulang semua daemon yang sedang berjalan untuk menerapkan telur baru ini.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Variabel “:variable” telah dihapus dan tidak akan lagi tersedia untuk server setelah dibangun kembali.',
            'variable_updated' => 'Variabel “:variable” telah diperbarui. Anda perlu membangun ulang semua server yang menggunakan variabel ini untuk menerapkan perubahan.',
            'variable_created' => 'Variabel baru telah berhasil dibuat dan ditetapkan ke telur ini.',
        ],
    ],
    'descriptions' => [
        'name' => 'Nama yang simpel dan mudah dibaca oleh manusia untuk digunakan sebagai pengenal Telur ini.',
        'description' => 'Deskripsi Telur yang akan ditampilkan di seluruh Panel sesuai kebutuhan.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'Penulis versi Telur ini. Mengunggah konfigurasi Telur baru dari pembuat yang berbeda akan mengubahnya.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'Perintah startup default yang sebaiknya digunakan untuk server baru yang menggunakan Egg ini.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
