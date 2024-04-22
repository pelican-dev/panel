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
    'active_url' => ':attribute er ikke en gyldig URL.',
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
        'string' => ':attribute skal være mellem :min og :max tegn.',
        'array' => ':attribute skal have mellem :min og :max elementer.',
    ],
    'boolean' => ':attribute skal være sandt eller falsk.',
    'confirmed' => ':attribute bekræftelse stemmer ikke overens.',
    'date' => ':attribute er ikke en gyldig dato.',
    'date_format' => ':attribute stemmer ikke overens med formatet :format.',
    'different' => ':attribute og :other skal være forskellige.',
    'digits' => ':attribute skal være :digits cifre.',
    'digits_between' => ':attribute skal være mellem :min og :max cifre.',
    'dimensions' => ':attribute har ugyldige billeddimensioner.',
    'distinct' => ':attribute feltet har en duplikeret værdi.',
    'email' => ':attribute skal være en gyldig emailadresse.',
    'exists' => 'Den valgte :attribute er ugyldig.',
    'file' => ':attribute skal være en fil.',
    'filled' => ':attribute skal udfyldes.',
    'image' => ':attribute skal være et billede.',
    'in' => 'Den valgte :attribute er ugyldig.',
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
    'not_in' => 'Den valgte :attribute er ugyldig.',
    'numeric' => ':attribute skal være et tal.',
    'present' => ':attribute feltet skal være til stede.',
    'regex' => ':attribute formatet er ugyldigt.',
    'required' => ':attribute skal udfyldes.',
    'required_if' => ':attribute skal udfyldes når :other er :value.',
    'required_unless' => ':attribute skal udfyldes medmindre :other findes i :values.',
    'required_with' => ':attribute skal udfyldes når :values er til stede.',
    'required_with_all' => ':attribute skal udfyldes når :values er til stede.',
    'required_without' => ':attribute skal udfyldes når :values ikke er til stede.',
    'required_without_all' => ':attribute skal udfyldes når ingen af :values er til stede.',
    'same' => ':attribute og :other skal matche.',
    'size' => [
        'numeric' => ':attribute skal være :size.',
        'file' => ':attribute skal være :size kilobytes.',
        'string' => ':attribute skal være :size tegn.',
        'array' => ':attribute skal indeholde :size elementer.',
    ],
    'string' => ':attribute skal være en streng.',
    'timezone' => ':attribute skal være en gyldig tidszone.',
    'unique' => ':attribute er allerede taget.',
    'uploaded' => ':attribute fejlede uploade.',
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
