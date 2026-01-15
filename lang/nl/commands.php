<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Geef het e-mailadres op waarvan eggs geëxporteerd worden vanuit dit Paneel. Dit moet een geldig e-mailadres zijn.',
            'url' => 'De applicatie-URL MOET beginnen met https:// of http:// afhankelijk van het gebruik van SSL of niet. Als u dit niet toevoegt zullen uw e-mails en andere inhoud linken naar de verkeerde locatie.',
            'timezone' => "De tijdzone moet overeenkomen met een van de ondersteunde tijdzones van PHP\\'s. Als u niet zeker bent, ga dan naar https://php.net/manual/en/timezones.php.",
        ],
        'redis' => [
            'note' => 'U heeft de Redis-driver geselecteerd voor één of meer opties, geef hieronder geldige verbindingsinformatie op. In de meeste gevallen kunt u de opgegeven standaardwaarden gebruiken, maar niet als u uw instellingen hebt gewijzigd.',
            'comment' => 'Standaard heeft een Redis-serverinstance de gebruikersnaam default en geen wachtwoord, omdat deze lokaal wordt uitgevoerd en niet toegankelijk is van buitenaf. Als dit het geval is, druk dan gewoon op Enter zonder een waarde in te voeren.',
            'confirm' => 'Het lijkt erop dat een :field al is gedefinieerd voor Redis, wilt u het wijzigen?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Het wordt sterk aangeraden om "localhost" niet als uw database-host te gebruiken, aangezien we frequente problemen met verbinding met socket hebben gezien. Als je een lokale verbinding wilt gebruiken, moet je "127.0.0.1" gebruiken.',
        'DB_USERNAME_note' => 'Het gebruik van het root-account voor MySQL verbindingen is niet alleen sterk afgeraden, het wordt ook niet toegestaan door deze applicatie. U heeft een MySQL gebruiker voor deze software nodig.',
        'DB_PASSWORD_note' => 'Het lijkt erop dat u al een MySQL wachtwoord hebt gedefinieerd, wilt u het wijzigen?',
        'DB_error_2' => 'Uw verbindingsgegevens zijn NIET opgeslagen. U moet geldige verbindingsinformatie verstrekken voordat u verder gaat.',
        'go_back' => 'Ga terug en probeer het opnieuw',
    ],
    'make_node' => [
        'name' => 'Voer een korte identificator in die gebruikt wordt om deze node te onderscheiden van anderen',
        'description' => 'Voer een beschrijving in om de node te identificeren',
        'scheme' => 'Voer alstublieft https in voor een SSL-verbinding of http voor een non-ssl verbinding',
        'fqdn' => 'Voer een domeinnaam (bijv. node.example.com) in om te gebruiken voor het verbinden met de daemon. Een IP-adres kan alleen worden gebruikt als u geen SSL gebruikt voor deze node',
        'public' => 'Moet deze node openbaar zijn? Ter uitbreiding, wanneer een node ingesteld wordt als privé node, dan zal het onmogelijk zijn om deze node te gebruiken voor het automatisch uitrollen.',
        'behind_proxy' => 'Staat uw FQDN achter een proxy?',
        'maintenance_mode' => 'Moet de onderhoudsmodus worden ingeschakeld?',
        'memory' => 'Vul de maximale hoeveelheid geheugen in',
        'memory_overallocate' => 'Voer de hoeveelheid geheugen in die toegestaan is boven het ingestelde geheugen, -1 zal de controle uitschakelen en 0 zal het maken van nieuwe servers voorkomen',
        'disk' => 'Voer het maximum aantal schijfruimte in',
        'disk_overallocate' => 'Voer de hoeveelheid geheugen in die toegestaan is boven het ingestelde geheugen, -1 zal de controle uitschakelen en 0 zal het maken van nieuwe servers voorkomen',
        'cpu' => 'Vul de maximale hoeveelheid geheugen in',
        'cpu_overallocate' => 'Voer de hoeveelheid geheugen in die toegestaan is boven het ingestelde geheugen, -1 zal de controle uitschakelen en 0 zal het maken van nieuwe servers voorkomen',
        'upload_size' => "'Voer de maximale bestandsgrootte upload in",
        'daemonListen' => 'Voer de daemon listening port in',
        'daemonConnect' => 'Voer de deamon connectie poort in (deze kan niet hetzelfde zijn als de luisterende poort)',
        'daemonSFTP' => 'Voer de daemon SFTP listening port in',
        'daemonSFTPAlias' => 'Voer de daemon SFTP alias in (kan leeg zijn)',
        'daemonBase' => 'Voer de basismap in',
        'success' => 'Nieuw Node met de naam :name is succesvol aangemaakt en heeft een id van :id',
    ],
    'node_config' => [
        'error_not_exist' => 'De geselecteerde node bestaat niet.',
        'error_invalid_format' => 'Ongeldig formaat opgegeven. Geldige opties zijn yaml en json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Het lijkt erop dat u al een applicatie encryptiesleutel hebt geconfigureerd. Doorgaan met dit proces zal leiden tot het overschrijven de deze sleutel en veroorzaakt datacorruptie voor bestaande versleutelde gegevens. GA NIET DOOR BEHALVE ALS JE WEET WAT JE AAN HET DOEN BENT.',
        'understand' => 'Ik begrijp de gevolgen van het uitvoeren van deze opdracht en neem de volledige verantwoordelijkheid op me voor het verlies van versleutelde gegevens.',
        'continue' => 'Weet je zeker dat je wilt doorgaan? Het wijzigen van de applicatiesleutel ZORGT VOOR VERLIES VAN DATA.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Er zijn geen geplande taken voor servers die uitgevoerd moeten worden.',
            'error_message' => 'Er is een fout opgetreden tijdens het verwerken van de Schema: ',
        ],
    ],
];
