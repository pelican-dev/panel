<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Chcesz usunąć domyślną alokację dla tego serwera, ale nie ma alternatywnej alokacji do wykorzystania.',
        'marked_as_failed' => 'Ten serwer został zidentyfikowany jako mający nieudaną wcześniejszą instalację. Nie można zmienić jego aktualnego statusu w tej sytuacji.',
        'bad_variable' => 'Wystąpił błąd walidacji zmiennych :name',
        'daemon_exception' => 'Wystąpił błąd podczas próby komunikacji z demonem, co spowodowało kod odpowiedzi HTTP/:code. Ten błąd został zarejestrowany. (ID żądania: :request_id)',
        'default_allocation_not_found' => 'Nie znaleziono żądanej domyślnej alokacji w alokacjach tego serwera.',
    ],
    'alerts' => [
        'startup_changed' => 'Zaktualizowano konfigurację startową tego serwera. Jeśli nastąpiła zmiana jajka dla tego serwera, zostanie przeprowadzona ponowna instalacja w tym momencie.',
        'server_deleted' => 'Serwer został pomyślnie usunięty z systemu.',
        'server_created' => 'Serwer został pomyślnie utworzony w panelu. Proszę poczekać kilka minut, aby demon zakończył instalację tego serwera.',
        'build_updated' => 'Zaktualizowano szczegóły konfiguracji dla tego serwera. Pewne zmiany mogą wymagać ponownego uruchomienia, aby zacząć obowiązywać.',
        'suspension_toggled' => 'Status zawieszenia serwera został zmieniony na :status',
        'rebuild_on_boot' => 'Ten serwer został oznaczony jako wymagający ponownej budowy kontenera Docker. Zostanie to wykonane przy następnym uruchomieniu serwera.',
        'install_toggled' => 'Status instalacji dla tego serwera został zmieniony.',
        'server_reinstalled' => 'Ten serwer został umieszczony w kolejce do ponownej instalacji, która rozpoczyna się w tym momencie.',
        'details_updated' => 'Szczegóły serwera zostały pomyślnie zaktualizowane.',
        'docker_image_updated' => 'Pomyślnie zmieniono domyślny obraz Docker do użycia dla tego serwera. Konieczne jest ponowne uruchomienie, aby zastosować tę zmianę.',
        'node_required' => 'Musisz mieć skonfigurowany co najmniej jeden węzeł, zanim będziesz mógł dodać serwer do tego panelu.',
        'transfer_nodes_required' => 'Musisz mieć skonfigurowanych co najmniej dwa węzły, zanim będziesz mógł przenosić serwery.',
        'transfer_started' => 'Rozpoczęto transfer serwera.',
        'transfer_not_viable' => 'Wybrany węzeł nie ma wystarczającej ilości dostępnej przestrzeni dyskowej ani pamięci, aby pomieścić ten serwer.',
    ],
];
