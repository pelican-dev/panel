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
        'reset-password' => 'Wachtwoord reset aangevraagd',
        'checkpoint' => 'Tweestapsverificatie aangevraagd',
        'recovery-token' => 'Token voor tweestapsverificatie herstel gebruikt',
        'token' => 'Tweestapsverificatie voltooid',
        'ip-blocked' => 'Geblokkeerd verzoek van niet in de lijst opgenomen IP-adres voor :identifier',
        'sftp' => [
            'fail' => 'Mislukte SFTP login',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'E-mailadres gewijzigd van :old naar :new',
            'password-changed' => 'Wachtwoord gewijzigd',
        ],
        'api-key' => [
            'create' => 'Nieuwe API-sleutel aangemaakt :identifier',
            'delete' => 'API-sleutel verwijderd :identifier',
        ],
        'ssh-key' => [
            'create' => 'SSH sleutel :fingerprint aan account toegevoegd',
            'delete' => 'SSH sleutel :fingerprint verwijderd van account',
        ],
        'two-factor' => [
            'create' => 'Tweestapsverificatie ingeschakeld',
            'delete' => 'Tweestapsverificatie uitgeschakeld',
        ],
    ],
    'server' => [
        'reinstall' => 'Opnieuw geinstalleerde server',
        'console' => [
            'command' => '":command" is uitgevoerd op de server',
        ],
        'power' => [
            'start' => 'De server is gestart',
            'stop' => 'De server is gestopt',
            'restart' => 'De server is herstart',
            'kill' => 'De server is gekilled',
        ],
        'backup' => [
            'download' => ':name back-up is gedownload',
            'delete' => 'De :name back-up verwijderd',
            'restore' => 'De :name back-up hersteld (verwijderde bestanden: :truncate)',
            'restore-complete' => 'Herstel van de :name back-up voltooid',
            'restore-failed' => 'Gefaald om de backup :name te herstellen',
            'start' => 'Het maken van backup :name is gestart',
            'complete' => 'De back-up :name gemarkeerd als voltooid',
            'fail' => 'De backup :name has failed',
            'lock' => 'Backup :name vergrendeld',
            'unlock' => 'Backup :name ontgrendeld',
        ],
        'database' => [
            'create' => 'Database :name gemaakt',
            'rotate-password' => 'Wachtwoord geroteerd voor database :name',
            'delete' => 'Database :name verwijderd',
        ],
        'file' => [
            'compress_one' => 'Gecomprimeerd :directory:bestand',
            'compress_other' => 'Gecomprimeerd :count bestanden in :directory',
            'read' => 'De inhoud van :file is bekeken',
            'copy' => 'Kopie gemaakt van :file',
            'create-directory' => 'Map :directory:name aangemaakt',
            'decompress' => 'Uitgepakt :files in :directory',
            'delete_one' => 'Verwijderd :directory:files.0',
            'delete_other' => 'Verwijderde :count bestanden in :directory',
            'download' => ':file gedownload',
            'pull' => 'Een extern bestand gedownload van :url naar :directory',
            'rename_one' => ':directory:files.0.from naar :directory:files.0.to hernoemd',
            'rename_other' => ':count bestanden in :directory hernoemd',
            'write' => 'Nieuwe inhoud geschreven naar :file',
            'upload' => 'Bestandsupload is gestart',
            'uploaded' => ':directory:file geüpload',
        ],
        'sftp' => [
            'denied' => 'Geblokkeerde SFTP toegang vanwege machtigingen',
            'create_one' => ':files.0 aangemaakt',
            'create_other' => ':count nieuwe bestanden aangemaakt',
            'write_one' => 'De inhoud van :files.0 gewijzigd',
            'write_other' => 'De inhoud van :count bestanden gewijzigd',
            'delete_one' => ':files.0 verwijderd',
            'delete_other' => ':count bestanden verwijderd',
            'create-directory_one' => 'Map :files.0 aangemaakt',
            'create-directory_other' => ':count mappen aangemaakt',
            'rename_one' => ':files.0.from naar :files.0.to hernoemd',
            'rename_other' => ':count bestanden hernoemd of verplaatst',
        ],
        'allocation' => [
            'create' => ':allocation aan de server toegevoegd',
            'notes' => 'De notitie voor :allocation van ":old" is gewijzigd naar ":new"',
            'primary' => ':allocation is als de primaire server toewijzing ingesteld',
            'delete' => ':allocation toewijzing verwijderd',
        ],
        'schedule' => [
            'create' => 'Taak :name aangemaakt',
            'update' => 'Taak :name bewerkt',
            'execute' => 'Taak :name handmatig uitgevoerd',
            'delete' => 'Taak :name verwijderd',
        ],
        'task' => [
            'create' => 'Nieuwe ":action" opdracht aangemaakt voor de taak :name',
            'update' => '":action" opdracht aangepast voor de taak :name',
            'delete' => 'Opdracht verwijderd de taak :name',
        ],
        'settings' => [
            'rename' => 'Server hernoemd van :old naar :new',
            'description' => 'De beschrijving van de server veranderd van :old naar :new',
        ],
        'startup' => [
            'edit' => 'De :variable variabele van ":old" naar ":new" gewijzigd',
            'image' => 'Docker image van de server is aangepast van :old naar :new',
        ],
        'subuser' => [
            'create' => ':email toegevoegd als medegebruiker',
            'update' => 'Permissies van medegebruiker :email gewijzigd',
            'delete' => ':email verwijderd als medegebruiker',
        ],
    ],
];
