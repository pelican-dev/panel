<?php

return [
    'user' => [
        'search_users' => 'Kérlek írd ide a felhasználónevet, azonosító számot vagy E-mail címet!',
        'select_search_user' => 'A törölni kívánt felhasználó ID száma (Nyomj a \'0\' -ra az újra kereséshez)',
        'deleted' => 'A felhasználó törölve a panel adatbázisából.',
        'confirm_delete' => 'Biztos vagy benne, hogy törlöd a felhasználót a panel adatbázisából?',
        'no_users_found' => 'Nem található felhasználó a megadott keresési adatokkal.',
        'multiple_found' => 'Több felhasználói fiók is található a megadott felhasználói azonosító alatt, nem törölhető, mert --no-interaction.',
        'ask_admin' => 'Ez a felhasználó rendelkezik adminisztrátori jogosultsággal?',
        'ask_email' => 'E-mail cím',
        'ask_username' => 'Felhasználónév',
        'ask_password' => 'Jelszó',
        'ask_password_tip' => 'Ha olyan fiókot szeretne létrehozni, amelynek jelszavát véletlenszerűen küldi el e-mailben a felhasználónak, futtassa újra ezt a parancsot (CTRL+C), és adja meg a `--no-password` jelzőt.',
        'ask_password_help' => 'A jelszavaknak legalább 8 karakter hosszúságúnak kell lenniük, és legalább egy nagybetűt és egy számot kell tartalmazniuk.',
        '2fa_help_text' => 'Ez a parancs letiltja a 2 faktoros azonosítást a felhasználó fiókjában, ha az engedélyezve van. Ezt kizárólag fiókhelyreállítás céljából szabad használni, ha a felhasználó nem tud hozzáférni a fiókjához. Ha nem ezt szeretnéd végrehajtani, nyomd meg a CTRL+C billentyűkombinációt a folyamat megszakításához.',
        '2fa_disabled' => 'A 2-faktoros hitelesítés letiltásra került a :email esetében.',
    ],
    'schedule' => [
        'output_line' => 'Munkamenet indítása az első feladathoz a(z) `:schedule`-ban (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Biztonsági mentési fájl törlése :file.',
    ],
    'server' => [
        'rebuild_failed' => 'A ":node” csomópont ":name” (#:id) újraépítési kérelme sikertelen volt, hiba: :message',
        'reinstall' => [
            'failed' => 'A ":node” csomópont ":name” (#:id) újra telepítési kérelme sikertelen volt, hiba: :message',
            'confirm' => 'Egyszerre több szervert is újratelepíteni készülsz. Biztos, hogy folytatni szeretnéd?',
        ],
        'power' => [
            'confirm' => 'Ön egy :actiont készül végrehajtani :count szervernél. Szeretné folytatni?',
            'action_failed' => 'A ":name" (#:id) számára a ":node" csomóponton meghiúsult energiaellátási művelet kérése: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Kiszolgáló (pl.: smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP Port',
            'ask_smtp_username' => 'SMTP Felhasználónév',
            'ask_smtp_password' => 'SMTP Jelszó',
            'ask_mailgun_domain' => 'Mailgun tartomány',
            'ask_mailgun_endpoint' => 'Mailgun végpont',
            'ask_mailgun_secret' => 'Mailgun titkos kulcs',
            'ask_mandrill_secret' => 'Mandrill titkos kulcs',
            'ask_postmark_username' => 'Postmark API kulcs',
            'ask_driver' => 'Melyik illesztőprogramot szeretnél használni az e-mailek küldéséhez?',
            'ask_mail_from' => 'E-mail cím, ahonnan az e-maileknek származniuk kell',
            'ask_mail_name' => 'Név, amelyről az e-maileknek meg kell jelenniük',
            'ask_encryption' => 'Használandó titkosítási módszer',
        ],
    ],
];
