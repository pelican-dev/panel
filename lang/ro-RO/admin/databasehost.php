<?php

return [
    'nav_title' => 'Gazde Baze de date',
    'model_label' => 'Gazda bazei de date',
    'model_label_plural' => 'Gazde Baze de date',
    'table' => [
        'database' => 'Bază de Date',
        'name' => 'Nume',
        'host' => 'Host',
        'port' => 'Port',
        'name_helper' => 'Lăsând acest câmp gol se va genera automat un nume aleatoriu',
        'username' => 'Nume de utilizator',
        'password' => 'Parolă',
        'remote' => 'Conexiuni de la',
        'remote_helper' => 'Unde conexiunile ar trebui să fie permise. Lăsați necompletat pentru a permite conexiuni de oriunde.',
        'max_connections' => 'Conexiuni maxime',
        'created_at' => 'Creat la',
        'connection_string' => 'Șirul de conexiune JDBC',
    ],
    'error' => 'Eroare de conectare la gazdă',
    'host' => 'Host',
    'host_help' => 'Adresa IP sau numele de domeniu care ar trebui să fie utilizate la conectarea la acest gazdă MySQL din acest Panou pentru a crea noi baze de date.',
    'port' => 'Port',
    'port_help' => 'Portul pe care MySQL rulează pentru această gazdă.',
    'max_database' => 'Maxim baze de date',
    'max_databases_help' => 'Numărul maxim de baze de date care pot fi create pe această gazdă. Dacă limita este atinsă, nu pot fi create noi baze de date pe această gazdă. Lăsând loc liber setați nelimitat.',
    'display_name' => 'Nume afișat',
    'display_name_help' => 'Un identificator scurt folosit pentru a distinge această gazdă de celelalte. Trebuie să fie între 1 și 60 de caractere, de exemplu, us.nyc.lvl3.',
    'username' => 'Nume de utilizator',
    'username_help' => 'Numele de utilizator al unui cont care are suficiente permisiuni pentru a crea noi utilizatori și baze de date în sistem.',
    'password' => 'Parola',
    'password_help' => 'Parola pentru utilizatorul bazei de date.',
    'linked_nodes' => 'Noduri asociate',
    'linked_nodes_help' => 'Această setare este doar cea implicită pentru această bază de date gazdă atunci când se adaugă o bază de date la un server din modulul selectat.',
    'connection_error' => 'Eroare la conectarea la gazda de date',
    'no_database_hosts' => 'Nu sunt gazde baze de date',
    'no_nodes' => 'Nici un Nod',
    'delete_help' => 'Baza de date gazdă are bazele de date',
    'unlimited' => 'Nelimitat',
    'anywhere' => 'Oriunde',

    'rotate' => 'Rotire',
    'rotate_password' => 'Rotește parola',
    'rotated' => 'Parolă rotită',
    'rotate_error' => 'Rotirea parolei a eșuat',
    'databases' => 'Baze de date',

    'setup' => [
        'preparations' => 'Pregătiri',
        'database_setup' => 'Configurare bază de date',
        'panel_setup' => 'Configurare Panou',

        'note' => 'În prezent, sunt suportate doar bazele de date MySQL/MariaDB pentru gazdele bazelor de date!',
        'different_server' => 'Panoul și baza de date <i>NU</i> sunt pe același server?',

        'database_user' => 'Utilizator bază de date',
        'cli_login' => 'Folosește <code>mysql -u root -p</code> pentru a accesa mysql cli.',
        'command_create_user' => 'Comanda pentru a crea utilizatorul',
        'command_assign_permissions' => 'Comanda pentru a atribui permisiuni',
        'cli_exit' => 'Pentru a ieși din mysql cli foloseste comanda <code>exit</code>.',
        'external_access' => 'Acces Extern',
        'allow_external_access' => '
<p>Este posibil să fie necesar să permiți accesul extern la această instanță MySQL pentru a permite serverelor să se conecteze la ea.</p>
                                    <br>
                                    <p>Pentru a face acest lucru, deschide <code>my.cnf</code>, a cărui locație variază în funcție de sistemul de operare și modul în care MySQL a fost instalat. Poți folosi comanda <code>find /etc -iname my.cnf</code> pentru a o localiza.</p>
                                    <br>
                                    <p>Deschide <code>my.cnf</code>, adaugă textul de mai jos la finalul fișierului și salvează:<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>Repornește MySQL/ MariaDB pentru ca aceste modificări să aibă efect. Aceasta va suprascrie configurația implicită MySQL, care în mod normal acceptă cereri doar de la localhost. Actualizarea acesteia va permite conexiuni pe toate interfețele și, implicit, conexiuni externe. Asigură-te că permiți portul MySQL (implicit 3306) în firewall.</p>                                ',
    ],
];
