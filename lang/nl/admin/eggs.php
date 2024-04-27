<?php

return [
    'notices' => [
        'imported' => 'Het importeren van deze egg en de bijbehorende variabelen is geslaagd.',
        'updated_via_import' => 'Deze egg is bijgewerkt met behulp van het opgegeven bestand.',
        'deleted' => 'De aangevraagde egg is met succes uit het paneel verwijderd.',
        'updated' => 'Egg configuratie is met succes bijgewerkt.',
        'script_updated' => 'Egg install script is bijgewerkt en wordt uitgevoerd wanneer er servers worden geïnstalleerd.',
        'egg_created' => 'Een nieuw egg is met succes toegevoegd. U moet elke lopende daemon opnieuw opstarten om deze nieuwe egg toe te passen.',
    ],
    'variables' => [
        'notices' => [
            'variable_deleted' => 'De variabele ":variable" is verwijderd en zal niet meer beschikbaar zijn voor servers nadat deze opnieuw zijn opgebouwd.',
            'variable_updated' => 'De variabele ":variable" is bijgewerkt. Je moet elke server opnieuw opbouwen met deze variabele om wijzigingen toe te passen.',
            'variable_created' => 'Er is een nieuwe variabele aangemaakt en toegewezen aan deze egg.',
        ],
    ],
    'descriptions' => [
        'name' => 'Een eenvoudige, menselijk leesbare naam om te gebruiken als identificator voor deze Egg.',
        'description' => 'Een beschrijving van deze Egg die zal worden weergegeven in het gehele Paneel indien nodig.',
        'uuid' => 'Dit is de globaal unieke identificatie voor deze Egg dat Wings als een identificator gebruikt.',
        'author' => 'De auteur van deze versie van de egg. Het uploaden van een nieuwe egg configuratie van een andere auteur zal dit veranderen.',
        'force_outgoing_ip' => "Dwingt al het uitgaande netwerkverkeer om zijn bron-IP te laten NATeren tot het IP-adres van de primaire allocatie van de server.\nVereist voor het goed functioneren van bepaalde spellen als de Node meerdere openbare IP-adressen heeft.\nHet inschakelen van deze optie zal interne netwerken voor alle servers met deze egg uitschakelen, waardoor ze geen interne toegang hebben tot andere servers op dezelfde node.",
        'startup' => 'Het standaard opstart commando dat gebruikt moet worden voor nieuwe servers met deze egg.',
        'docker_images' => 'De docker images die beschikbaar zijn voor servers die deze egg gebruiken.',
    ],
];
