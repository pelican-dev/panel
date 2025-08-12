<?php

return [
    'user' => [
        'search_users' => 'Masukkan Username, User ID, atau Email',
        'select_search_user' => 'ID untuk pengguna yang ingin dihapus (Ketik \'0\' untuk mencari ulang)',
        'deleted' => 'Pengguna berhasil dihapus dari Panel.',
        'confirm_delete' => 'Apakah anda yakin ingin menghapus pengguna ini dari Panel?',
        'no_users_found' => 'Tidak ada pengguna yang ditemukan untuk istilah pencarian yang digunakan.',
        'multiple_found' => 'Ditemukan beberapa akun untuk pengguna yang diberikan, tidak dapat menghapus pengguna karena flag --no-interaction',
        'ask_admin' => 'Apakah user ini administrator?',
        'ask_email' => 'Alamat Email',
        'ask_username' => 'Nama Pengguna',
        'ask_password' => 'Kata sandi',
        'ask_password_tip' => 'Jika Anda ingin membuat akun dengan kata sandi acak yang akan diemailkan ke pengguna, jalankan kembali perintah ini (CTRL+C) dan tambahkan flag `--no-password`.',
        'ask_password_help' => 'Kata sandi harus terdiri dari setidaknya 8 karakter dan mengandung setidaknya satu huruf kapital dan angka.',
        '2fa_help_text' => [
            'Perintah ini akan menonaktifkan autentikasi 2 faktor untuk akun pengguna jika diaktifkan. Perintah ini hanya boleh digunakan sebagai perintah pemulihan akun jika pengguna terkunci dari akun mereka.',
            'Jika bukan ini yang ingin anda lakukan, tekan CTRL+C untuk keluar dari proses ini.',
        ],
        '2fa_disabled' => 'Autentikasi 2 faktor telah dinonaktifkan untuk :email.',
    ],
    'schedule' => [
        'output_line' => 'Menjalankan tugas pertama dalam `:schedule` (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Menghapus file cadangan layanan :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Permintaan pembuatan ulang untuk “:name” (#:id) pada node “:node” gagal dengan kesalahan: :message',
        'reinstall' => [
            'failed' => 'Permintaan instal ulang untuk “:name” (#:id) pada node “:node” gagal dengan kesalahan: :message',
            'confirm' => 'Anda akan menginstal ulang pada sekelompok server. Apakah Anda ingin melanjutkan?',
        ],
        'power' => [
            'confirm' => 'Anda akan melakukan tindakan :action terhadap :count server. Apakah Anda ingin melanjutkan?',
            'action_failed' => 'Permintaan tindakan daya untuk “:name” (#:id) pada node “:node” gagal dengan kesalahan: :pesan',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'Host SMTP (Cth. smtp.gmail.com)',
            'ask_smtp_port' => 'Port SMTP',
            'ask_smtp_username' => 'Nama Pengguna SMTP',
            'ask_smtp_password' => 'Kata Sandi SMTP',
            'ask_mailgun_domain' => 'Laman Mailgun',
            'ask_mailgun_endpoint' => 'Endpoint Mailgun',
            'ask_mailgun_secret' => 'Kunci Rahasia Mailgun',
            'ask_mandrill_secret' => 'Kunci Rahasia Mandrill',
            'ask_postmark_username' => 'Kunci API Postmark',
            'ask_driver' => 'Driver mana yang harus digunakan untuk mengirim email?',
            'ask_mail_from' => 'Driver mana yang harus digunakan untuk mengirim email?',
            'ask_mail_name' => 'Nama dari mana email akan dikirim',
            'ask_encryption' => 'Metode Enkripsi untuk digunakan',
        ],
    ],
];
