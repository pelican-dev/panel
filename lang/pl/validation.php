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

    'accepted' => ':attribute musi zostać zaakceptowany.',
    'active_url' => ':attribute jest nieprawidłowym adresem URL.',
    'after' => ':attribute musi być późniejszą datą w stosunku do :date.',
    'after_or_equal' => ':attribute musi być datą późniejszą lub tą samą co :date.',
    'alpha' => ':attribute może zawierać wyłącznie litery.',
    'alpha_dash' => ':attribute może zawierać tylko litery, cyfry i myślniki.',
    'alpha_num' => ':attribute może zawierać jedynie litery oraz cyfry.',
    'array' => ':attribute musi być array\'em.',
    'before' => ':attribute musi być datą przed :date.',
    'before_or_equal' => ':attribute musi być datą przed albo równą dacie :date.',
    'between' => [
        'numeric' => ':attribute musi być w zakresie od :min do :max.',
        'file' => 'Waga :attribute musi wynosić pomiędzy :min, a :max kilobajtów.',
        'string' => 'Długość :attribute musi wynosić pomiędzy :min, a :max znaków',
        'array' => ':attribute musi zawierać pomiędzy :min a :max elementów.',
    ],
    'boolean' => ':attribute musi być true lub false.',
    'confirmed' => 'Potwierdzenie :attribute nie jest zgodne.',
    'date' => ':attribute nie jest prawidłową datą.',
    'date_format' => ':attribute musi mieć format :format.',
    'different' => ':attribute i :other muszą się różnić.',
    'digits' => ':attribute musi składać się z :digits cyfr.',
    'digits_between' => ':attribute musi mieć od :min do :max cyfr.',
    'dimensions' => ':attribute ma niepoprawne wymiary.',
    'distinct' => 'Pole :attribute zawiera zduplikowaną wartość.',
    'email' => ':attribute musi być prawidłowym adresem email.',
    'exists' => 'Wybrany :attribute jest nieprawidłowy.',
    'file' => ':attrivute musi być plikiem.',
    'filled' => 'Pole :attribute jest wymagane.',
    'image' => ':attribute musi być obrazem.',
    'in' => 'Wybrany :attribute jest nieprawidłowy.',
    'in_array' => 'Pole :attribute nie istnieje w :other.',
    'integer' => ':attribute musi być liczbą.',
    'ip' => ':attribute musi być prawidłowym adresem IP.',
    'json' => ':attribute musi być prawidłowym ciągiem JSON.',
    'max' => [
        'numeric' => ':attribute nie może być większa niż :max.',
        'file' => 'Wielkość :attribute nie może być większa niż :max kilobajtów.',
        'string' => ':attribute nie może być dłuższy niż :max znaków.',
        'array' => ':attribute nie może mieć więcej niż :max elementów.',
    ],
    'mimes' => ':attribute musi być plikiem typu: :values.',
    'mimetypes' => ':attribute musi być plikiem typu: :values.',
    'min' => [
        'numeric' => ':attribute musi mieć co najmniej :min.',
        'file' => ':attribute musi mieć co najmniej :min kilobajtów.',
        'string' => ':attribute musi mieć przynajmniej :min znaków.',
        'array' => ':attribute musi mieć co najmniej :min elementów.',
    ],
    'not_in' => 'Wybrany :attribute jest nieprawidłowy.',
    'numeric' => ':attribute musi być liczbą.',
    'present' => 'Pole :attribute musi być wypełnione.',
    'regex' => 'Format :attribute jest niewłaściwy.',
    'required' => 'Pole :attribute jest wymagane.',
    'required_if' => 'Pole :attribute jest wymagane, gdy :other jest :value.',
    'required_unless' => ':attribute jest wymagany jeżeli :other nie znajduje się w :values.',
    'required_with' => 'Pole :attribute jest wymagane gdy :values jest obecny.',
    'required_with_all' => 'Pole :attribute jest wymagane gdy :values jest obecny.',
    'required_without' => 'Pole :attribute jest wymagane gdy :values nie jest podana.',
    'required_without_all' => 'Pole :attribute jest wymagane, gdy żadna z :values nie jest obecna.',
    'same' => 'Pole :attribute oraz :other muszą być takie same.',
    'size' => [
        'numeric' => 'Atrybut :attribute musi mieć wartość :size.',
        'file' => 'Pole :attribute musi mieć :size kilobajtów.',
        'string' => ':attribute musi mieć :size znaków.',
        'array' => ':attribute musi zawierać :size elementów.',
    ],
    'string' => ':attribute musi być typu string.',
    'timezone' => ':attribute musi być prawidłową strefą.',
    'unique' => ':attribute został już pobrany.',
    'uploaded' => 'Nie udało się przesłać :attribute.',
    'url' => 'Format :attribute jest niewłaściwy.',

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
        'variable_value' => 'Zmienna :env',
        'invalid_password' => 'Podane hasło jest nieprawidłowe.',
    ],
];
