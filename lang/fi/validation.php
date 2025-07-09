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
        'string' => ':attribute on oltava :min ja :max merkin väliltä.',
        'array' => ':attribute tulee sisältää :min ja :max väliltä olioita.',
    ],

    'confirmed' => ':attribute vahvistus ei täsmää.',
    'date' => ':attribute ei ole oikea päivämäärä.',
    'date_format' => ':attribute ei täsmää muodon :format kanssa.',
    'different' => ':attribute ja :other on oltava erilaisia.',
    'digits' => ':attribute on oltava :digits numeroa pitkä.',
    'digits_between' => ':attribute on oltava pituudeltaan :min ja :max numeron väliltä.',
    'dimensions' => ':attribute kuvan mitat ovat virheelliset.',

    'email' => ':attribute tulee olla kelvollinen sähköpostiosoite.',

    'file' => ':attribute tulee olla tiedosto.',
    'filled' => ':attribute kenttä on pakollinen.',
    'image' => ':attribute on oltava kuva.',

    'in_array' => ':attribute kenttää ei ole olemassa :other:ssa.',
    'integer' => ':attribute tulee olla kokonaisluku.',
    'ip' => ':attribute tulee olla kelvollinen IP-osoite.',
    'json' => ':attribute on oltava kelvollinen JSON-merkkijono.',
    'max' => [
        'numeric' => ':attribute saa olla korkeintaan :max.',
        'file' => ':attribute ei saa olla suurempi kuin :max kilotavua.',
        'string' => ':attribute ei saa olla suurempi kuin :max merkkiä.',
        'array' => ':attribute ei saa sisältää yli :max kohteita.',
    ],
    'mimes' => ':attribute tulee olla tiedosto jonka tyyppi on: :values.',
    'mimetypes' => ':attribute tulee olla tiedosto jonka tyyppi on: :values.',
    'min' => [
        'numeric' => ':attribute tulee olla vähintään :min.',
        'file' => ':attribute tulee olla vähintään :min kilotavua.',
        'string' => ':attribute tulee olla vähintään :min merkkiä.',
        'array' => ':attribute täytyy sisältää vähintään :min kohdetta.',
    ],

    'numeric' => ':attribute tulee olla numero.',

    'regex' => ':attribute muoto on virheellinen.',

    'required_with_all' => ':attribute kenttä on pakollinen kun :values ovat läsnä.',

    'same' => ':attribute ja :other tulee täsmätä.',
    'size' => [
        'numeric' => ':attribute on oltava :size.',
        'file' => ':attribute on oltava :size kilotavua.',
        'string' => ':attribute tulee olla :size merkkiä.',
        'array' => ':attribute tulee sisältää :size kohdetta.',
    ],
    'string' => ':attribute on oltava merkkijono.',
    'timezone' => ':attribute tulee olla validi aikavyöhyke.',

    'url' => ':attribute muoto on virheellinen.',

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
        'variable_value' => ':env muuttuja',
        'invalid_password' => 'Annettu salasana oli virheellinen tälle tilille.',
    ],
];
