<?php

return [
    'notices' => [
        'imported' => 'Berhasil mengimport Egg dan variabel terkaitnya.',
        'updated_via_import' => 'Egg ini telah diperbarui menggunakan file yang disediakan.',
        'deleted' => 'Berhasil menghapus Egg dari Panel.',
        'updated' => 'Konfigurasi Egg ini telah berhasil diperbarui.',
        'script_updated' => 'Pemasangan Script Egg telah diperbarui dan akan dijalankan setiap kali server dipasang.',
        'egg_created' => 'Egg baru berhasil ditetaskan. Anda perlu memulai ulang semua daemon yang sedang berjalan untuk menerapkan telur baru ini.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Variabel “:variable” telah dihapus dan tidak akan lagi tersedia untuk server setelah dibangun kembali.',
            'variable_updated' => 'Variabel “:variable” telah diperbarui. Anda perlu membangun ulang semua server yang menggunakan variabel ini untuk menerapkan perubahan.',
            'variable_created' => 'Variabel baru telah berhasil dibuat dan ditetapkan ke egg ini.',
        ],
    ],
    'descriptions' => [
        'name' => 'Nama yang simpel dan mudah dibaca oleh manusia untuk digunakan sebagai pengenal egg ini.',
        'description' => 'Deskripsi Egg yang akan ditampilkan di seluruh Panel sesuai kebutuhan.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'Penulis versi Egg ini. Mengunggah konfigurasi Telur baru dari pembuat yang berbeda akan mengubahnya.',
        'force_outgoing_ip' => "Memaksa semua lalu lintas jaringan keluar agar IP Sumbernya di-NAT-kan ke IP dari IP alokasi utama server.\nDiperlukan supaya game tertentu dapat bekerja dengan baik ketika Node memiliki beberapa alamat IP publik.\nMengaktifkan opsi ini akan menonaktifkan jaringan internal untuk semua server yang menggunakan Egg ini, menyebabkan mereka tidak dapat mengakses server lain secara internal pada node yang sama.",
        'startup' => 'Perintah startup default yang sebaiknya digunakan untuk server baru yang menggunakan Egg ini.',
        'docker_images' => 'Docker images yang tersedia pada server yang menggunakan Egg ini.',
    ],
];
