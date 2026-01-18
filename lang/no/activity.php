<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Mislykket innlogging',
        'success' => 'Logget inn',
        'password-reset' => 'Passord tilbakestilt',
        'checkpoint' => 'Tofaktorautentisering forespurt',
        'recovery-token' => 'Brukte tofaktor gjenopprettingskode',
        'token' => 'Løste tofaktor utfordring',
        'ip-blocked' => 'Blokkerte forespørsel fra ikke-listet IP-adresse for <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Mislykket SFTP-innlogging',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Endret brukernavn fra <b>:old</b> til <b>:new</b>',
            'email-changed' => 'Endret e-post fra <b>:old</b> til <b>:new</b>',
            'password-changed' => 'Endret passord',
        ],
        'api-key' => [
            'create' => 'Opprettet ny API-nøkkel <b>:identifier</b>',
            'delete' => 'Slettet API-nøkkel <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'La til SSH-nøkkel <b>:fingerprint</b> på kontoen',
            'delete' => 'Fjernet SSH-nøkkel <b>:fingerprint</b> fra kontoen',
        ],
        'two-factor' => [
            'create' => 'Aktiverte tofaktorautentisering',
            'delete' => 'Deaktiverte tofaktorautentisering',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Utførte "<b>:command</b>" på serveren',
        ],
        'power' => [
            'start' => 'Startet serveren',
            'stop' => 'Stoppet serveren',
            'restart' => 'Omstartet serveren',
            'kill' => 'Tvunget avslutning av serverprosessen',
        ],
        'backup' => [
            'download' => 'Lastet ned sikkerhetskopi <b>:name</b>',
            'delete' => 'Slettet sikkerhetskopi <b>:name</b>',
            'restore' => 'Gjenopprettet sikkerhetskopi <b>:name</b> (slettede filer: <b>:truncate</b>)',
            'restore-complete' => 'Fullførte gjenoppretting av sikkerhetskopi <b>:name</b>',
            'restore-failed' => 'Kunne ikke fullføre gjenoppretting av sikkerhetskopi <b>:name</b>',
            'start' => 'Startet en ny sikkerhetskopi <b>:name</b>',
            'complete' => 'Merked sikkerhetskopi <b>:name</b> som fullført',
            'fail' => 'Merked sikkerhetskopi <b>:name</b> som mislykket',
            'lock' => 'Låste sikkerhetskopi <b>:name</b>',
            'unlock' => 'Låste opp sikkerhetskopi <b>:name</b>',
            'rename' => 'Omdøpt sikkerhetskopi fra "<b>:old_name</b>" til "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Opprettet ny database <b>:name</b>',
            'rotate-password' => 'Passord rotert for database <b>:name</b>',
            'delete' => 'Slettet database <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Komprimerte <b>:directory:files</b>|Komprimerte <b>:count</b> filer i <b>:directory</b>',
            'read' => 'Viste innholdet i <b>:file</b>',
            'copy' => 'Opprettet en kopi av <b>:file</b>',
            'create-directory' => 'Opprettet mappe <b>:directory:name</b>',
            'decompress' => 'Pakket ut <b>:file</b> i <b>:directory</b>',
            'delete' => 'Slettet <b>:directory:files</b>|Slettet <b>:count</b> filer i <b>:directory</b>',
            'download' => 'Lastet ned <b>:file</b>',
            'pull' => 'Lastet ned en ekstern fil fra <b>:url</b> til <b>:directory</b>',
            'rename' => 'Flyttet/byttet navn på <b>:from</b> til <b>:to</b>|Flyttet/byttet navn på <b>:count</b> filer i <b>:directory</b>',
            'write' => 'Skrev nytt innhold til <b>:file</b>',
            'upload' => 'Startet en filopplasting',
            'uploaded' => 'Lastet opp <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Blokkerte SFTP-tilgang på grunn av manglende tillatelser',
            'create' => 'Opprettet <b>:files</b>|Opprettet <b>:count</b> nye filer',
            'write' => 'Endret innholdet i <b>:files</b>|Endret innholdet i <b>:count</b> filer',
            'delete' => 'Slettet <b>:files</b>|Slettet <b>:count</b> filer',
            'create-directory' => 'Opprettet mappen <b>:files</b>|Opprettet <b>:count</b> mapper',
            'rename' => 'Endret navn fra <b>:from</b> til <b>:to</b>|Endret navn på eller flyttet <b>:count</b> filer',
        ],
        'allocation' => [
            'create' => 'La til <b>:allocation</b> på serveren',
            'notes' => 'Oppdaterte notatene for <b>:allocation</b> fra "<b>:old</b>" til "<b>:new</b>"',
            'primary' => 'Satte <b>:allocation</b> som primær serverallokering',
            'delete' => 'Slettet allokeringen <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'Opprettet tidsplanen <b>:name</b>',
            'update' => 'Oppdaterte tidsplanen <b>:name</b>',
            'execute' => 'Manuelt kjørte tidsplanen <b>:name</b>',
            'delete' => 'Slettet tidsplanen <b>:name</b>',
        ],
        'task' => [
            'create' => 'Opprettet en ny "<b>:action</b>" oppgave for tidsplanen <b>:name</b>',
            'update' => 'Oppdaterte oppgaven "<b>:action</b>" for tidsplanen <b>:name</b>',
            'delete' => 'Slettet "<b>:action</b>" oppgaven for <b>:name</b> tidsplanen',
        ],
        'settings' => [
            'rename' => 'Endret servernavnet fra "<b>:old</b>" til "<b>:new</b>"',
            'description' => 'Endret serverbeskrivelsen fra "<b>:old</b>" til "<b>:new</b>"',
            'reinstall' => 'Reinstallerte server',
        ],
        'startup' => [
            'edit' => 'Endret variabelen <b>:variable</b> fra "<b>:old</b>" til "<b>:new</b>"',
            'image' => 'Oppdaterte Docker-bildet for serveren fra <b>:old</b> til <b>:new</b>',
            'command' => 'Oppdaterte oppstartskommandoen for serveren fra <b>:old</b> til <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'La til <b>:email</b> som underbruker',
            'update' => 'Oppdaterte underbrukerens tillatelser for <b>:email</b>',
            'delete' => 'Fjernet <b>:email</b> som underbruker',
        ],
        'crashed' => 'Serveren krasjet',
    ],
];
