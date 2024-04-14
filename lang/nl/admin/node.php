<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'De opgegeven FQDN of IP adres kan niet gekoppeld worden aan een geldig IP-adres.',
        'fqdn_required_for_ssl' => 'Een volledig domein naam welke naar een openbaar IP-adres wijst is nodig om SSL te gebruiken op deze node.',
    ],
    'notices' => [
        'allocations_added' => 'Allocaties zijn succesvol toegevoegd aan deze node.',
        'node_deleted' => 'De node is succesvol verwijderd van het paneel.',
        'node_created' => 'De node is succesvol aangemaakt. Je kan automatisch de daemon configureren op deze machine door het tabje \'Configuratie\' te bezoeken. <strong>Voordat je servers kan aanmaken, moet je minimaal één IP-adres en poort toewijzen.</strong>',
        'node_updated' => 'Node informatie is bijgewerkt. Als de daemon instellingen zijn aangepast, dien je de daemon te herstarten om wijzigingen toe te passen.',
        'unallocated_deleted' => 'Alle niet toegewezen poorten zijn verwijderd voor: <code>ip</code>',
    ],
];
