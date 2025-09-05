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

    'accepted' => 'A(z) :attribute el kell legyen fogadva!',
    'active_url' => 'A(z) :attribute nem érvényes URL!',
    'after' => 'A(z) :attribute :date utáni dátum kell, hogy legyen!',
    'after_or_equal' => 'A(z) :attribute a :date után vagy azzal megegyező dátumnak kell lennie.',
    'alpha' => ':attribute csak betűket tartalmazhat.',
    'alpha_dash' => ':attribute csak betűket, számokat és kötőjelet tartalmazhat.',
    'alpha_num' => 'A(z) :attribute kizárólag betűket és számokat tartalmazhat!',
    'array' => ':attribute csak tömb típusú lehet.',
    'before' => 'A(z) :attribute -nak a :date előtti dátumnak kell lennie.',
    'before_or_equal' => 'A(z) :attribute a :date előtti vagy azzal megegyező dátumnak kell lennie.',
    'between' => [
        'numeric' => 'A(z) :attribute :min és :max között kell lennie.',
        'file' => ':attribute értékének :min és :max kilobyte között kell lennie.',
        'string' => ':attribute :min és :max karakter között kell legyen.',
        'array' => ':attribute mennyiségének :min és :max elem között kell lennie.',
    ],

    'confirmed' => ':attribute megerősítése nem egyezik.',
    'date' => ':attribute nem egy érvényes dátum.',
    'date_format' => ':attribute nem egyezik :format formátummal.',
    'different' => ':attribute és :other értékének különböznie kell.',
    'digits' => ':attribute :digits számból kell álljon.',
    'digits_between' => ':attribute :min és :max számjegy között kell lennie.',
    'dimensions' => ':attribute attribútum képfelbontása érvénytelen.',

    'email' => 'A(z) :attribute érvényes e-mail címnek kell lennie.',

    'file' => 'A(z) :attribute fájlnak kell lennie.',
    'filled' => ':attribute mező kötelező.',
    'image' => 'A :attribute képnek kell lennie.',

    'in_array' => ':attribute nem létezik itt: :other.',
    'integer' => 'A :attribute egész számnak kell lennie.',
    'ip' => ':attribute érvényes IP cím kell, hogy legyen.',
    'json' => ':attribute csak érvényes JSON lehet.',
    'max' => [
        'numeric' => ':attribute nem lehet nagyobb mint :max.',
        'file' => ':attribute nem lehet nagyobb mint :max kilobyte.',
        'string' => ':attribute nem lehet nagyobb, mint :max karakter.',
        'array' => 'A :attribute nem tartalmazhat több adatot, mint :max.',
    ],
    'mimes' => 'A :attribute kizárólag csak :value fájl formátum lehet.',
    'mimetypes' => 'A :attribute kizárólag csak :value fájl formátum lehet.',
    'min' => [
        'numeric' => 'A :attribute legalább :min kell lennie.',
        'file' => 'A :attribute legalább :min kilobytenak kell lennie.',
        'string' => 'A(z) :attribute minimum :min karakter hosszú kell, hogy legyen.',
        'array' => 'A(z) :attribute kell hogy legyen minimum :min elem.',
    ],

    'numeric' => 'A(z) :attribute szám kell, hogy legyen.',

    'regex' => 'A(z) :attribute formátum érvénytelen.',

    'required_with_all' => 'A :attribute mező kötelező, amikor a :values jelenben van.',

    'same' => ':attribute és :other értékének egyeznie kell.',
    'size' => [
        'numeric' => 'Az :attribute -nak :size-nak kell lennie.
',
        'file' => ':attribute :size kilobyte-nak kell lennie.',
        'string' => ':attribute :size karakter kell legyen.',
        'array' => ':attribute :size elemet kell tartalmazzon.',
    ],
    'string' => 'A :attribute egy szövegnek kell lennie.
',
    'timezone' => 'A(z) :attribute létező zónának kell lennie.',

    'url' => 'A(z) :attribute formátum érvénytelen.',

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
        'variable_value' => ':env változó',
        'invalid_password' => 'A megadott jelszó érvénytelen volt ehhez a fiókhoz.',
    ],
];
