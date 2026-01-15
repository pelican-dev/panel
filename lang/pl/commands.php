<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Podaj adres e-mail, z którego powinny być eksportowane jaja przez ten panel. To powinien być poprawny adres e-mail.',
            'url' => 'Adres URL aplikacji MUSI zaczynać się od https:// lub http:// w zależności od tego, czy używasz SSL, czy nie. Jeśli nie zastosujesz tego schematu, adresy e-mail i inne treści będą linkować do złej lokalizacji.',
            'timezone' => 'Strefa czasowa powinna odpowiadać jednej z obsługiwanych stref czasowych PHP. Jeśli nie jesteś pewien, sprawdź stronę https://php.net/manual/en/timezones.php.',
        ],
        'redis' => [
            'note' => 'Wybrałeś sterownik Redis dla jednej lub więcej opcji, podaj poniżej poprawne informacje o połączeniu. W większości przypadków możesz użyć domyślnych ustawień chyba że zmodyfikowałeś ustawienia.',
            'comment' => 'Domyślnie instancja serwera Redis ma nazwę użytkownika „default” i nie posiada hasła, ponieważ działa lokalnie i jest niedostępna z zewnątrz. Jeśli tak jest, po prostu naciśnij Enter bez wpisywania wartości.',
            'confirm' => 'Wygląda na to, że :field jest już zdefiniowane dla Redis. Czy chcesz to zmienić?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Zaleca się, aby nie używać „localhost” jako hosta bazy danych, ponieważ często występują problemy z połączeniami gniazdowymi. Jeśli chcesz używać połączenia lokalnego, powinieneś użyć „127.0.0.1”.',
        'DB_USERNAME_note' => 'Używanie konta root do połączeń z MySQL jest nie tylko zdecydowanie odradzane, ale także niedozwolone przez tę aplikację. Musisz utworzyć użytkownika MySQL dla tego oprogramowania.',
        'DB_PASSWORD_note' => 'Wygląda na to, że masz już zdefiniowane hasło do połączenia z MySQL. Czy chcesz je zmienić?',
        'DB_error_2' => 'Twoje dane logowania nie zostały zapisane. Będziesz musiał podać prawidłowe informacje o połączeniu przed kontynuowaniem.',
        'go_back' => 'Wróć i spróbuj ponownie',
    ],
    'make_node' => [
        'name' => 'Wprowadź krótki identyfikator używany do odróżnienia tego węzła od innych.',
        'description' => 'Wprowadź opis identyfikujący węzeł',
        'scheme' => 'Proszę wprowadzić https dla połączenia SSL lub http dla połączenia bez SSL.',
        'fqdn' => 'Wprowadź nazwę domeny (np. node.example.com), która będzie używana do łączenia się z daemon\'em. Adres IP może być użyty tylko wtedy, gdy nie używasz SSL dla tego węzła.',
        'public' => 'Czy ten węzeł ma być publiczny? Zauważ, że ustawienie węzła jako prywatnego spowoduje uniemożliwienie automatycznego wdrażania na tym węźle.',
        'behind_proxy' => 'Czy Twój FQDN znajduje się za serwerem proxy?',
        'maintenance_mode' => 'Czy tryb konserwacji powinien być włączony?',
        'memory' => 'Wprowadź maksymalną ilość pamięci',
        'memory_overallocate' => 'Wprowadź ilość pamięci, którą chcesz przypisać; -1 wyłączy sprawdzanie, a 0 uniemożliwi tworzenie nowych serwerów.',
        'disk' => 'Wprowadź maksymalną ilość przestrzeni dyskowej',
        'disk_overallocate' => 'Wprowadź ilość przestrzeni dyskowej, którą chcesz przypisać; -1 wyłączy sprawdzanie, a 0 uniemożliwi tworzenie nowych serwerów.',
        'cpu' => 'Wprowadź maksymalną ilość cpu',
        'cpu_overallocate' => 'Wprowadź ilość cpu, którą chcesz przypisać; -1 wyłączy sprawdzanie, a 0 uniemożliwi tworzenie nowych serwerów.',
        'upload_size' => 'Wprowadź maksymalny rozmiar pliku do przesłania',
        'daemonListen' => 'Wprowadź port, na którym nasłuchuje daemon',
        'daemonConnect' => 'Podaj port łączenia daemon (może być taki sam jak port nasłuchu).',
        'daemonSFTP' => 'Wprowadź port nasłuchujący daemona SFTP',
        'daemonSFTPAlias' => 'Wprowadź alias SFTP dla daemon\'a (może być pusty)',
        'daemonBase' => 'Wprowadź folder bazowy',
        'success' => 'Pomyślnie utworzono nowy węzeł o nazwie :name i identyfikatorze :id',
    ],
    'node_config' => [
        'error_not_exist' => 'Wybrany węzeł nie istnieje.',
        'error_invalid_format' => 'Podany format jest nieprawidłowy. Dostępne opcje to yaml i json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Wygląda na to, że już skonfigurowałeś klucz szyfrowania aplikacji. Kontynuowanie tego procesu spowoduje nadpisanie tego klucza i może doprowadzić do uszkodzenia danych, które zostały już zaszyfrowane. NIE KONTYNUUJ, JEŚLI NIE WIESZ, CO ROBISZ.',
        'understand' => 'Rozumiem konsekwencje wykonania tego polecenia i akceptuję całą odpowiedzialność za utratę zaszyfrowanych danych.',
        'continue' => 'Czy na pewno chcesz kontynuować? Zmiana klucza szyfrowania aplikacji SPOWODUJE UTRATĘ DANYCH.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Nie ma zaplanowanych zadań dla serwerów, które muszą zostać wykonane.',
            'error_message' => 'Wystąpił błąd podczas przetwarzania harmonogramu: ',
        ],
    ],
];
