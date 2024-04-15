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
    'ip' => 'The :attribute must be a valid IP address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values is present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',

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
