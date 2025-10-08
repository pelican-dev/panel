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
        'success' => 'Kirjauduttu sisään',
        'password-reset' => 'Salasanan resetointi',
        'checkpoint' => 'Kaksivaiheinen todennut pyydetty',
        'recovery-token' => 'Käytetty kaksivaihteisen valmennuksen palautus token',
        'token' => 'Selvitetty kaksivaihteisen todennuksen haaste',
        'ip-blocked' => 'Estetty pyyntö tuntemattomasta IP osoitteesta <B>:identifier</B>',
        'sftp' => [
            'fail' => 'SFTP kirjautuminen epöonnistui',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Vaihdettu sähköposti vanhasta <b>:old</b> osoitteesta uuteen osoitteeseen <b>:new</b>',
            'password-changed' => 'Salasana vaihdettu',
        ],
        'api-key' => [
            'create' => 'Luotu uusi API avain <b>:identifier</b>',
            'delete' => 'Poistettu API avain <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Lisätty SSH avain <b>:fingerprint</b> käyttäjälle.',
            'delete' => 'Poistettu SSH avain <b>:fingerprint</b> käyttäjältäsi.',
        ],
        'two-factor' => [
            'create' => 'Aktivoitu kaksivaihteinen todennus',
            'delete' => 'Kaksivaiheinen todennus on poistettu käytöstä',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Suoritettu "<b>:command</b>" palvelimella',
        ],
        'power' => [
            'start' => 'Käynnistetty palvelin',
            'stop' => 'Pysäytetty palvelin',
            'restart' => 'Uudelleen käynnistetty palvelin',
            'kill' => 'Tapettu palvelimen prosessi',
        ],
        'backup' => [
            'download' => 'Ladattu <b>:name</b> varmuuskopio',
            'delete' => 'Poistettu <b>:name</b> varmuuskopio',
            'restore' => 'Palautettu <b>:name</b> varmuuskopio (poistettu tiedostoja: <b>:truncate</b>)',
            'restore-complete' => 'Suoritettu <b>:name</b> -varmuuskopion palauttaminen',
            'restore-failed' => '<b>:name</b> -varmuuskopion palauttaminen epäonnistui',
            'start' => 'Aloitettiin uusi varmuuskopio <b>:name</b>',
            'complete' => 'Varmuuskopio <b>:name</b> on merkitty valmiiksi',
            'fail' => 'Varmuuskopio <b>:name</b> on merkitty epäonnistuneeksi',
            'lock' => 'Varmuuskopio <b>:name</b> lukittiin',
            'unlock' => 'Poistettu <b>:name</b> varmuuskopion esto',
            'rename' => 'Varmuuskopio nimettiin uudelleen "<b>:old_name</b>" → "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Luotiin uusi tietokanta <b>:name</b>',
            'rotate-password' => 'Tietokannan <b>:name</b> salasana vaihdettu',
            'delete' => 'Poistettiin tietokanta <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Pakattu <b>:directory:files</b>|Pakattu <b>:count</b> tiedostoa kansiossa <b>:directory</b>',
            'read' => 'Katsottu tiedoston <b>:file</b> sisältö',
            'copy' => 'Luotu kopio tiedostosta <b>:file</b>',
            'create-directory' => 'Luotu hakemisto <b>:Directory:name</b>',
            'decompress' => 'Purettu <b>:file</b> kansioon <b>:directory</b>',
            'delete' => 'Poistettu <b>:directory:files</b>|Poistettu <b>:count</b> tiedostoa kansiosta <b>:directory</b>',
            'download' => 'Ladattu <b>:file</b>',
            'pull' => 'Ladattu etätiedosto osoitteesta <b>:url</b> kansioon <b>:directory</b>',
            'rename' => 'Siirretty/ Nimetty uudelleen <b>:from</b> nimellä <b>:to</b>|Siirretty/ Nimetty uudelleen <b>:count</b> tiedostoa kansiossa <b>:directory</b>',
            'write' => 'Kirjoitettu uusi sisältö tiedostoon <b>:file</b>',
            'upload' => 'Tiedoston lataus aloitettu',
            'uploaded' => 'Ladattu palvelimelle <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'SFTP-käyttö estetty käyttöoikeuksien vuoksi',
            'create' => 'Luotu <b>:files</b>|Luotu <b>:count</b> uutta tiedostoa',
            'write' => 'Muokattu tiedoston <b>:files</b> sisältöä|Muokattu <b>:count</b> tiedoston sisältöä',
            'delete' => 'Poistettu <b>:files</b>|Poistettu <b>:count</b> tiedostoa',
            'create-directory' => 'Luotu <b>:files</b> kansio|Luotu <b>:count</b> kansiota',
            'rename' => 'Nimetty uudelleen <b>:from</b> nimellä <b>:to</b>|Nimetty uudelleen tai siirretty <b>:count</b> tiedostoa',
        ],
        'allocation' => [
            'create' => 'Lisätty <b>:allocation</b> palvelimelle',
            'notes' => 'Päivitetty huomautukset kohteelle <b>:allocation</b> arvosta "<b>:old</b>" arvoon "<b>:new</b>"',
            'primary' => 'Asetettu <b>:allocation</b> palvelimen ensisijaiseksi allokaatioksi',
            'delete' => 'Poistettu <b>:allocation</b> allokaatio',
        ],
        'schedule' => [
            'create' => 'Luotu <b>:name</b> ajastus',
            'update' => 'Päivitetty <b>:name</b> ajastusta',
            'execute' => 'Suoritettu manuaalisesti <b>:name</b> ajastus',
            'delete' => 'Poistettu <b>:name</b> ajastus',
        ],
        'task' => [
            'create' => 'Luotu uusi "<b>:action</b>" tehtävä <b>:name</b> ajastukseen',
            'update' => 'Päivitetty "<b>:action</b>" tehtävä <b>:name</b> ajastuksessa',
            'delete' => 'Poistettu "<b>:action</b>" tehtävä <b>:name</b> ajastuksesta',
        ],
        'settings' => [
            'rename' => 'Nimetty palvelin uudelleen nimestä "<b>:old</b>" nimeen "<b>:new</b>"',
            'description' => 'Vaihdettu palvelimen kuvaus arvosta "<b>:old</b>" arvoon "<b>:new</b>"',
            'reinstall' => 'Asennettu palvelin uudelleen',
        ],
        'startup' => [
            'edit' => 'Vaihdettu <b>:variable</b> muuttuja arvosta "<b>:old</b>" arvoon "<b>:new</b>"',
            'image' => 'Päivitetty palvelimen Docker-kuva arvosta <b>:old</b> arvoon <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Lisättiin <b>:email</b> alikäyttäjäksi',
            'update' => 'Päivitetty alikäyttäjän <b>:email</b> käyttöoikeudet',
            'delete' => 'Poistettiin käyttäjä <b>:email</b> alikäyttäjistä',
        ],
        'crashed' => 'Palvelin Kaatui',
    ],
];
