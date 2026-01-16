<?php

return [
    'daemon_connection_failed' => 'Tapahtui poikkeus, kun yritettiin kommunikoida daemonin kanssa, mikä johti HTTP/:code -vastauskoodiin. Tämä poikkeus on kirjautunut.',
    'node' => [
        'servers_attached' => 'Palvelimella ei saa olla siihen linkitettyjä palvelimia, jotta se voitaisiin poistaa.',
        'error_connecting' => 'Virhe yhdistettäessä solmuun :node',
        'daemon_off_config_updated' => 'Daemon konfiguraatio <strong>on päivitetty</strong>, mutta virhe ilmeni yritettäessä päivittää konfiguraatiota automaattisesti daemoniin. Sinun tulee päivittää daemonin konfiguraatio (config.yml) manuaalisesti, jotta muutokset voidaan ottaa käyttöön.',
    ],
    'allocations' => [
        'server_using' => 'Palvelin on tällä hetkellä määritelty tähän varaukseen. Varauksen voi poistaa vain, jos siihen ei ole tällä hetkellä määritettyä palvelinta.',
        'too_many_ports' => 'Yli 1000 portin lisääminen yhteen alueeseen kerralla ei ole tuettua.',
        'invalid_mapping' => ':port:lle annettu määritys oli virheellinen eikä sitä voitu käsitellä.',
        'cidr_out_of_range' => 'CIDR-muoto sallii vain maskit välillä /25 ja /32.',
        'port_out_of_range' => 'Portit allokaatiossa tulee olla välillä 1024–65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Paneelista ei voi poistaa Munaa, johon on liitetty aktiivisia palvelimia.',
        'invalid_copy_id' => 'Skriptin kopiointiin valittu Muna ei ole olemassa tai se kopioi itse skriptiä.',
        'has_children' => 'Tämä Muna on yhden tai useamman muun Munan vanhempi. Poista Munat ennen tämän Munan poistamista.',
    ],
    'variables' => [
        'env_not_unique' => 'Ympäristömuuttujan :name on oltava yksilöllinen tähän Munaan.',
        'reserved_name' => 'Ympäristömuuttuja :name on suojattu ja sitä ei voi liittää muuttujaan.',
        'bad_validation_rule' => 'Vahvistussääntö ":rule" ei ole kelvollinen sääntö tälle sovellukselle.',
    ],
    'importer' => [
        'json_error' => 'Tapahtui virhe yritettäessä jäsentää JSON tiedostoa: :error.',
        'file_error' => 'Annettu JSON-tiedosto ei ollut kelvollinen.',
        'invalid_json_provided' => 'Annettu JSON tiedosto ei ole muodossa, joka voidaan tunnistaa.',
    ],
    'subusers' => [
        'editing_self' => 'Oman alikäyttäjätilin muokkaaminen ei ole sallittua.',
        'user_is_owner' => 'Et voi lisätä palvelimen omistajaa alikäyttäjäksi tälle palvelimelle.',
        'subuser_exists' => 'Käyttäjä, jolla on tämä sähköpostiosoite, on jo määritetty alikäyttäjäksi tälle palvelimelle.',
    ],
    'databases' => [
        'delete_has_databases' => 'Ei voida poistaa tietokannan isäntäpalvelinta, jossa on siihen linkitettyjä aktiivisia tietokantoja.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Ketjutellun tehtävän aikaväli on enintään 15 minuuttia.',
    ],
    'locations' => [
        'has_nodes' => 'Ei voida poistaa sijaintia, jossa on aktiivisia palvelimia siihen liitettynä.',
    ],
    'users' => [
        'is_self' => 'Et voi poistaa omaa käyttäjätiliäsi.',
        'has_servers' => 'Käyttäjää ei voi poistaa, jos hänen tilillään on aktiivisia palvelimia. Poista palvelimet ennen jatkamista.',
        'node_revocation_failed' => 'Avainten peruuttaminen epäonnistui <a href=":link">Palvelimen #:node</a> kohdalla. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Yhtään vaatimuksia täyttävää palvelinta automaattiseen käyttöönottamiseen ei löytynyt.',
        'no_viable_allocations' => 'Yhtään automaattiseen käyttöönottoon soveltuvaa varausta ei löytynyt.',
    ],
    'api' => [
        'resource_not_found' => 'Pyydettyä resurssia ei ole tällä palvelimella.',
    ],
    'mount' => [
        'servers_attached' => 'Mountin poistaminen edellyttää, ettei siihen ole liitetty palvelimia.',
    ],
    'server' => [
        'marked_as_failed' => 'Palvelimen asennus on kesken, kokeile myöhemmin uudestaan.',
    ],
];
