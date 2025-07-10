<?php

return [
    'nav_title' => 'Database Host',
    'model_label' => 'Database Host',
    'model_label_plural' => 'Database servere',
    'table' => [
        'database' => 'Database',
        'name' => 'Navn',
        'host' => 'Host',
        'port' => 'Port',
        'name_helper' => 'Efterlades dette tomt vil der automatisk genereres et tilfældigt navn',
        'username' => 'Brugernavn',
        'password' => 'Adgangskode',
        'remote' => 'Forbindelser fra',
        'remote_helper' => 'Hvor forbindelser skal tillades fra. Efterlad blank for at tillade forbindelser fra hvor som helst.',
        'max_connections' => 'Maks. forbindelser',
        'created_at' => 'Oprettet den',
        'connection_string' => 'JDBC-forbindelsesstreng',
    ],
    'error' => 'Fejl ved tilslutning til serveren',
    'host' => 'Host',
    'host_help' => 'IP-adressen eller domænenavnet, der skal bruges for at oprette forbindelse til denne MySQL server fra panelet for at oprette nye databaser.',
    'port' => 'Port',
    'port_help' => 'Porten som MySQL kører på ved denne host.',
    'max_database' => 'Maks. databaser',
    'max_databases_help' => 'Det maksimale antal databaser, som kan oprettes på denne server. Nås kvoten, kan der ikke oprettes flere databaser. Lad stå tomt for ubegrænset.',
    'display_name' => 'Visningsnavn',
    'display_name_help' => 'En kort identifikation brugt til at adskille denne vært fra andre. Skal være mellem 1 og 60 tegn, f.eks. "us.nyc.lvl3"',
    'username' => 'Brugernavn',
    'username_help' => 'Brugernavnet på en konto med tilstrækkelige tilladelser til at oprette nye brugere og databaser på systemet.',
    'password' => 'Adgangskode',
    'password_help' => 'Adgangskoden til databasebrugeren.',
    'linked_nodes' => 'Linket Nodes',
    'linked_nodes_help' => 'Denne indstilling er kun standard for denne database host, når der føjes en database til en server på den valgte node.',
    'connection_error' => 'Fejl under tilslutning til database serveren',
    'no_database_hosts' => 'Ingen database servere',
    'no_nodes' => 'Ingen noder',
    'delete_help' => 'Database serveren har stadig databaser',
    'unlimited' => 'Ubegrænset',
    'anywhere' => 'Hvor som helst',

    'rotate' => 'Rotation',
    'rotate_password' => 'Roter Adgangskode',
    'rotated' => 'Adgangskode skiftet',
    'rotate_error' => 'Adgangskoderotation mislykkedes',
    'databases' => 'Databaser',

    'setup' => [
        'preparations' => 'Forberedelser',
        'database_setup' => 'Database Opsætning',
        'panel_setup' => 'Panel Opsætning',

        'note' => 'I øjeblikket understøttes kun MySQL/MariaDB databaser som databaseværter!',
        'different_server' => 'Er panelet og databasen <i>ikke</i> på samme server?',

        'database_user' => 'Databasebruger',
        'cli_login' => 'Brug <code>mysql -u root -p</code> for at tilgå mysql cli.',
        'command_create_user' => 'Kommando til at oprette brugeren',
        'command_assign_permissions' => 'Kommando til at tildele rettigheder',
        'cli_exit' => 'For at afslutte mysql cli, kør <code>exit</code>.',
        'external_access' => 'Ekstern Adgang',
        'allow_external_access' => '
                                    <p>Det er sandsynligt, at du skal tillade ekstern adgang til denne MySQL-instans for at lade servere oprette forbindelse til den.</p>
                                    <br>
                                    <p>For at gøre dette skal du åbne <code>my.cnf</code>, hvis placering varierer afhængigt af dit operativsystem og hvordan MySQL blev installeret. Du kan bruge kommandoen find <code>/etc -iname my.cnf</code> for at finde den.</p>
                                    <br>
                                    <p>Åbn <code>my.cnf</code>, tilføj følgende tekst nederst i filen og gem den:<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>Genstart MySQL/MariaDB for at anvende ændringerne. Dette vil overskrive standardkonfigurationen, som udgangspunkt kun accepterer forbindelser fra localhost. Ved at opdatere dette tillades forbindelser fra alle netværksgrænseflader – altså også eksterne forbindelser. Husk også at åbne MySQL-porten (standard: 3306) i din firewall.</p>
                                ',
    ],
];
