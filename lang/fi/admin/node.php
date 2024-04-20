<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Annettua FQDN:ää tai IP-osoitetta ei voida muuntaa kelvolliseksi IP-osoitteeksi.',
        'fqdn_required_for_ssl' => 'SSL:n käyttämiseksi tälle solmulle tarvitaan täysin määritelty verkkotunnusnimi, joka muuntuu julkiseksi IP-osoitteeksi.',
    ],
    'notices' => [
        'allocations_added' => 'Varaukset on onnistuneesti lisätty tähän solmuun.',
        'node_deleted' => 'Solmu on onnistuneesti poistettu paneelista.',
        'node_created' => 'Uusi palvelin luotiin onnistuneesti. Voit automaattisesti määrittää daemonin tälle koneelle käymällä \'Configuration\' välilehdellä. <strong>Ennen kuin voit lisätä mitään palvelimia, sinun on ensin varattava vähintään yksi IP-osoite ja portti.</strong>',
        'node_updated' => 'Palvelimen tiedot on päivitetty. Jos jokin Daemonin asetuksia on muutettu, sinun täytyy käynnistää ne uudelleen, jotta nämä muutokset tulevat voimaan.',
        'unallocated_deleted' => 'Poistettiin kaikki kohdentamattomat portit <code>:ip</code>.',
    ],
];
