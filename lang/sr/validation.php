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

    'accepted' => 'Polje :attribute mora biti prihvaćeno.',
    'active_url' => 'Polje :attribute nije validan URL.',
    'after' => 'Polje :attribute mora biti datum nakon :date.',
    'after_or_equal' => 'Polje :attribute mora biti datum nakon ili jednak :date.',
    'alpha' => 'Polje :attribute može sadržati samo slova.',
    'alpha_dash' => 'Polje :attribute može sadržati samo slova, brojeve i crte.',
    'alpha_num' => 'Polje :attribute može sadržati samo slova i brojeve.',
    'array' => 'Polje :attribute mora biti niz.',
    'before' => 'Polje :attribute mora biti datum pre :date.',
    'before_or_equal' => 'Polje :attribute mora biti datum pre ili jednak :date.',
    'between' => [
        'numeric' => 'Polje :attribute mora biti između :min i :max.',
        'file' => 'Polje :attribute mora biti između :min i :max kilobajta.',
        'string' => 'Polje :attribute mora biti između :min i :max karaktera.',
        'array' => 'Polje :attribute mora imati između :min i :max stavki.',
    ],

    'confirmed' => 'Potvrda za polje :attribute se ne poklapa.',
    'date' => 'Polje :attribute nije validan datum.',
    'date_format' => 'Polje :attribute se ne poklapa sa formatom :format.',
    'different' => 'Polje :attribute i :other moraju biti različiti.',
    'digits' => ':attribute mora imati :digits cifara.',
    'digits_between' => ':attribute mora biti između :min i :max cifara.',
    'dimensions' => ':attribute ima nevažeće dimenzije slike.',

    'email' => ':attribute mora biti važeća email adresa.',

    'file' => ':attribute mora biti fajl.',
    'filled' => 'Polje :attribute je obavezno.',
    'image' => ':attribute mora biti slika.',

    'in_array' => 'Polje :attribute ne postoji u :other.',
    'integer' => ':attribute mora biti ceo broj. :attribute mora biti ceo broj.',
    'ip' => ':attribute mora biti važeća IP adresa.',
    'json' => ':attribute mora biti važeći JSON string.',
    'max' => [
        'numeric' => ':attribute ne može biti veći od :max.',
        'file' => ':attribute ne može biti veći od :max kilobajta.',
        'string' => ':attribute ne može imati više od :max karaktera.',
        'array' => ':attribute ne može imati više od :max stavki.',
    ],
    'mimes' => ':attribute mora biti fajl tipa: :values.',
    'mimetypes' => ':attribute mora biti fajl tipa: :values.',
    'min' => [
        'numeric' => ':attribute mora biti najmanje :min.',
        'file' => ':attribute mora imati najmanje :min kilobajta.',
        'string' => ':attribute mora imati najmanje :min karaktera.',
        'array' => ':attribute mora imati najmanje :min stavki.',
    ],

    'numeric' => ':attribute mora biti broj.',

    'regex' => 'Format polja :attribute je nevažeći.',

    'required_with_all' => 'Polje :attribute je obavezno kada je vrednost :values prisutna.',

    'same' => ':attribute i :other moraju da se poklapaju.',
    'size' => [
        'numeric' => ':attribute mora biti :size.',
        'file' => ':attribute mora imati :size kilobajta.',
        'string' => ':attribute mora imati :size karaktera.',
        'array' => ':attribute mora sadržati :size stavki.',
    ],
    'string' => ':attribute mora biti tekstualni niz.',
    'timezone' => ':attribute mora biti validna zona.',

    'url' => 'Format za :attribute je nevažeći.',

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
        'variable_value' => ':env varijabla',
        'invalid_password' => 'Šifra koju ste uneli nije važeća za ovaj nalog.',
    ],
];
