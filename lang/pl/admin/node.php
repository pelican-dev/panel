<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Podany adres FQDN lub IP nie jest poprawnym adresem IP.',
        'fqdn_required_for_ssl' => 'Aby używać SSL dla tego węzła, wymagana jest pełna nazwa domeny, która nawiązuje do publicznego adresu IP.',
    ],
    'notices' => [
        'allocations_added' => 'Pomyślnie dodano alokacje do tego węzła.',
        'node_deleted' => 'Pomyślnie usunięto węzeł z panelu.',
        'node_created' => 'Pomyślnie utworzono nowy węzeł. Możesz automatycznie skonfigurować demona na tej maszynie, odwiedzając zakładkę \'Konfiguracja\'. <strong>Przed dodaniem serwerów musisz najpierw przydzielić co najmniej jeden adres IP i port.</strong>',
        'node_updated' => 'Informacje o węźle zostały zaktualizowane. Jeśli jakiekolwiek ustawienia demona zostały zmienione, konieczne będzie jego ponowne uruchomienie, aby te zmiany zaczęły obowiązywać',
        'unallocated_deleted' => 'Usunięto wszystkie nieprzydzielone porty dla <code>:ip</code>',
    ],
];
