<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Anna sähköpostiosoite, josta tämän paneelin viemät munat ovat peräisin. Tämän pitäisi olla voimassa oleva sähköposti osoite.',
            'url' => 'Sovelluksen URL-osoitteen PITÄÄ alkaa https:// tai http:// riippuen siitä, käytätkö SSL:ää vai et. Jos et sisällytä URL-osoitteen alkua sähköpostit ja muu sisältö linkittää väärään sijaintiin.',
            'timezone' => 'Aikavyöhykkeen tulee täsmätä yhteen PHP:n tuetuista aikavyöhykkeistä. Jos et ole varma. ole hyvä ja katso https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Olet valinnut Redis-ajurin yhteen tai useampaan vaihtoehtoon, ole hyvä ja anna kelvolliset yhdistys tiedot alla. Useimmissa tapauksissa voit käyttää oletusarvoja, ellei asetuksiasi ole muutettu.',
            'comment' => 'Oletuksena Redis-palvelimella on käyttäjänimi default eikä salasanaa, koska se toimii paikallisesti eikä ole ulkomaailman saavutettavissa. Tällöin paina vain enteriä syöttämättä arvoa.',
            'confirm' => 'Näyttää siltä, että Redisille on jo määritetty :field, haluatko muuttaa sitä?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'On erittäin suositeltavaa olla käyttämättä "localhost" tietokantapalvelimen isäntänä, koska olemme havainneet usein soketiyhteysongelmia. Jos haluat käyttää paikallista yhteyttä, sinun tulisi käyttää "127.0.0.1".',
        'DB_USERNAME_note' => 'MySQL-yhteyksien käyttö root-tilillä ei ole pelkästään erittäin paheksuttavaa, vaan se on myös kielletty tässä sovelluksessa. Sinun täytyy luoda MySQL-käyttäjä tätä ohjelmistoa varten.',
        'DB_PASSWORD_note' => 'Näyttää siltä, että sinulla on jo MySQL-yhteyssalasana määritetty, haluaisitko muuttaa sen?',
        'DB_error_2' => 'Yhteyden tietoja EI ole tallennettu. Sinun täytyy antaa kelvolliset yhteyden tiedot ennen kuin voit jatkaa.',
        'go_back' => 'Palaa takaisin ja yritä uudelleen',
    ],
    'make_node' => [
        'name' => 'Anna lyhyt tunniste, jolla erotat tämän solmun muista',
        'description' => 'Anna kuvaus solmun tunnistamiseksi',
        'scheme' => 'Ole hyvä ja syötä joko https SSL-yhteyttä varten tai http ei-SSL-yhteyttä varten.',
        'fqdn' => 'Syötä verkkotunnus (esim. node.example.com) käytettäväksi daemon-yhteydessä. IP-osoitetta voidaan käyttää vain, jos et käytä SSL:ää tälle solmulle.',
        'public' => 'Tuleeko tämän solmun olla julkinen? Huomaa, että asettamalla solmun yksityiseksi estät automaattisen käyttöönoton tälle solmulle.',
        'behind_proxy' => 'Onko sinun FQDN proxyn takana?',
        'maintenance_mode' => 'Pitäisikö huoltotilan olla päällä?',
        'memory' => 'Anna muistin enimmäismäärä',
        'memory_overallocate' => 'Syötä muistin ylikäytön määrä, -1 poistaa tarkistuksen käytöstä ja 0 estää uusien palvelimien luomisen.',
        'disk' => 'Anna levytilan enimmäismäärä',
        'disk_overallocate' => 'Syötä levytilan yliallokointi; arvo -1 ohittaa tarkistuksen ja 0 estää uuden palvelimen luonnin.',
        'cpu' => 'Anna enimmäis-CPU-määrä',
        'cpu_overallocate' => 'Anna CPU:n yliallokoinnin määrä; -1 poistaa tarkistuksen ja 0 estää uusien palvelimien luomisen.',
        'upload_size' => 'Syötä tiedoston maksimi lähetyskoko',
        'daemonListen' => 'Syötä daemonin kuuntelemisportti',
        'daemonConnect' => 'Syötä daemonin yhteysportti (voi olla sama kuin kuunteluportti)',
        'daemonSFTP' => 'Syötä daemonin SFTP kuuntelemisportti',
        'daemonSFTPAlias' => 'Syötä daemonin SFTP-alias (voi jättää tyhjäksi)',
        'daemonBase' => 'Anna peruskansio',
        'success' => 'Solmu :name luotiin onnistuneesti, sen tunnus on :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Valittua solmua ei ole olemassa.',
        'error_invalid_format' => 'Virheellinen muoto. Sallitut vaihtoehdot ovat yaml ja json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Näyttää siltä, että olet jo määrittänyt sovelluksen salausavaimen. Jatkamalla tätä prosessia kirjoitat yli kyseisen avaimen ja aiheutat tietojen korruptoitumisen kaikille olemassa oleville salatuille tiedoille. ÄLÄ JATKA ELLEI TIETÄMÄSI MITÄ TEET.',
        'understand' => 'Ymmärrän tämän komennon suorittamisen seuraukset ja hyväksyn kaiken vastuun salatun datan menetyksestä.',
        'continue' => 'Oletko varma, että haluat jatkaa? Sovelluksen salausavaimen muuttaminen AIHEUTTAA TIETOJEN MENETYKSEN.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Palvelimille ei ole ajoitettuja tehtäviä, jotka olisi suoritettava.',
            'error_message' => 'Aikataulua käsiteltäessä tapahtui virhe: ',
        ],
    ],
];
