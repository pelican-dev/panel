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

    'accepted' => ':attribute må aksepteres.',
    'active_url' => ':attribute er ikke en gyldig URL.',
    'after' => ':attribute må være en dato etter :date.',
    'after_or_equal' => ':attribute må være en dato etter eller lik :date.',
    'alpha' => ':attribute kan kun inneholde bokstaver.',
    'alpha_dash' => ':attribute kan kun inneholde bokstaver, tall og bindestreker.',
    'alpha_num' => ':attribute kan kun inneholde bokstaver og tall.',
    'array' => ':attribute må være en liste.',
    'before' => ':attribute må være en dato før :date.',
    'before_or_equal' => ':attribute må være en dato før eller lik :date.',
    'between' => [
        'numeric' => ':attribute må være mellom :min og :max.',
        'file' => ':attribute må være mellom :min og :max kilobytes.',
        'string' => ':attribute må være mellom :min og :max tegn.',
        'array' => ':attribute må ha mellom :min og :max elementer.',
    ],

    'confirmed' => ':attribute-bekreftelsen samsvarer ikke.',
    'date' => ':attribute er ikke en gyldig dato.',
    'date_format' => ':attribute samsvarer ikke med formatet :format.',
    'different' => ':attribute og :other må være forskjellige.',
    'digits' => ':attribute må være :digits siffer.',
    'digits_between' => ':attribute må være mellom :min og :max siffer.',
    'dimensions' => ':attribute har ugyldige bildedimensjoner.',

    'email' => ':attribute må være en gyldig e-postadresse.',

    'file' => ':attribute må være en fil.',
    'filled' => ':attribute-feltet er påkrevd.',
    'image' => ':attribute må være et bilde.',

    'in_array' => ':attribute-feltet eksisterer ikke i :other.',
    'integer' => ':attribute må være et heltall.',
    'ip' => ':attribute må være en gyldig IP-adresse.',
    'json' => ':attribute må være en gyldig JSON-streng.',
    'max' => [
        'numeric' => ':attribute kan ikke være større enn :max.',
        'file' => ':attribute kan ikke være større enn :max kilobytes.',
        'string' => ':attribute kan ikke være lengre enn :max tegn.',
        'array' => ':attribute kan ikke ha mer enn :max elementer.',
    ],
    'mimes' => ':attribute må være en fil av typen: :values.',
    'mimetypes' => ':attribute må være en fil av typen: :values.',
    'min' => [
        'numeric' => ':attribute må være minst :min.',
        'file' => ':attribute må være minst :min kilobytes.',
        'string' => ':attribute må være minst :min tegn.',
        'array' => ':attribute må ha minst :min elementer.',
    ],

    'numeric' => ':attribute må være et tall.',

    'regex' => ':attribute har et ugyldig format.',

    'required_with_all' => ':attribute-feltet er påkrevd når :values er til stede.',

    'same' => ':attribute og :other må samsvare.',
    'size' => [
        'numeric' => ':attribute må være :size.',
        'file' => ':attribute må være :size kilobytes.',
        'string' => ':attribute må være :size tegn.',
        'array' => ':attribute må inneholde :size elementer.',
    ],
    'string' => ':attribute må være en streng.',
    'timezone' => ':attribute må være en gyldig tidssone.',

    'url' => ':attribute har et ugyldig format.',

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
        'variable_value' => ':env variabel',
        'invalid_password' => 'Det oppgitte passordet var ugyldig for denne kontoen.',
    ],
];
