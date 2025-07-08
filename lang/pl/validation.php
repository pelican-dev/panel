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
    'active_url' => ':attribute nie jest poprawnym adresem URL',
    'after' => ':attribute musi być datą późniejszą niż :date.',
    'after_or_equal' => ':attribute musi być datą późniejszą lub równą :date.',
    'alpha' => ':attribute może zawierać wyłącznie litery.',
    'alpha_dash' => ':attribute może zawierać tylko litery, cyfry i myślniki.',
    'alpha_num' => ':attribute może zawierać wyłącznie litery i cyfry.',
    'array' => ':attribute musi być array\'em.',
    'before' => ':attribute musi być datą wcześniejszą niż :date.',
    'before_or_equal' => ':attribute musi być datą wcześniejszą lub równą :date.',
    'between' => [
        'numeric' => ':attribute musi wynosić między :min i :max.',
        'file' => ':attribute musi wynosić między :min a :max kilobajtów.',
        'string' => ':attribute musi wynosić między :min a :max znakami.',
        'array' => ':attribute musi mieć między :min a :max elementów.',
    ],

    'confirmed' => ':attribute nie pasuje.',
    'date' => ':attribute nie jest poprawną datą.',
    'date_format' => ':attribute nie jest zgodny z formatem :format.',
    'different' => ':attribute i :other muszą się różnić.',
    'digits' => ':attribute musi wynosić :digits cyfr.',
    'digits_between' => ':attribute musi wynosić między :min a :max cyfr.',
    'dimensions' => ':attribute ma niepoprawne wymiary obrazu.',

    'email' => ':attribute musi być poprawnym adresem e-mail.',

    'file' => 'Wybrany :attribute musi być plikiem.',
    'filled' => 'Pole :attribute jest wymagane.',
    'image' => 'Wybrany :attribute musi być obrazem.',

    'in_array' => 'Pole :attribute nie istnieje w :other.',
    'integer' => ':attribute musi być liczbą całkowitą.',
    'ip' => ':attribute musi być poprawnym adresem IP.',
    'json' => ':attribute musi być poprawnym ciągiem JSON.',
    'max' => [
        'numeric' => ':attribute nie może być większy niż :max.',
        'file' => ':attribute nie może być większy niż :max kilobajtów.',
        'string' => ':attribute nie może być większe niż :max znaków.',
        'array' => ':attribute nie może mieć więcej niż :max elementów.',
    ],
    'mimes' => ':attribute musi być plikiem typu: :values.',
    'mimetypes' => ':attribute musi być plikiem typu: :values.',
    'min' => [
        'numeric' => ':attribute musi wynosić co najmniej :min.',
        'file' => ':attribute musi wynosić co najmniej :min kilobajtów.',
        'string' => ':attribute musi mieć co najmniej :min znaków.',
        'array' => ':attribute musi mieć co najmniej :min elementów.',
    ],

    'numeric' => ':attribute musi być liczbą.',

    'regex' => 'Format :attribute jest nieprawidłowy.',

    'required_with_all' => 'Pole :attribute jest wymagane, gdy :values jest obecne.',

    'same' => ':attribute i :other muszą być zgodne.',
    'size' => [
        'numeric' => ':attribute musi mieć :size.',
        'file' => ':attribute musi wynosić :size kilobajty.',
        'string' => ':attribute musi mieć :size znaków.',
        'array' => ':attribute musi zawierać :size elementów.',
    ],
    'string' => ':attribute musi być ciągiem znaków.',
    'timezone' => ':attribute musi być poprawną strefą.',

    'url' => 'Format :attribute jest niepoprawny.',

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
        'invalid_password' => 'Hasło podane dla tego konta jest nieprawidłowe.',
    ],
];
