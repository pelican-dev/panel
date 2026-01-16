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

    'accepted' => ':attribute skal accepteres.',
    'active_url' => ':attribute er ikke et gyldigt URL.',
    'after' => ':attribute skal være en dato efter :date.',
    'after_or_equal' => ':attribute skal være en dato efter eller lig med :date.',
    'alpha' => ':attribute må kun indeholde bogstaver.',
    'alpha_dash' => ':attribute må kun indeholde bogstaver, tal og bindestreger.',
    'alpha_num' => ':attribute må kun indeholde bogstaver og tal.',
    'array' => ':attribute skal være et array.',
    'before' => ':attribute skal være en dato før :date.',
    'before_or_equal' => ':attribute skal være en dato før eller lig med :date.',
    'between' => [
        'numeric' => ':attribute skal være mellem :min og :max.',
        'file' => ':attribute skal være mellem :min og :max kilobytes.',
        'string' => ':attribute skal være imellem :min - :max tegn.',
        'array' => ':attribute skal have mellem :min - :max elementer.',
    ],

    'confirmed' => ':attribute bekræftelse stemmer ikke overens.',
    'date' => ':attribute er ikke en gyldig dato.',
    'date_format' => ':attribute stemmer ikke overens med formatet :format.',
    'different' => ':attribute og :other skal være forskellige.',
    'digits' => ':attribute skal være :digits cifre.',
    'digits_between' => ':attribute skal være mellem :min og :max cifre.',
    'dimensions' => ':attribute har ugyldige billeddimensioner.',

    'email' => ':attribute skal være en gyldig emailadresse.',

    'file' => ':attribute skal være en fil.',
    'filled' => ':attribute skal udfyldes.',
    'image' => ':attribute skal være et billede.',

    'in_array' => ':attribute feltet findes ikke i :other.',
    'integer' => ':attribute skal være et heltal.',
    'ip' => ':attribute skal være en gyldig IP-adresse.',
    'json' => ':attribute skal være en gyldig JSON-streng.',
    'max' => [
        'numeric' => ':attribute må ikke være større end :max.',
        'file' => ':attribute må ikke være større end :max kilobytes.',
        'string' => ':attribute må ikke være større end :max tegn.',
        'array' => ':attribute må ikke have mere end :max elementer.',
    ],
    'mimes' => ':attribute skal være en fil af typen: :values.',
    'mimetypes' => ':attribute skal være en fil af typen: :values.',
    'min' => [
        'numeric' => ':attribute skal være mindst :min.',
        'file' => ':attribute skal være mindst :min kilobytes.',
        'string' => ':attribute skal være mindst :min tegn.',
        'array' => ':attribute skal have mindst :min elementer.',
    ],

    'numeric' => ':attribute skal være et tal.',

    'regex' => ':attribute formatet er ugyldigt.',

    'required_with_all' => ':attribute skal udfyldes når :values er til stede.',

    'same' => ':attribute og :other skal matche.',
    'size' => [
        'numeric' => ':attribute skal være :size.',
        'file' => ':attribute skal være :size kilobytes.',
        'string' => ':attribute skal være :size tegn.',
        'array' => ':attribute skal indeholde :size elementer.',
    ],
    'string' => ':attribute skal være tekst.',
    'timezone' => ':attribute skal være en gyldig tidszone.',

    'url' => ':attribute formatet er ugyldigt.',

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
        'invalid_password' => 'Den angivne adgangskode var ugyldig for denne konto.',
    ],
];
