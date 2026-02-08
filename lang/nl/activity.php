<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Inloggen mislukt',
        'success' => 'Ingelogd',
        'password-reset' => 'Wachtwoord resetten',
        'checkpoint' => 'Tweestapsverificatie aangevraagd',
        'recovery-token' => 'Token voor tweestapsverificatie herstel gebruikt',
        'token' => 'Tweestapsverificatie voltooid',
        'ip-blocked' => 'Geblokkeerd verzoek van niet in de lijst opgenomen IP-adres voor <b>:identifier:</b>',
        'sftp' => [
            'fail' => 'Mislukte SFTP-login',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Gewijzigde e-mail van <b>:old</b> naar <b>:new</b>',
            'email-changed' => 'Gewijzigde e-mail van <b>:old</b> naar <b>:new</b>',
            'password-changed' => 'Wachtwoord gewijzigd',
        ],
        'api-key' => [
            'create' => 'Nieuwe API-sleutel aangemaakt :identifier: ',
            'delete' => 'API-sleutel verwijderd <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'SSH sleutel <b>:fingerprint</b> aan account toegevoegd',
            'delete' => 'SSH sleutel <b>:fingerprint</b> verwijderd van account',
        ],
        'two-factor' => [
            'create' => 'Tweestapsverificatie ingeschakeld',
            'delete' => 'Tweestapsverificatie uitgeschakeld',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '"<b>:command</b>" is uitgevoerd op de server',
        ],
        'power' => [
            'start' => 'De server is gestart',
            'stop' => 'De server is gestopt',
            'restart' => 'De server is herstart',
            'kill' => 'De server is gekilled',
        ],
        'backup' => [
            'download' => '<b>:name</b> back-up is gedownload',
            'delete' => 'De :name back-up verwijderd',
            'restore' => 'De :name back-up hersteld (verwijderde bestanden: :truncate)',
            'restore-complete' => 'Herstel van de :name back-up voltooid',
            'restore-failed' => 'Gefaald om de backup :name te herstellen',
            'start' => 'Het maken van backup :name is gestart',
            'complete' => 'De back-up :name gemarkeerd als voltooid',
            'fail' => 'De backup :name has failed',
            'lock' => 'Backup :name vergrendeld',
            'unlock' => 'Backup :name ontgrendeld',
            'rename' => 'Back-up hernoemd van "<b>:old_name</b>" naar "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Nieuwe Database  gemaakt',
            'rotate-password' => 'Wachtwoord geroteerd voor database ',
            'delete' => 'Database verwijderd',
        ],
        'file' => [
            'compress' => 'Gecomprimeerd <b>:directory:files</b>|Gecomprimeerd <b>:</b> bestanden in',
            'read' => 'De inhoud van is bekeken',
            'copy' => 'Kopie gemaakt van :file',
            'create-directory' => 'Map :directory:name aangemaakt',
            'decompress' => 'Uitgepakt <b>:file</b> in <b>:directory</b>',
            'delete' => 'Verwijderd <b>:directory:files</b>|Verwijderd <b>:count</b> bestanden in <b>:directory</b>',
            'download' => 'Gedownload <b>:file</b>',
            'pull' => 'Een extern bestand gedownload van :url naar :directory',
            'rename' => 'Verplaatsen/hernoemd <b>:from</b> naar <b>:to</b>|Verplaatst/ Hernoemd <b>:count</b> bestanden in <b>:directory</b>',
            'write' => 'Nieuwe inhoud geschreven naar <b>:file</b>',
            'upload' => 'Bestandsupload is gestart',
            'uploaded' => 'Geüpload <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'SFTP-toegang geblokkeerd vanwege machtigingen',
            'create' => 'Aangemaakte <b>:files</b>|Aangemaakte <b>:count</b> nieuwe bestanden',
            'write' => 'De inhoud van <b>:files</b> gewijzigd | De inhoud van <b>:count</b> bestanden gewijzigd',
            'delete' => 'Verwijderde <b>:files</b>|Verwijderde <b>:count</b> bestanden',
            'create-directory' => 'De map <b>:files</b> aangemaakt|<b>:count</b> mappen aangemaakt',
            'rename' => 'Hernoemd <b>:from</b> naar <b>:to</b>|Hernoemd of verplaatst <b>:count</b> bestanden',
        ],
        'allocation' => [
            'create' => '<b>:allocation</b> toegevoegd aan de server',
            'notes' => 'De notities voor <b>:allocation</b> bijgewerkt van "<b>:old</b>" naar "<b>:new</b>"',
            'primary' => '<b>:allocation</b> ingesteld als de primaire servertoewijzing',
            'delete' => 'De toewijzing <b>:allocation</b> verwijderd',
        ],
        'schedule' => [
            'create' => 'Het <b>:name</b>-schema aangemaakt',
            'update' => 'Het <b>:name</b>-schema bijgewerkt',
            'execute' => 'Handmatig het <b>:name</b> schema uitgevoerd',
            'delete' => 'Verwijderde het <b>:name</b> schema',
        ],
        'task' => [
            'create' => 'Een nieuwe <b>:action</b> taak aangemaakt voor het <b>:name</b> schema',
            'update' => 'Bijgewerkte de <b>:action</b> taak voor het <b>:name</b> schema',
            'delete' => 'Verwijderde de "<b>:action</b>" taak voor het <b>:name</b> schema',
        ],
        'settings' => [
            'rename' => 'De server hernoemd van "<b>:old</b>" naar "<b>:new</b>"',
            'description' => 'De serverbeschrijving gewijzigd van "<b>:old</b>" naar "<b>:new</b>"',
            'reinstall' => 'Server opnieuw geïnstalleerd',
        ],
        'startup' => [
            'edit' => 'De <b>:variable</b> variabele gewijzigd van "<b>:old</b>" naar "<b>:new</b>"',
            'image' => 'De Docker-image voor de server bijgewerkt van <b>:old</b> naar <b>:new</b>',
            'command' => 'Het opstartcommando voor de server van <b>:old</b> bijgewerkt naar <b>:new</b>',
        ],
        'subuser' => [
            'create' => '<b>:email</b> toegevoegd als subgebruiker',
            'update' => 'De machtigingen van de subgebruiker bijgewerkt voor <b>:email</b>',
            'delete' => '<b>:email</b> verwijderd als subgebruiker',
        ],
        'crashed' => 'Server gecrasht',
    ],
];
