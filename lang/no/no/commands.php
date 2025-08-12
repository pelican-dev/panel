<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Oppgi e-postadressen som egg eksportert av dette panelet skal komme fra. Dette må være en gyldig e-postadresse.',
            'url' => 'Applikasjons-URL-en MÅ begynne med https:// eller http:// avhengig av om du bruker SSL eller ikke. Hvis du ikke inkluderer skjemaet, vil e-poster og annet innhold lenke til feil sted.',
            'timezone' => "Tidssonen må samsvare med en av PHP\\'s støttede tidssoner. Hvis du er usikker, se https://php.net/manual/en/timezones.php.",
        ],
        'redis' => [
            'note' => 'Du har valgt Redis-driveren for ett eller flere alternativer, vennligst oppgi gyldig tilkoblingsinformasjon nedenfor. I de fleste tilfeller kan du bruke standardverdiene med mindre du har endret oppsettet ditt.',
            'comment' => 'Som standard har en Redis-serverinstans brukernavn som "default" og ikke noe passord, siden den kjører lokalt og er utilgjengelig fra omverdenen. Hvis dette er tilfelle, trykk bare enter uten å skrive inn en verdi.',
            'confirm' => 'Det ser ut til at en :field allerede er definert for Redis. Vil du endre den?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Det anbefales sterkt å ikke bruke "localhost" som databasevert, da vi ofte ser tilkoblingsproblemer med sockets. Hvis du vil bruke en lokal tilkobling, bør du bruke "127.0.0.1".',
        'DB_USERNAME_note' => 'Å bruke root-kontoen for MySQL-tilkoblinger er ikke bare sterkt frarådet, det er heller ikke tillatt av denne applikasjonen. Du må opprette en MySQL-bruker for denne programvaren.',
        'DB_PASSWORD_note' => 'Det ser ut til at du allerede har definert et MySQL-tilkoblingspassord. Vil du endre det?',
        'DB_error_2' => 'Tilkoblingsinformasjonen din er IKKE lagret. Du må oppgi gyldig tilkoblingsinformasjon før du fortsetter.',
        'go_back' => 'Gå tilbake og prøv igjen',
    ],
    'make_node' => [
        'name' => 'Skriv inn en kort identifikator for å skille denne noden fra andre',
        'description' => 'Skriv inn en beskrivelse for å identifisere noden',
        'scheme' => 'Skriv inn "https" for SSL eller "http" for en ikke-SSL-tilkobling',
        'fqdn' => 'Skriv inn et domenenavn (f.eks. node.example.com) for tilkobling til daemonen. En IP-adresse kan kun brukes hvis du ikke bruker SSL for denne noden.',
        'public' => 'Skal denne noden være offentlig? Hvis en node er privat, vil den ikke være tilgjengelig for automatisk distribusjon.',
        'behind_proxy' => 'Er din FQDN bak en proxy?',
        'maintenance_mode' => 'Skal vedlikeholdsmodus aktiveres?',
        'memory' => 'Skriv inn maksimal mengde minne',
        'memory_overallocate' => 'Skriv inn mengden minne for overallokering. "-1" deaktiverer sjekking, og "0" forhindrer oppretting av nye servere.',
        'disk' => 'Skriv inn maksimal mengde diskplass',
        'disk_overallocate' => 'Skriv inn mengden diskplass for overallokering. "-1" deaktiverer sjekking, og "0" forhindrer oppretting av nye servere.',
        'cpu' => 'Skriv inn maksimal mengde CPU',
        'cpu_overallocate' => 'Skriv inn mengden CPU for overallokering. "-1" deaktiverer sjekking, og "0" forhindrer oppretting av nye servere.',
        'upload_size' => 'Skriv inn maksimal filopplastingsstørrelse',
        'daemonListen' => 'Skriv inn daemonens lytteport',
        'daemonSFTP' => 'Skriv inn daemonens SFTP-lytteport',
        'daemonSFTPAlias' => 'Skriv inn daemonens SFTP-alias (kan være tomt)',
        'daemonBase' => 'Skriv inn grunnmappen',
        'success' => 'Opprettet en ny node med navnet :name og ID-en :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Den valgte noden eksisterer ikke.',
        'error_invalid_format' => 'Ugyldig format spesifisert. Gyldige alternativer er "yaml" og "json".',
    ],
    'key_generate' => [
        'error_already_exist' => 'Det ser ut til at du allerede har konfigurert en krypteringsnøkkel for applikasjonen. Hvis du fortsetter, vil den eksisterende nøkkelen bli overskrevet og føre til datakorrupsjon. IKKE FORTSETT MED MINDRE DU VET HVA DU GJØR.',
        'understand' => 'Jeg forstår konsekvensene av denne kommandoen og tar fullt ansvar for tap av kryptert data.',
        'continue' => 'Er du sikker på at du vil fortsette? Endring av applikasjonens krypteringsnøkkel VIL FØRE TIL TAP AV DATA.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Det er ingen planlagte oppgaver for servere som må kjøres.',
            'error_message' => 'En feil oppstod under behandling av tidsplanen: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Denne kommandoen verifiserer ikke integriteten til nedlastede filer. Sørg for at du stoler på nedlastingskilden før du fortsetter. Hvis du ikke vil laste ned en arkivfil, bruk flagget "--skip-download" eller svar "nei" på spørsmålet nedenfor.',
        'source_url' => 'Nedlastingskilde (sett med --url=):',
        'php_version' => 'Kan ikke utføre selvoppgradering. Minimumskravet til PHP-versjon er 7.4.0, men du har',
        'skipDownload' => 'Vil du laste ned og pakke ut arkivfilene for den nyeste versjonen?',
        'webserver_user' => 'Webserverbrukeren din er registrert som <fg=blue>[{:user}]:</>. Er dette riktig?',
        'name_webserver' => 'Skriv inn navnet på brukeren som kjører webserverprosessen din. Dette varierer mellom systemer, men er vanligvis "www-data", "nginx" eller "apache".',
        'group_webserver' => 'Webservergruppen din er registrert som <fg=blue>[{:group}]:</>. Er dette riktig?',
        'group_webserver_question' => 'Skriv inn navnet på gruppen som kjører webserverprosessen din. Dette er vanligvis den samme som brukeren din.',
        'are_your_sure' => 'Er du sikker på at du vil kjøre oppgraderingsprosessen for panelet ditt?',
        'terminated' => 'Oppgraderingsprosessen ble avbrutt av brukeren.',
        'success' => 'Panelet har blitt vellykket oppgradert. Sørg for å også oppdatere eventuelle Daemon-installasjoner.',

    ],
];
