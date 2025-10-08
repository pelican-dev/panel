<?php

return [
    'nav_title' => 'Hostitelia databáz',
    'model_label' => 'Hostiteľ databázy',
    'model_label_plural' => 'Hostitelia databáz',
    'table' => [
        'database' => 'Databáza',
        'name' => 'Meno',
        'host' => 'Hostiteľ',
        'port' => 'Port',
        'name_helper' => 'Ak toto pole necháte prázdne, automaticky sa vygeneruje náhodné meno',
        'username' => 'Používateľské meno',
        'password' => 'Heslo',
        'remote' => 'Pripojenia z',
        'remote_helper' => 'Odkiaľ by mali byť povolené pripojenia. Nechajte prázdne, aby boli povolené pripojenia odkiaľkoľvek.',
        'max_connections' => 'Maximálne pripojenia',
        'created_at' => 'Vytvorené',
        'connection_string' => 'JDBC pripojovací reťazec',
    ],
    'error' => 'Chyba pri pripojovaní k hostiteľovi',
    'host' => 'Hostiteľ',
    'host_help' => 'IP adresa alebo doménové meno, ktoré sa má použiť pri pokuse o pripojenie k tomuto MySQL hostiteľovi z tohto panelu na vytvorenie nových databáz.',
    'port' => 'Port',
    'port_help' => 'Port pre hostiteľa na ktorom beží MySQL.',
    'max_database' => 'Maximálny počet databáz',
    'max_databases_help' => 'Maximálny počet databáz, ktoré je možné vytvoriť na tomto hostiteľovi. Ak je limit dosiahnutý, nové databázy už nebude možné na tomto hostiteľovi vytvárať. Prázdne pole znamená neobmedzený počet.',
    'display_name' => 'Zobrazované Meno',
    'display_name_help' => 'IP adresa alebo názov domény, ktorý by mal byť zobrazený koncovému používateľovi.',
    'username' => 'Používateľské meno',
    'username_help' => 'Používateľské meno účtu, ktorý má dostatočné oprávnenia na vytváranie nových používateľov a databáz v systéme.',
    'password' => 'Heslo',
    'password_help' => 'Heslo pre používateľa databázy.',
    'linked_nodes' => 'Prepojené Uzly',
    'linked_nodes_help' => 'Toto nastavenie predvolene použije tohto hostiteľa databázy, keď sa pridáva databáza na server na vybranom uzle.',
    'connection_error' => 'Chyba pri pripojení k hostiteľovi databázy.',
    'no_database_hosts' => 'Žiadni hostitelia databázy.',
    'no_nodes' => 'Žiadne Uzly',
    'delete_help' => 'Hostiteľ Databázy má Databázy',
    'unlimited' => 'Neobmedzený',
    'anywhere' => 'Kdekoľvek',

    'rotate' => 'Zmeniť',
    'rotate_password' => 'Zmeniť heslo',
    'rotated' => 'Heslo zmenené',
    'rotate_error' => 'Zmena hesla neúspešná',
    'databases' => 'Databázy',

    'setup' => [
        'preparations' => 'Prípravy',
        'database_setup' => 'Nastavenie databázy',
        'panel_setup' => 'Nastavenie panelu',

        'note' => 'Momentálne sú podporované iba MySQL/MariaDB databázy!',
        'different_server' => 'Panel a databáza <i>niesu</i> na tom istom servery?',

        'database_user' => 'Databázový používateľ',
        'cli_login' => 'Na prístup do MySQL CLI použite <code>mysql -u root -p</code>',
        'command_create_user' => 'Príkaz na vytvorenie ďalšieho používateľa',
        'command_assign_permissions' => 'Príkaz na priradenie oprávnení',
        'cli_exit' => 'Na ukončenie MySQL CLI napíšte <code>exit</code>.',
        'external_access' => 'Externý prístup',
        'allow_external_access' => '<p>Je pravdepodobné, že budete musieť povoliť externý prístup k tejto inštancii MySQL, aby sa k nej mohli pripojiť servery.</p>
<br>
<p>Preto otvorte súbor <code>my.cnf</code>, ktorého umiestnenie sa líši v závislosti od vášho operačného systému a spôsobu inštalácie MySQL. Na jeho vyhľadanie môžete zadať príkaz <code>find /etc -iname my.cnf</code></p>.
<br>
<p>Otvorte súbor <code>my.cnf</code>, pridajte nasledujúci text na koniec súboru a uložte ho:<br>
<code>[mysqld]<br>[object Object],bind-address=0.0.0.0</code></p>
<br>
<p>Reštartujte MySQL/MariaDB, aby sa tieto zmeny uplatnili. Týmto sa prepíše predvolená konfigurácia MySQL, ktorá štandardne akceptuje požiadavky iba z localhostu. Aktualizáciou tejto konfigurácie povolíte pripojenia na všetkých rozhraniach, a teda aj externé pripojenia. Uistite sa, že v bráne firewall povolíte port MySQL (predvolene 3306).</p>',
    ],
];
