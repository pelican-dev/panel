<?php

return [
    'notices' => [
        'imported' => 'Pomyślnie zaimportowano to jądro i związane z nim zmienne.',
        'updated_via_import' => 'To jądro zostało zaktualizowane przy użyciu dostarczonego pliku.',
        'deleted' => 'Pomyślnie usunięto żądane jądro z Panelu.',
        'updated' => 'Konfiguracja jądra została pomyślnie zaktualizowana.',
        'script_updated' => 'Skrypt instalacyjny jądra został zaktualizowany i zostanie uruchomiony za każdym razem, gdy serwery zostaną zainstalowane.',
        'egg_created' => 'Nowe jądro zostało dodane. Musisz zrestartować wszystkie uruchomione Daemon\'y, aby zastosować nowe jądro.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Zmienna ":variable" została usunięta i nie będzie już dostępna dla serwerów po przebudowie.',
            'variable_updated' => 'Zmienna ":variable" została zaktualizowana. Musisz przebudować serwery za pomocą tej zmiennej, aby zastosować zmiany.',
            'variable_created' => 'Nowa zmienna została pomyślnie stworzona i przypisana do tego jądra.',
        ],
    ],
    'descriptions' => [
        'name' => 'A simple, human-readable name to use as an identifier for this Egg.',
        'description' => 'A description of this Egg that will be displayed throughout the Panel as needed.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'The default startup command that should be used for new servers using this Egg.',
        'docker_images' => 'The docker images available to servers using this egg.',
    ],
];
