<?php

return [
    'notices' => [
        'imported' => 'Tämän munan ja siihen liittyvien muuttujien tuonti onnistui.',
        'updated_via_import' => 'Tämä muna on päivitetty toimitettua tiedostoa käyttäen.',
        'deleted' => 'Pyydetyn munan poistaminen paneelista onnistui.',
        'updated' => 'Munan määritys on päivitetty onnistuneesti.',
        'script_updated' => 'Munan asennus koodi on päivitetty ja suoritetaan aina, kun palvelin asennetaan.',
        'egg_created' => 'Uusi muna luotiin onnistuneesti ja se on valmis käytettäväksi. Sinun tulee käynnistää uudelleen kaikki käynnissä olevat daemonit, jotta uusi muna otetaan käyttöön',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'Muuttuja ":variable" on poistettu, eikä se enää ole palvelimien käytettävissä uudelleenrakennuksen jälkeen.',
            'variable_updated' => 'Muuttuja ":variable" on päivitetty. Sinun on rakennettava uudelleen kaikki palvelimet, jotka käyttävät tätä muuttujaa, jotta muutokset voidaan ottaa käyttöön.',
            'variable_created' => 'Uusi muuttuja on onnistuneesti luotu ja määritetty tähän munaan.',
        ],
    ],
    'descriptions' => [
        'name' => 'Yksinkertainen, ihmisluettavissa oleva nimi, jota käytetään tämän munan tunnisteena.',
        'description' => 'Tämän munan kuvaus, joka näkyy tarpeen mukaan.',
        'uuid' => 'This is the globally unique identifier for this Egg which Wings uses as an identifier.',
        'author' => 'The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.',
        'force_outgoing_ip' => "Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.\nRequired for certain games to work properly when the Node has multiple public IP addresses.\nEnabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node.",
        'startup' => 'Käynnistyskomento, jota käytetään uusille palvelimille, jotka käyttävät tätä munaa.',
        'docker_images' => 'Docker-kuvat, jotka ovat käytettävissä palvelimille, jotka käyttävät tätä munaa.',
    ],
];
