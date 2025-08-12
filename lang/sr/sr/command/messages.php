<?php

return [
    'user' => [
        'search_users' => 'Unesite korisničko ime, ID korisnika ili adresu E-Pošte',
        'select_search_user' => 'ID korisnika za brisanje (unesite \'0\' za ponovnu pretragu)',
        'deleted' => 'Korisnik je uspešno obrisan sa Panela',
        'confirm_delete' => 'Da li ste sigurni da želite da obrišete ovog korisnika sa Panela?',
        'no_users_found' => 'Nisu pronađeni korisnici za uneti termin pretrage.',
        'multiple_found' => 'Pronađeni su višestruki nalozi za unetog korisnika, nije moguće obrisati korisnika zbog zastavice --no-interaction.',
        'ask_admin' => 'Da li je ovaj korisnik administrator?',
        'ask_email' => 'Email adresa',
        'ask_username' => 'Korisnicko ime',
        'ask_password' => 'Sifra',
        'ask_password_tip' => 'Ako želite da kreirate nalog sa nasumičnom lozinkom koja će biti poslata korisniku putem e-pošte, ponovo pokrenite ovu komandu (CTRL+C) i dodajte zastavicu --no-password.',
        'ask_password_help' => 'Lozinke moraju imati najmanje 8 karaktera i sadržati barem jedno veliko slovo i broj.',
        '2fa_help_text' => [
            'Ova komanda će onemogućiti dvofaktorsku autentifikaciju za korisnički nalog ukoliko je aktivirana. Ovo bi trebalo koristiti samo kao komandu za oporavak naloga ako je korisnik zaključan iz svog naloga.',
            'Ako ovo nije ono što želite da uradite, pritisnite CTRL+C da izađete iz ovog procesa.',
        ],
        '2fa_disabled' => 'Dvofaktorska autentifikacija je onemogućena za :email.',
    ],
    'schedule' => [
        'output_line' => 'Pokreće se zadatak za prvi posao u :schedule (:id).',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'Brišem rezervnu datoteku usluge :file.',
    ],
    'server' => [
        'rebuild_failed' => 'Zahtev za ponovnu izgradnju za ":name" (#:id) na čvoru ":node" nije uspeo sa greškom: :message.',
        'reinstall' => [
            'failed' => 'Zahtev za ponovnu instalaciju za ":name" (#:id) na čvoru ":node" nije uspeo sa greškom: :message.',
            'confirm' => 'Spremate se za ponovnu instalaciju na grupu servera. Da li želite da nastavite?',
        ],
        'power' => [
            'confirm' => 'Spremate se da izvršite :action na :count servera. Da li želite da nastavite?',
            'action_failed' => 'Zahtev za radnju napajanja za ":name" (#:id) na čvoru ":node" nije uspeo sa greškom: :message.',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP Host (npr. smtp.gmail.com)',
            'ask_smtp_port' => 'SMPT Port',
            'ask_smtp_username' => 'SMPT Ime',
            'ask_smtp_password' => 'SMPT Sifra',
            'ask_mailgun_domain' => 'Mailgun domen (Domain)',
            'ask_mailgun_endpoint' => 'Mailgun Endpoint (Odredište)',
            'ask_mailgun_secret' => 'Mailgun tajna',
            'ask_mandrill_secret' => 'Mandrill tajna',
            'ask_postmark_username' => 'Postmark API ključ',
            'ask_driver' => 'Koji drajver treba koristiti za slanje e-pošte?',
            'ask_mail_from' => 'Adresa E-Pošte sa koje treba da potiču email poruke.',
            'ask_mail_name' => 'Ime koje treba da se pojavljuje kao pošiljalac email poruka.',
            'ask_encryption' => 'Metoda enkripcije koju treba koristiti.',
        ],
    ],
];
