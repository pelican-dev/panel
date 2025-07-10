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

    'accepted' => ':attribute trebuie să fie acceptat.',
    'active_url' => 'Câmpul :attribute nu este un URL valid.',
    'after' => ':attribute trebuie sa fie o dată după :date.',
    'after_or_equal' => ':attribute trebuie să fie o dată mai mare sau egală cu :date.',
    'alpha' => ':attribute poate să conțină numai litere.',
    'alpha_dash' => ':attribute poate conține numai litere, numere și liniuțe.',
    'alpha_num' => 'Câmpul :attribute poate conține doar litere și numere.',
    'array' => ':attribute trebuie să fie un vector.',
    'before' => ':attribute trebuie sa contina o data inainte de :date.',
    'before_or_equal' => 'Câmpul :attribute trebuie să fie o dată înainte sau egală cu :date.',
    'between' => [
        'numeric' => ':attribute trebuie să fie între :min și :max.',
        'file' => ':attribute trebuie să fie între :min și :max kilobytes.',
        'string' => ':attribute trebuie să fie între :min și :max caractere.',
        'array' => ':attribute trebuie să aibă între :min şi :max elemente.',
    ],

    'confirmed' => 'Confirmarea :attribute nu se potrivește.',
    'date' => ':attribute nu este o dată valida.',
    'date_format' => ':attribute nu se potrivește cu formatul :format.',
    'different' => ':attribute și :other trebuie să fie diferite.',
    'digits' => ':attribute trebuie să fie de :digits cifre.',
    'digits_between' => ':attribute trebuie să aibă între :min și :max cifre.',
    'dimensions' => ':attribute are dimensiuni nevalide ale imaginii.',

    'email' => ':attribute trebuie să fie o adresă de email validă.',

    'file' => ':attribute trebuie să fie un fișier.',
    'filled' => 'Câmpul :attribute este obligatoriu.',
    'image' => ':attribute trebuie să fie o imagine.',

    'in_array' => 'Câmpul :attribute nu există în :other.',
    'integer' => ':attribute trebuie să fie un număr întreg.',
    'ip' => 'Câmpul :attribute trebuie să fie o adresă IP validă.',
    'json' => 'Acest :attribute trebuie să fie un sir JSON valid.',
    'max' => [
        'numeric' => ':attribute nu poate fi mai mare decât :max.',
        'file' => 'Câmpul :attribute nu poate avea mai mult de :max kiloocteți.',
        'string' => ':attribute nu poate fi mai mare decât :max caractere.',
        'array' => ':attribute nu poate avea mai mult de :max elemente.',
    ],
    'mimes' => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'mimetypes' => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'min' => [
        'numeric' => ':attribute trebuie să aibă cel puțin :min.',
        'file' => ':attribute trebuie să aibă cel puțin :min kilobytes.',
        'string' => ':attribute trebuie să fie de minim :min caractere.',
        'array' => ':attribute trebuie să aibă cel puțin :min elemente.',
    ],

    'numeric' => ':attribute trebuie sa fie un numar.',

    'regex' => 'Formatul :attribute este invalid.',

    'required_with_all' => 'Câmpul :attribute este obligatoriu atunci când :values este prezent.',

    'same' => 'Câmpul :attribute și :other trebuie să fie identice.',
    'size' => [
        'numeric' => ':attribute trebuie să fie :size.',
        'file' => 'Acest :attribute trebuie sa aibă :size kilobiți.',
        'string' => 'Câmpul :attribute trebuie să aibă :size caractere.',
        'array' => ':attribute trebuie să conțină :size elemente.',
    ],
    'string' => ':attribute trebuie să fie un șir.',
    'timezone' => ':attribute trebuie să fie o zonă validă.',

    'url' => 'Formatul :attribute este invalid.',

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
        'variable_value' => 'Variabilă :env',
        'invalid_password' => 'Parola furnizată nu a fost validă pentru acest cont.',
    ],
];
