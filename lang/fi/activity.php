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
            'rename_other' => 'Renamed :count files in :directory',
            'write' => 'Wrote new content to :file',
            'upload' => 'Began a file upload',
            'uploaded' => 'Uploaded :directory:file',
        ],
        'sftp' => [
            'denied' => 'Blocked SFTP access due to permissions',
            'create_one' => 'Created :files.0',
            'create_other' => 'Created :count new files',
            'write_one' => 'Modified the contents of :files.0',
            'write_other' => 'Modified the contents of :count files',
            'delete_one' => 'Deleted :files.0',
            'delete_other' => 'Deleted :count files',
            'create-directory_one' => 'Created the :files.0 directory',
            'create-directory_other' => 'Created :count directories',
            'rename_one' => 'Renamed :files.0.from to :files.0.to',
            'rename_other' => 'Renamed or moved :count files',
        ],
        'allocation' => [
            'create' => 'Added :allocation to the server',
            'notes' => 'Updated the notes for :allocation from ":old" to ":new"',
            'primary' => 'Set :allocation as the primary server allocation',
            'delete' => 'Deleted the :allocation allocation',
        ],
        'schedule' => [
            'create' => 'Created the :name schedule',
            'update' => 'Updated the :name schedule',
            'execute' => 'Manually executed the :name schedule',
            'delete' => 'Deleted the :name schedule',
        ],
        'task' => [
            'create' => 'Created a new ":action" task for the :name schedule',
            'update' => 'Updated the ":action" task for the :name schedule',
            'delete' => 'Deleted a task for the :name schedule',
        ],
        'settings' => [
            'rename' => 'Renamed the server from :old to :new',
            'description' => 'Changed the server description from :old to :new',
        ],
        'startup' => [
            'edit' => 'Changed the :variable variable from ":old" to ":new"',
            'image' => 'Updated the Docker Image for the server from :old to :new',
        ],
        'subuser' => [
            'create' => 'Added :email as a subuser',
            'update' => 'Updated the subuser permissions for :email',
            'delete' => 'Removed :email as a subuser',
        ],
    ],
];
