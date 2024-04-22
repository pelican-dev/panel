<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Kirjautuminen epäonnistui',
        'success' => 'Kirjautunut sisään',
        'password-reset' => 'Salasanan palauttaminen',
        'reset-password' => 'Lähetä salasanan nollauspyyntö',
        'checkpoint' => 'Kaksivaiheista todennusta pyydetty',
        'recovery-token' => 'Käytetty kaksivaiheinen palautustunniste',
        'token' => 'Ratkaistu kaksivaiheinen haaste',
        'ip-blocked' => 'Estetty pyyntö listaamattomasta IP-osoitteesta :identifier',
        'sftp' => [
            'fail' => 'SFTP kirjautuminen epäonnistui',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Muutettu sähköpostiosoite :old muotoon :new',
            'password-changed' => 'Salasana vaihdettu',
        ],
        'api-key' => [
            'create' => 'Luotu uusi API-avain :identifier',
            'delete' => 'Poistettu API-avain :identifier',
        ],
        'ssh-key' => [
            'create' => 'Tilille lisätty SSH avain :fingerprint',
            'delete' => 'Tililtä poistettu SSH avain :fingerprint',
        ],
        'two-factor' => [
            'create' => 'Kaksivaiheinen todennus käytössä',
            'delete' => 'Kaksivaiheinen todennus poistettu käytöstä',
        ],
    ],
    'server' => [
        'reinstall' => 'Uudelleenasennettu palvelin',
        'console' => [
            'command' => 'Suoritettu ":command" palvelimelle',
        ],
        'power' => [
            'start' => 'Palvelin käynnistetty',
            'stop' => 'Palvelin pysäytetty',
            'restart' => 'Palvelin uudelleen käynnistetty',
            'kill' => 'Palvelimen prosessi tapettu',
        ],
        'backup' => [
            'download' => 'Ladattu varmuuskopio :name',
            'delete' => 'Poistettu varmuuskopio :name',
            'restore' => 'Palautettu varmuuskopio :name (poistetut tiedostot: :truncate)',
            'restore-complete' => 'Suoritettu palauttaminen varmuuskopiosta :name',
            'restore-failed' => 'Ei voitu suorittaa varmuuskopion :name palauttamista',
            'start' => 'Aloitti uuden varmuuskopion :name',
            'complete' => 'Varmuuskopio :name on merkitty valmiiksi',
            'fail' => 'Varmuuskopio :name on merkitty epäonnistuneeksi',
            'lock' => 'Varmuuskopio :name lukittiin',
            'unlock' => 'Varmuuskopio :name on avattu lukituksesta',
        ],
        'database' => [
            'create' => 'Luotiin uusi tietokanta :name',
            'rotate-password' => 'Tietokannan :name salasana vaihdettu',
            'delete' => 'Tietokanta :name poistettiin',
        ],
        'file' => [
            'compress_one' => 'Pakattu :directory:file',
            'compress_other' => 'Pakattu :count tiedostoa :directory',
            'read' => 'Tiedoston :file sisältöä tarkasteltu',
            'copy' => 'Luotu kopio tiedostosta :file',
            'create-directory' => 'Luotu hakemisto :Directory:name',
            'decompress' => ':files purettiin :directory',
            'delete_one' => 'Poistettu :directory:files.0',
            'delete_other' => 'Poistettiin :count tiedostoa :directory',
            'download' => 'Ladattu :file',
            'pull' => 'Etätiedosto ladattiin :url :directory',
            'rename_one' => 'Uudelleennimetty :directory:files.0.from :directory:files.0.to',
            'rename_other' => 'Nimetty uudelleen :count tiedostoa hakemistossa :directory',
            'write' => 'Kirjoitettu uutta sisältöä tiedostoon :file',
            'upload' => 'Tiedoston lataus aloitettu',
            'uploaded' => 'Ladattu :directory:file',
        ],
        'sftp' => [
            'denied' => 'SFTP-käyttö estetty käyttöoikeuksien vuoksi',
            'create_one' => 'Luotu :files.0',
            'create_other' => 'Luotu :count uutta tiedostoa',
            'write_one' => 'Muokattu :files.0 sisältöä',
            'write_other' => 'Muokattu :count tiedostojen sisältöä',
            'delete_one' => 'Poistettiin :files.0',
            'delete_other' => 'Poistettiin :count tiedostoa',
            'create-directory_one' => 'Luotu :files.0 hakemisto',
            'create-directory_other' => 'Luotu :count hakemistoa',
            'rename_one' => 'Tiedosto :files.0.from nimettiin uudelleen tiedostoksi :files.0.to',
            'rename_other' => 'Nimetty uudelleen tai siirretty :count tiedostoa',
        ],
        'allocation' => [
            'create' => 'Lisätty :allocation palvelimeen',
            'notes' => 'Päivitettiin muistiinpanot varaukselle :allocation ":old":sta ":new":een',
            'primary' => 'Aseta :allocation ensisijaiseksi palvelinvaraukseksi',
            'delete' => 'Poistettu :allocation varaus',
        ],
        'schedule' => [
            'create' => 'Luotu :name aikataulu',
            'update' => 'Päivitetty :name aikataulu',
            'execute' => 'Suoritettu manuaalisesti :name aikataulu',
            'delete' => 'Aikataululta :name poistettiin',
        ],
        'task' => [
            'create' => 'Luotiin uusi ":action" tehtävä aikatauluun :name.',
            'update' => 'Päivitetty ":action" tehtävä aikatauluun :name',
            'delete' => 'Poistettu tehtävä :name aikataululta',
        ],
        'settings' => [
            'rename' => 'Palvelin :old nimettiin uudelleen :new',
            'description' => 'Palvelimen vanha kuvaus :old päivitetiin :new',
        ],
        'startup' => [
            'edit' => ':variable päivitettiin vanhasta :old uuteen :new',
            'image' => 'Päivitettiin Docker-kuva palvelimelle vanhasta :old uudeksi :new',
        ],
        'subuser' => [
            'create' => 'Alikäyttäjä :email lisättiin',
            'update' => 'Alikäyttäjän :email oikeudet päivitetty',
            'delete' => 'Alikäyttäjä :email poistettu',
        ],
    ],
];
