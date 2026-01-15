<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute moet geaccepteerd worden.',
    'active_url' => ':attribute is geen geldige URL.',
    'after' => ':attribute moet een datum zijn later dan :date.',
    'after_or_equal' => ':attribute moet een datum na of gelijk aan :date zijn.',
    'alpha' => ':attribute mag alleen letters bevatten.',
    'alpha_dash' => ':attribute mag enkel letters, cijfers of koppeltekens bevatten.',
    'alpha_num' => ':attribute mag alleen letters en nummers bevatten.',
    'array' => ':attribute moet een array zijn.',
    'before' => ':attribute moet een datum voor :date zijn.',
    'before_or_equal' => ':attribute moet een datum zijn voor of gelijk aan :date.',
    'between' => [
        'numeric' => ':attribute moet tussen :min en :max zijn.',
        'file' => ':attribute moet tussen de :min en :max kilobytes zijn.',
        'string' => ':attribute moet tussen :min en :max karakters zijn.',
        'array' => ':attribute moet tussen de :min en :max items bevatten.',
    ],

    'confirmed' => ':attribute bevestiging komt niet overeen.',
    'date' => ':attribute is geen geldige datum.',
    'date_format' => ':attribute komt niet overeen met het formaat :format.',
    'different' => ':attribute en :other moeten verschillend zijn.',
    'digits' => ':attribute moet :digits cijfers lang zijn.',
    'digits_between' => ':attribute moet tussen de :min en :max cijfers bevatten.',
    'dimensions' => ':attribute heeft ongeldige afbeelding afmetingen.',

    'email' => ':attribute is geen geldig e-mailadres.',

    'file' => ':attribute moet een bestand zijn.',
    'filled' => ':attribute is verplicht.',
    'image' => ':attribute moet een afbeelding zijn.',

    'in_array' => ':attribute veld bestaat niet in :other.',
    'integer' => ':attribute moet een getal zijn.',
    'ip' => ':attribute moet een geldig IP-adres zijn.',
    'json' => ':attribute moet een geldige JSON string zijn.',
    'max' => [
        'numeric' => ':attribute mag niet groter zijn dan :max.',
        'file' => ':attribute mag niet groter zijn dan :max kilobytes.',
        'string' => ':attribute mag niet uit meer dan :max karakters bestaan.',
        'array' => ':attribute mag niet meer dan :max items bevatten.',
    ],
    'mimes' => ':attribute moet een bestand zijn van het bestandstype :values.',
    'mimetypes' => ':attribute moet een bestand zijn van het bestandstype :values.',
    'min' => [
        'numeric' => ':attribute moet minimaal :min zijn.',
        'file' => ':attribute moet minstens :min kilobytes groot zijn.',
        'string' => ':attribute moet tenminste :min karakters bevatten.',
        'array' => ':attribute moet minimaal :min items bevatten.',
    ],

    'numeric' => ':attribute moet een nummer zijn.',

    'regex' => ':attribute formaat is ongeldig.',

    'required_with_all' => ':attribute is verplicht in combinatie met :values.',

    'same' => ':attribute en :other moeten overeenkomen.',
    'size' => [
        'numeric' => ':attribute moet :size zijn.',
        'file' => ':attribute moet :size kilobytes zijn.',
        'string' => ':attribute moet :size karakters zijn.',
        'array' => ':attribute moet :size items bevatten.',
    ],
    'string' => ':attribute moet een tekenreeks zijn.',
    'timezone' => ':attribute moet een geldige tijdzone zijn.',

    'url' => ':attribute formaat is ongeldig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    // Internal validation logic for Panel
    'internal' => [
        'variable_value' => ':env variabele',
        'invalid_password' => 'Het opgegeven wachtwoord is ongeldig voor dit account.',
    ],
];
