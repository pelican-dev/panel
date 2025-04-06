<?php

return [
    'user' => [
        'search_users' => 'Įveskite naudotojo vardą, naudotojo ID arba el. pašto adresą',
        'select_search_user' => 'Naudotojo ID, kurį norite ištrinti (įveskite „0“, jei norite ieškoti iš naujo)',
        'deleted' => 'Naudotojas sėkmingai ištrintas iš skydelio.',
        'confirm_delete' => 'Ar tikrai norite ištrinti šį naudotoją iš skydelio?',
        'no_users_found' => 'Pagal pateiktą paieškos užklausą naudotojų nerasta.',
        'multiple_found' => 'Rastos kelios naudotojų paskyros. Naudotojo ištrinti nepavyko dėl nustatyto --no-interaction parametro.',
        'ask_admin' => 'Ar šis naudotojas yra administratorius?',
        'ask_email' => 'El. pašto adresas',
        'ask_username' => 'Naudotojo vardas',
        'ask_password' => 'Slaptažodis',
        'ask_password_tip' => 'Jei norite sukurti paskyrą su atsitiktiniu slaptažodžiu, kuris bus išsiųstas naudotojui el. paštu, paleiskite šią komandą iš naujo (CTRL+C) ir pasirinkite `--no-password` parametrą.',
        'ask_password_help' => 'Slaptažodžiai turi būti bent 8 simbolių ilgio ir turėti bent vieną didžiąją raidę bei skaičių.',
        '2fa_help_text' => [
            'Ši komanda išjungs 2-iejų faktorių autentifikaciją naudotojo paskyrai, jei ji buvo įjungta. Tai turėtų būti naudojama tik kaip paskyros atkūrimo komanda, jei naudotojas negali prisijungti prie savo paskyros.',
            'Jei tai nebuvo jūsų norimas veiksmas, paspauskite CTRL+C, kad išeitumėte iš šio proceso.',
        ],
        '2fa_disabled' => '2-iejų faktorių autentifikacija buvo išjungta paskyrai su el. pašto adresu :email.',
    ],
    'schedule' => [
        'output_line' => 'Išsiunčiamas užduoties vykdymas pirmam darbui :schedule (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Trinama paslaugos atsarginė kopija failas :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Skelbiant prašymą perstatyti ":name" (#:id) „node“ „:node“ įvyko klaida: :message',
        'reinstall' => [
            'failed' => 'Skelbiant prašymą įdiegti iš naujo „:name“ (#:id) „node“ „:node“ įvyko klaida: :message',
            'confirm' => 'Jūs ruošiatės atlikti naują diegimą grupėje serverių. Ar norite tęsti?',
        ],
        'power' => [
            'confirm' => 'Ketinate atlikti :action su :count serveriais. Ar norite tęsti?',
            'action_failed' => 'Skelbiant jungimo įvykį „:name“ (#:id) „node“ „:node“ įvyko klaida: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP serveris (pvz., smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP prievadas',
            'ask_smtp_username' => 'SMTP naudotojo vardas',
            'ask_smtp_password' => 'SMTP Slaptažodis',
            'ask_mailgun_domain' => 'Mailgun Domenas',
            'ask_mailgun_endpoint' => 'Mailgun galinis taškas',
            'ask_mailgun_secret' => 'Mailgun paslaptis',
            'ask_mandrill_secret' => 'Mandrill paslaptis',
            'ask_postmark_username' => 'Postmark API raktas',
            'ask_driver' => 'Kuri opcija turėtų būti naudojama siunčiant el. laiškus?',
            'ask_mail_from' => 'El. pašto adresas, iš kurio turėtų kilti el. laiškai',
            'ask_mail_name' => 'Vardas, kuris turi būti rodomas kaip siuntėjas el. laiškuose',
            'ask_encryption' => 'Naudojama šifravimo metodika',
        ],
    ],
];
