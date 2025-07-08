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

    'accepted' => ':attribute musí byť prijatý.',
    'active_url' => ':attribute nieje validná adresa URL',
    'after' => ':attribute musí byť dátum po :date.',
    'after_or_equal' => ':attribute musí byť dátum po alebo rovný :date.',
    'alpha' => ':attribute môže obsahovať iba písmená.',
    'alpha_dash' => ':attribute môže obsahovať iba písmená, čísla a pomlčky.',
    'alpha_num' => ':attribute môže obsahovať iba písmená a čísla.',
    'array' => ':attribute musí byť array.',
    'before' => ':attribute musí byť dátum pred :date.',
    'before_or_equal' => ':attribute musí byť dátum pred alebo rovný :date.',
    'between' => [
        'numeric' => ':attribute musí byť medzi :min a :max.',
        'file' => ':attribute musí byť medzi :min a :max kilobajtov.',
        'string' => ':attribute musí byť medzi :min a :max znakmi.',
        'array' => ':attribute musí byť medzi :min a :max položkami.',
    ],

    'confirmed' => ':attribute sa nezhoduje.',
    'date' => ':attribute nie je platný dátum.',
    'date_format' => ':attribute nezodpovedá formátu :formát.',
    'different' => ':attribute a :other musia byť odlišné.',
    'digits' => ':attribute musí byť :digits číslice.',
    'digits_between' => ':attribute musí byť medzi :min a :max číslicami.',
    'dimensions' => ':attribute má neplatné rozmery obrázka.',

    'email' => ':attribute musí byť platná e-mailová adresa.',

    'file' => ':attribute musí byť súbor.',
    'filled' => ':attribute je povinné.',
    'image' => ':attribute musí byť obrázok.',

    'in_array' => ':attribute pole neexistuje v :other.',
    'integer' => ':attribute musí byť celé číslo.',
    'ip' => ':attribute musí byť platná IP adresa.',
    'json' => ':attribute musí byť platný JSON.',
    'max' => [
        'numeric' => ':attribute nesmie byť väčšie ako :max.',
        'file' => ':attribute nesmie byť väčšie ako :max kilobajtov.',
        'string' => ':attribute nesmie byť väčší ako :max znakov.',
        'array' => ':attribute nemôže mať viac ako :max položiek.',
    ],
    'mimes' => ':attribute musí byť súbor typu: :values.',
    'mimetypes' => ':attribute musí byť súbor typu: :values.',
    'min' => [
        'numeric' => ':attribute musí byť aspoň :min.',
        'file' => ':attribute musí mať aspoň :min kilobajtov.',
        'string' => ':attribute musí mať aspoň :min znakov.',
        'array' => ':attribute musí mať aspoň :min položiek.',
    ],

    'numeric' => ':attribute musí byť číslo.',

    'regex' => ':attribute formát je neplatný.',

    'required_with_all' => ':attribute je povinné, keď je prítomný :values.',

    'same' => ':attribute a :other sa musia zhodovať.',
    'size' => [
        'numeric' => ':attribute musí byť :size.',
        'file' => ':attribute musí byť :size kilobajtov.',
        'string' => ':attribute musí byť :size znakov.',
        'array' => ':attribute musí obsahovať položky :size.',
    ],
    'string' => ':attribute musí byť string.',
    'timezone' => ':attribute musí byť platná zóna.',

    'url' => ':attribute formát je neplatný.',

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
        'invalid_password' => 'Zadané heslo bolo pre tento účet neplatné.',
    ],
];
