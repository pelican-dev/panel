<?php

return [
    'title' => 'Schema\'s',
    'new' => 'Nieuw Schema',
    'edit' => 'Schema bewerken',
    'save' => 'Schema opslaan',
    'delete' => 'Schema verwijderen',
    'import' => 'Schema importeren',
    'export' => 'Schema exporteren',
    'name' => 'Naam',
    'cron' => 'Cron',
    'status' => 'Status',
    'schedule_status' => [
        'inactive' => 'Inactief',
        'processing' => 'Bezig met verwerken',
        'active' => 'Actief',
    ],
    'no_tasks' => 'Geen taken',
    'run_now' => 'Nu uitvoeren',
    'online_only' => 'Alleen wanneer online',
    'last_run' => 'Laatst uitgevoerd',
    'next_run' => 'Volgende uitvoering',
    'never' => 'nooit',
    'cancel' => 'Annuleren',

    'only_online' => 'Alleen wanneer de server Online is?',
    'only_online_hint' => 'Voer dit schema alleen uit als de server zich in een actieve staat bevindt.',
    'enabled' => 'Schema inschakelen?',
    'enabled_hint' => 'Dit schema zal automatisch worden uitgevoerd als dit is ingeschakeld.',

    'cron_body' => 'Houd er rekening mee dat de cron invoer altijd UTC gebruikt.',
    'cron_timezone' => 'De volgende uitvoering in je eigen tijdzone (:timezone): <b> :next_run </b>',

    'invalid' => 'Ongeldig',

    'time' => [
        'minute' => 'Minuut',
        'hour' => 'Uur',
        'day' => 'Dag',
        'week' => 'Week',
        'month' => 'Maand',
        'day_of_month' => 'Dag van maand',
        'day_of_week' => 'Dag van de week',

        'hourly' => 'Uurlijks',
        'daily' => 'Dagelijks',
        'weekly_mon' => 'Wekelijks (maandag)',
        'weekly_sun' => 'Wekelijks (zondag)',
        'monthly' => 'Maandelijks',
        'every_min' => 'Elke X minuten',
        'every_hour' => 'Elke X uur',
        'every_day' => 'Elke X dagen',
        'every_week' => 'Elke X weken',
        'every_month' => 'Elke X maanden',
        'every_day_of_week' => 'Elke X dag van de week',

        'every' => 'Elke',
        'minutes' => 'Minuten',
        'hours' => 'Uren',
        'days' => 'Dagen',
        'months' => 'Maanden',

        'monday' => 'Maandag',
        'tuesday' => 'Dinsdag',
        'wednesday' => 'Woensdag',
        'thursday' => 'Donderdag',
        'friday' => 'Vrijdag',
        'saturday' => 'Zaterdag',
        'sunday' => 'Zondag',
    ],

    'tasks' => [
        'title' => 'Taken',
        'create' => 'Taak Aanmaken',
        'limit' => 'Taaklimiet bereikt',
        'action' => 'Actie',
        'payload' => 'Payload',
        'no_payload' => 'Geen Payload',
        'time_offset' => 'Tijdverschil',
        'first_task' => 'Eerste taak',
        'seconds' => 'Seconden',
        'continue_on_failure' => 'Doorgaan bij fouten',

        'actions' => [
            'title' => 'Actie',
            'power' => [
                'title' => 'Verstuur server actie',
                'action' => 'Server actie',
                'start' => 'Start',
                'stop' => 'Stop',
                'restart' => 'Herstarten',
                'kill' => 'Geforceerd stoppen',
            ],
            'command' => [
                'title' => 'Commando versturen',
                'command' => 'Commando',
            ],
            'backup' => [
                'title' => 'Maak back-up',
                'files_to_ignore' => 'Bestanden om te negeren',
            ],
            'delete_files' => [
                'title' => 'Verwijder bestanden',
                'files_to_delete' => 'Te verwijderen bestanden',
            ],
        ],
    ],

    'notification_invalid_cron' => 'De verstrekte crongegevens worden niet geëvalueerd naar een geldige expressie',

    'import_action' => [
        'file' => 'Bestand',
        'url' => 'URL',
        'schedule_help' => 'Dit zou een raw .json bestand moeten zijn ( schedule-daily-restart.json )',
        'url_help' => 'URL\'s moeten gelijk verwijzen naar een raw .json bestand',
        'add_url' => 'Nieuwe URL',
        'import_failed' => 'Importeren mislukt',
        'import_success' => 'Importeren gelukt',
    ],
];
