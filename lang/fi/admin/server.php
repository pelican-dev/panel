<?php

return [
    'exceptions' => [
        'no_new_default_allocation' => 'Yrität poistaa tämän palvelimen oletusvarauksen, mutta vaihtoehtoista varausta ei ole käytettävissä.',
        'marked_as_failed' => 'Tämä palvelin on merkitty epäonnistuneeksi aiemmassa asennuksessa. Nykyistä tilaa ei voida vaihtaa tässä tilassa.',
        'bad_variable' => 'Vahvistuksessa tapahtui virhe :name muuttujan kanssa.',
        'daemon_exception' => 'Tapahtui poikkeus, kun yritettiin kommunikoida daemonin kanssa, mikä johti HTTP/:code -vastauskoodiin. Tämä poikkeus on kirjattu. (request id: :request_id)',
        'default_allocation_not_found' => 'Pyydettyä oletusjakoa ei löytynyt tämän palvelimen varauksista.',
    ],
    'alerts' => [
        'startup_changed' => 'Tämän palvelimen käynnistysasetukset on päivitetty. Jos tämän palvelimen muna on muuttunut, uudelleenasennus tapahtuu nyt.',
        'server_deleted' => 'Palvelin on onnistuneesti poistettu järjestelmästä.',
        'server_created' => 'Palvelin luotiin onnistuneesti paneelissa. Anna daemonille muutama minuutti aikaa asentaa palvelin täysin valmiiksi.',
        'build_updated' => 'Rakennustiedot tälle palvelimelle on päivitetty. Osa muutoksista saattaa vaatia käynnistyksen, jotta ne tulevat voimaan.',
        'suspension_toggled' => 'Palvelimen keskeytyksen tila on vaihdettu :status.',
        'rebuild_on_boot' => 'Tämän palvelimen on merkitty edellyttävän Docker Container uudelleenrakentamista. Tämä tapahtuu seuraavan kerran, kun palvelin käynnistetään.',
        'install_toggled' => 'Tämän palvelimen asennuksen tila on vaihdettu.',
        'server_reinstalled' => 'Tämä palvelin on laitettu uudelleenasennusjonoon, joka alkaa nyt.',
        'details_updated' => 'Palvelimen tiedot on päivitetty onnistuneesti.',
        'docker_image_updated' => 'Onnistuneesti vaihdettiin oletus Docker-kuva, jota käytetään tälle palvelimelle. Muutoksen voimaantuloksi vaaditaan uudelleen käynnistys.',
        'node_required' => 'Sinulla on oltava vähintään yksi palvelin määritetty ennen kuin voit lisätä palvelimen tähän paneeliin.',
        'transfer_nodes_required' => 'Sinulla on oltava vähintään kaksi palvelinta määritetty ennen kuin voit siirtää palvelimia.',
        'transfer_started' => 'Palvelimen siirto on aloitettu.',
        'transfer_not_viable' => 'Valitsemasi palvelin ei ole riittävän suuri tälle palvelimelle tarvittavan levytilan tai muistin saatavuuden kannalta.',
    ],
];
