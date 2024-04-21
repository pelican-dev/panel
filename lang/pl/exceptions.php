<?php

return [
    'daemon_connection_failed' => 'Wystąpił wyjątek podczas próby komunikacji z demonem skutkujący kodem odpowiedzi HTTP/:code. Wyjątek ten został zarejestrowany.',
    'node' => [
        'servers_attached' => 'Aby usunąć ten węzeł, nie możesz mieć podłączonych do niego serwerów.',
        'daemon_off_config_updated' => 'Konfiguracja deamona <strong>została zaktualizowana</strong>, jednak wystąpił błąd podczas próby automatycznej aktualizacji pliku konfiguracyjnego deamona. Aby zastosować te zmiany, należy ręcznie zaktualizować plik konfiguracyjny (config.yml).',
    ],
    'allocations' => [
        'server_using' => 'Serwer jest obecnie przypisany do tej alokacji. Alokację można usunąć tylko wtedy, gdy żaden serwer nie jest do niej przypisany.',
        'too_many_ports' => 'Dodawanie więcej niż 1000 portów w jednym zakresie nie jest obsługiwane.',
        'invalid_mapping' => 'Mapowanie podane dla :port było nieprawidłowe i nie mogło zostać przetworzone.',
        'cidr_out_of_range' => 'Notacja CIDR dopuszcza tylko maski od /25 do /32.',
        'port_out_of_range' => 'Porty w alokacji muszą być większe niż 1024 i mniejsze lub równe 65535.',
    ],
    'egg' => [
        'delete_has_servers' => 'Jajo z aktywnymi serwerami przypisanymi do niego nie może zostać usunięte z Panelu.',
        'invalid_copy_id' => 'Jajo wybrane do skopiowania skryptu albo nie istnieje, albo samo posiada kopię skryptu.',
        'has_children' => 'Jajo jest nadrzędne dla jednego lub więcej innych jajek. Proszę najpierw usunąć te jajka przed usunięciem tego.',
    ],
    'variables' => [
        'env_not_unique' => 'Zmienna środowiskowa :name musi być unikalna dla tego jajka.',
        'reserved_name' => 'Zmienna środowiskowa :name jest chroniona i nie może być przypisana do zmiennej.',
        'bad_validation_rule' => 'Reguła walidacji ":rule" nie jest prawidłową regułą dla tej aplikacji.',
    ],
    'importer' => [
        'json_error' => 'Wystąpił błąd podczas próby analizy pliku JSON: :error',
        'file_error' => 'Podany plik JSON jest nieprawidłowy.',
        'invalid_json_provided' => 'Podany plik JSON nie jest w formacie, który może być rozpoznany.',
    ],
    'subusers' => [
        'editing_self' => 'Edytowanie własnego konta podużytkownika jest niedozwolone.',
        'user_is_owner' => 'Nie można dodać właściciela serwera jako podużytkownika tego serwera.',
        'subuser_exists' => 'Użytkownik z tym adresem e-mail jest już przypisany jako podużytkownik dla tego serwera.',
    ],
    'databases' => [
        'delete_has_databases' => 'Nie można usunąć serwera hosta bazy danych, z którym powiązane są aktywne bazy danych.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'Maksymalny odstęp czasu dla zadania, które zostało zablokowane wynosi 15 minut.',
    ],
    'locations' => [
        'has_nodes' => 'Nie można usunąć lokalizacji, do której dołączone są aktywne węzły.',
    ],
    'users' => [
        'node_revocation_failed' => 'Nie udało się odwołać kluczy na <a href=":link">węźle #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'Nie znaleziono węzłów spełniających wymagania dla automatycznego wdrażania.',
        'no_viable_allocations' => 'Nie znaleziono portów spełniających wymagania dla automatycznego wdrażania.',
    ],
    'api' => [
        'resource_not_found' => 'Żądany zasób nie istnieje na tym serwerze.',
    ],
];
