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

    'accepted' => ':attribute musí být přijat.',
    'active_url' => ':attribute není platná URL adresa.',
    'after' => ':attribute nemůže být dříve než :date.',
    'after_or_equal' => ':attribute musí být datum po nebo stejné jako :date.',
    'alpha' => ':attribute smí obsahovat pouze písmena.',
    'alpha_dash' => ':attribute smí obsahovat pouze písmena, čísla a pomlčky.',
    'alpha_num' => ':attribute může obsahovat pouze písmena a čísla.',
    'array' => ':attribute musí být pole.',
    'before' => ':attribute musí mít datum před :date.',
    'before_or_equal' => ':attribute musí být datum před nebo stejné jako :date.',
    'between' => [
        'numeric' => ':attribute musí být v rozmezí :min až :max.',
        'file' => ':attribute musí být mezi :min a :max kilobajtů.',
        'string' => ':attribute musí být v rozmezí :min až :max znaků.',
        'array' => ':attribute musí mít v rozmezí :min až :max položek.',
    ],

    'confirmed' => 'Potvrzení :attribute se neshoduje.',
    'date' => ':attribute není platné datum.',
    'date_format' => ':attribute neodpovídá formátu :formát.',
    'different' => ':attribute a :other musí být odlišné.',
    'digits' => ':attribute musí obsahovat :digits číslic.',
    'digits_between' => ':attribute musí mít délku mezi :min a :max číslicemi.',
    'dimensions' => ':attribute má neplatné rozměry obrázku.',

    'email' => ':attribute musí obsahovat platnou e-mailovou adresu.',

    'file' => ':attribute musí být soubor.',
    'filled' => 'Pole :attribute je povinné.',
    'image' => ':attribute musí být obrázek.',

    'in_array' => 'Pole :attribute neexistuje v :other',
    'integer' => ':attribute musí být celé číslo.',
    'ip' => ':attribute musí obsahovat platnou IP adresu.',
    'json' => ':attribute musí být platný řetězec JSON.',
    'max' => [
        'numeric' => ':attribute nesmí být delší než :max.',
        'file' => ':attribute nesmí být vyšší než :max kilobajtů.',
        'string' => ':attribute nesmí být větší než :max znaků.',
        'array' => ':attribute nesmí obsahovat více než :max položek.',
    ],
    'mimes' => 'Atribut: musí být soubor typu: :values.',
    'mimetypes' => 'Atribut: musí být soubor typu: :values.',
    'min' => [
        'numeric' => ':attribute musí být alespoň :min.',
        'file' => ':attribute musí být alespoň :min kilobajtů.',
        'string' => 'Atribut musí být dlouhý alespoň :min znaků.',
        'array' => ':attribute musí obsahovat alespoň :min položek.',
    ],

    'numeric' => ':attribute musí být číslo.',

    'regex' => 'Formát :attribute je neplatný.',

    'required_with_all' => 'Pole :attribute je vyžadováno, pokud je zvoleno :values.',

    'same' => 'Atribut :attribute a :other se musí shodovat.',
    'size' => [
        'numeric' => ':attribute musí mít velikost :size.',
        'file' => ':attribute musí mít velikost :size Kb.',
        'string' => ':attribute musí mít :size znaků.',
        'array' => ':attribute musí obsahovat :size položek.',
    ],
    'string' => 'Atribut musí být textový řetězec.',
    'timezone' => ':attribute musí být platná zóna.',

    'url' => 'Formát :attribute není platný.',

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
        'variable_value' => ':env proměnná',
        'invalid_password' => 'Zadané heslo pro tento účet je neplatné.',
    ],
];
