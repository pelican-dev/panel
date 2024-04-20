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

    'accepted' => ':attribute tulee olla hyväksytty.',
    'active_url' => ':attribute ei ole kelvollinen URL.',
    'after' => ':attribute on oltava päivämäärä :date jälkeen.',
    'after_or_equal' => ':attribute päivämäärä tulee olla sama tai jälkeen :date.',
    'alpha' => ':attribute voi sisältää vain kirjaimia.',
    'alpha_dash' => ':attribute voi sisältää vain kirjaimia, numeroita ja väliviivoja.',
    'alpha_num' => ':attribute voi sisältää vain kirjaimia ja numeroita.',
    'array' => ':attribute on oltava taulukko.',
    'before' => ':attribute tulee olla päivämäärä ennen :date.',
    'before_or_equal' => ':attribute päiväyksen tulee olla sama tai ennen :date.',
    'between' => [
        'numeric' => ':attribute arvon täytyy olla välillä :min ja :max.',
        'file' => ':attribute on oltava :min ja :max kilotavun väliltä.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => ':attribute kentän tulee olla true tai false.',
    'confirmed' => ':attribute vahvistus ei täsmää.',
    'date' => ':attribute ei ole oikea päivämäärä.',
    'date_format' => ':attribute ei täsmää muodon :format kanssa.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field is required.',
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => ':attribute tulee olla kokonaisluku.',
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
    'present' => ':attribute kenttä on oltava läsnä.',
    'regex' => ':attribute muoto on virheellinen.',
    'required' => ':attribute kenttä on pakollinen.',
    'required_if' => ':attribute kenttä on pakollinen kun :other on :value.',
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
        'variable_value' => ':env variable',
        'invalid_password' => 'The password provided was invalid for this account.',
    ],
];
