<?php

return [
    'daemon_connection_failed' => 'Wystąpił wyjątek podczas próby komunikacji z daemon\'em skutkujący kodem odpowiedzi HTTP/:code. Wyjątek ten został zarejestrowany.',
    'node' => [
        'servers_attached' => 'Aby węzeł mógł zostać usunięty, nie może być z nim powiązany żaden serwer.',
        'error_connecting' => 'Błąd połączenia z węzłem :node',
        'daemon_off_config_updated' => 'Konfiguracja daemon\'a <strong>została zaktualizowana</strong>, jednak wystąpił błąd podczas próby automatycznej aktualizacji pliku konfiguracyjnego daemon\'a. Będziesz musiał ręcznie zaktualizować plik konfiguracyjny (config.yml) dla daemon\'a, aby zastosować te zmiany.',
    ],
    'allocations' => [
        'server_using' => 'Serwer jest aktualnie przypisany do tej alokacji. Alokacje można usunąć tylko wtedy, gdy żaden serwer nie jest aktualnie przypisany.',
        'too_many_ports' => 'Dodanie więcej niż 1000 portów w jednym zakresie jednocześnie nie jest obsługiwane.',
        'invalid_mapping' => 'Mapowanie dla portu :port było nieprawidłowe i nie mogło zostać przetworzone.',
        'cidr_out_of_range' => 'Notacja CIDR dopuszcza tylko maski od /25 do /32.',
        'port_out_of_range' => 'Porty w alokacji muszą być większe lub równe 1024 i mniejsze lub równe 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Jajo z aktywnymi serwerami nie może zostać usunięte z panelu.',
        'invalid_copy_id' => 'Jajo wybrane do skopiowania skryptu albo nie istnieje, albo kopiuje sam skrypt.',
        'has_children' => 'To jajo jest rodzicem jednego lub więcej innych jaj. Usuń te jaja przed usunięciem tego jaja.',
    ],
    'variables' => [
        'env_not_unique' => 'Zmienna środowiskowa :name musi być unikalna dla tego Egg.',
        'reserved_name' => 'Zmienna środowiskowa :name jest chroniona i nie może być przypisana do zmiennej.',
        'bad_validation_rule' => 'Reguła walidacji ":rule” nie jest prawidłową regułą dla tej aplikacji.',
    ],
    'importer' => [
        'json_error' => 'Wystąpił błąd podczas próby przeanalizowania pliku JSON: :error....',
        'file_error' => 'Podany plik JSON był nieprawidłowy.',
        'invalid_json_provided' => 'Dostarczony plik JSON nie jest w formacie, który można rozpoznać.',
    ],
    'subusers' => [
        'editing_self' => 'Edycja własnego konta subużytkownika jest niedozwolona.',
        'user_is_owner' => 'Nie można dodać właściciela serwera jako subużytkownika tego serwera.',
        'subuser_exists' => 'Użytkownik o tym adresie e-mail jest już przypisany jako subużytkownik tego serwera.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nie można usunąć serwera hosta bazy danych, z którym powiązane są aktywne bazy danych.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Maksymalny czas interwału dla zadania powiązanego wynosi 15 minut.',
    ],
    'locations' => [
        'has_nodes' => 'Nie można usunąć lokalizacji, do której dołączone są aktywne węzły.',
    ],
    'users' => [
        'is_self' => 'Nie można usunąć swojego konta użytkownika.',
        'has_servers' => 'Nie można usunąć użytkownika, który ma przypisane aktywne serwery. Przed kontynuowaniem usuń przypisane serwery.',
        'node_revocation_failed' => 'Nie udało się cofnąć kluczy na <a href=":link">Węźle #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nie znaleziono węzłów spełniających wymagania określone dla automatycznego wdrożenia.',
        'no_viable_allocations' => 'Nie znaleziono alokacji spełniających wymagania dla automatycznego wdrożenia.',
    ],
    'api' => [
        'resource_not_found' => 'Żądany zasób nie istnieje na tym serwerze.',
    ],
    'mount' => [
        'servers_attached' => 'Aby usunąć punkt montowania, nie mogą być do niego przypisane żadne serwery.',
    ],
    'server' => [
        'marked_as_failed' => 'Ten serwer nie zakończył jeszcze procesu instalacji, proszę spróbować ponownie później.',
    ],
];
