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

    'accepted' => 'Il campo :attribute deve essere accettato',
    'active_url' => 'Il campo :attribute non è un URL valido.',
    'after' => 'Il campo :attribute deve essere una data dopo il :date.',
    'after_or_equal' => ':attribute deve essere una data successiva o uguale al :date.',
    'alpha' => ':attribute può contenere solo lettere.',
    'alpha_dash' => ':attribute può contenere solo lettere, numeri e trattini.',
    'alpha_num' => ':attribute può contenere solo lettere e numeri.',
    'array' => ':attribute deve essere un array.',
    'before' => ':attribute deve essere una data precedente il :date .',
    'before_or_equal' => ':attribute deve essere una data precedente o uguale a :date.',
    'between' => [
        'numeric' => ':attribute deve essere tra :min e :max.',
        'file' => ':attribute deve essere compreso tra :min e :max kilobyte.',
        'string' => ':attribute deve essere tra :min e :max caratteri.',
        'array' => ':attribute deve avere tra :min e :max elementi.',
    ],

    'confirmed' => 'La conferma di :attribute non corrisponde.',
    'date' => 'Il campo :attribute non contiene una data valida.',
    'date_format' => ':attribute non corrisponde al formato :format.',
    'different' => ':attribute e :other devono essere differenti.',
    'digits' => ':attribute deve essere di :digits cifre.',
    'digits_between' => ':attribute deve essere tra :min e :max cifre.',
    'dimensions' => ':attribute ha dimensioni di immagine non valide.',

    'email' => ':attribute deve essere un indirizzo email valido.',

    'file' => ':attribute deve essere un file.',
    'filled' => 'Il campo :attribute è necessario.',
    'image' => ':attribute deve essere un immagine.',

    'in_array' => 'Il campo :attribute non esiste in :other.',
    'integer' => ':attribute deve essere un numero intero.',
    'ip' => ':attribute deve essere un indirizzo IP valido.',
    'json' => ':attribute deve essere una stringa JSON valida.',
    'max' => [
        'numeric' => ':attribute non può essere superiore a :max.',
        'file' => ':attribute non deve essere sopra i :max kilobyte.',
        'string' => ':attribute non può contenere più di :max caratteri.',
        'array' => ':attribute non può avere più di :max elementi.',
    ],
    'mimes' => ':attribute deve essere un file di tipo: :values.',
    'mimetypes' => ':attribute deve essere un file di tipo: :values.',
    'min' => [
        'numeric' => ':attribute deve essere almeno :min.',
        'file' => ':attribute deve essere almeno di :min kilobyte.',
        'string' => ':attribute deve contenere almeno :min caratteri.',
        'array' => ':attribute deve avere almeno :min elementi.',
    ],

    'numeric' => ':attribute deve essere un numero.',

    'regex' => 'Il formato di :attribute non è valido.',

    'required_with_all' => 'Il campo :attribute è obbligatorio quando :values è presente.',

    'same' => ':attribute e :other devono coincidere.',
    'size' => [
        'numeric' => ':attribute deve essere :size.',
        'file' => ':attribute deve essere :size kilobyte.',
        'string' => ':attribute deve essere di :size caratteri.',
        'array' => ':attribute deve contenere :size elementi.',
    ],
    'string' => ':attribute deve essere una stringa.',
    'timezone' => ':attribute deve essere una zona valida.',

    'url' => 'Il formato di :attribute non è valido.',

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
        'variable_value' => 'variabile :env',
        'invalid_password' => 'La password fornita non è valida per questo account.',
    ],
];
